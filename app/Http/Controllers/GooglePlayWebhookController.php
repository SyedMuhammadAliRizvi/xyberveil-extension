<?php

namespace App\Http\Controllers;

use App\Models\GoogleNotification;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Middleware\AuthTokenMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GooglePlayWebhookController extends Controller
{
    private $client;

    public function __construct()
    {
        // Initialize Google API client for verification
        try {
            $keyFile = storage_path(config('services.google_play.service_account_json'));

            if (! file_exists($keyFile)) {
                Log::error('Google Play service account key not found at: '.$keyFile);
                throw new \Exception('Google Play service account key not found');
            }

            $credentials = new ServiceAccountCredentials(
                'https://www.googleapis.com/auth/androidpublisher',
                $keyFile
            );

            $middleware = new AuthTokenMiddleware($credentials);
            $stack = HandlerStack::create();
            $stack->push($middleware);

            $this->client = new Client([
                'handler' => $stack,
                'base_uri' => 'https://androidpublisher.googleapis.com',
                'auth' => 'google_auth',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to initialize Google Play API client', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function __invoke(Request $request)
    {
        // Get the raw message from Pub/Sub
        $message = $request->input('message');

        if (! $message) {
            Log::error('Google Play Webhook: No message provided');

            return response()->json(['error' => 'No message provided'], 400);
        }

        try {
            // Decode the base64 data
            $data = json_decode(base64_decode($message['data'] ?? ''), true);
            Log::info('Google Play Webhook received data', ['data' => $data]);

            if (! $data) {
                Log::error('Google Play Webhook: Failed to decode message data');

                return response()->json(['error' => 'Invalid message data'], 400);
            }

            // Extract notification details
            $packageName = $data['packageName'] ?? null;
            $eventTimeMillis = $data['eventTimeMillis'] ?? null;
            $subscriptionNotification = $data['subscriptionNotification'] ?? null;
            $testNotification = $data['testNotification'] ?? null;

            // Validate package name
            if ($packageName && $packageName !== config('services.google_play.package_name')) {
                Log::error('Google Play Webhook: Invalid package name', [
                    'expected' => config('services.google_play.package_name'),
                    'received' => $packageName,
                ]);

                return response()->json(['error' => 'Invalid package name'], 400);
            }

            // Handle test notifications
            if ($testNotification) {
                Log::info('Google Play Webhook: Test notification received', [
                    'version' => $testNotification['version'] ?? null,
                ]);

                return response()->json(['status' => 'success'], 200);
            }

            if (! $subscriptionNotification) {
                Log::warning('Google Play Webhook: No subscription notification found');

                return response()->json(['status' => 'success'], 200);
            }

            $notificationType = $subscriptionNotification['notificationType'] ?? null;
            $purchaseToken = $subscriptionNotification['purchaseToken'] ?? null;
            $subscriptionId = $subscriptionNotification['subscriptionId'] ?? null;

            if (! $notificationType || ! $purchaseToken || ! $subscriptionId) {
                Log::error('Google Play Webhook: Missing required notification fields');

                return response()->json(['error' => 'Missing required fields'], 400);
            }

            // Check for duplicate notification
            $existingNotification = GoogleNotification::where('purchase_token', $purchaseToken)
                ->where('notification_type', $notificationType)
                ->first();

            if ($existingNotification) {
                Log::info('Google Play Webhook: Duplicate notification received', [
                    'purchase_token' => $purchaseToken,
                    'notification_type' => $notificationType,
                ]);

                return response()->json(['status' => 'success'], 200);
            }

            // Fetch subscription details from Google Play API
            $subscriptionDetails = $this->fetchSubscriptionDetails(
                $packageName,
                $subscriptionId,
                $purchaseToken
            );

            if (! $subscriptionDetails) {
                Log::error('Google Play Webhook: Failed to fetch subscription details', [
                    'purchase_token' => $purchaseToken,
                    'subscription_id' => $subscriptionId,
                ]);

                return response()->json(['error' => 'Failed to fetch subscription details'], 500);
            }

            // Save notification to database
            $googleNotification = GoogleNotification::create([
                'notification_type' => $notificationType,
                'package_name' => $packageName,
                'event_time_millis' => $eventTimeMillis,
                'subscription_id' => $subscriptionId,
                'purchase_token' => $purchaseToken,
                'payload' => $data,
                'subscription_details' => $subscriptionDetails,
                'obfuscated_external_account_id' => $subscriptionDetails['obfuscatedExternalAccountId'] ?? null,
                'order_id' => $subscriptionDetails['orderId'] ?? null,
            ]);

            // Dispatch event for processing
            // GooglePlayWebhookReceived::dispatch(
            //     $notificationType,
            //     $subscriptionDetails,
            //     $googleNotification
            // );

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Google Play Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Fetch subscription details from Google Play API
     */
    private function fetchSubscriptionDetails(string $packageName, string $subscriptionId, string $purchaseToken): ?array
    {
        try {
            $url = sprintf(
                '/androidpublisher/v3/applications/%s/purchases/subscriptions/%s/tokens/%s',
                $packageName,
                $subscriptionId,
                $purchaseToken
            );

            $response = $this->client->get($url);
            $body = json_decode($response->getBody()->getContents(), true);

            return $body;
        } catch (\Exception $e) {
            Log::error('Failed to fetch Google Play subscription details', [
                'package_name' => $packageName,
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
