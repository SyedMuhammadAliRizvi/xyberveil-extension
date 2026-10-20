<?php

namespace App\Http\Controllers;

use App\Events\AppStoreWebhookReceived;
use App\Models\AppleNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Readdle\AppStoreServerAPI\Exception\AppStoreServerNotificationException;
use Readdle\AppStoreServerAPI\ResponseBodyV2;
use Readdle\AppStoreServerAPI\Util\Helper;

class AppStoreWebhookController extends Controller
{
    private $rootCertificatePEM = null;

    public function __construct()
    {
        // Load the Apple Root CA certificate from local storage
        try {
            $certificatePath = storage_path('app/private/certificates/AppleRootCA-G3.cer');

            if (! file_exists($certificatePath)) {
                Log::error('Apple Root CA certificate not found at: '.$certificatePath);
                throw new \Exception('Apple Root CA certificate not found');
            }

            $certificateContent = file_get_contents($certificatePath);
            $this->rootCertificatePEM = Helper::toPEM($certificateContent);

            if (! $this->rootCertificatePEM) {
                throw new \Exception('Failed to convert certificate to PEM format');
            }
        } catch (\Exception $e) {
            Log::error('Failed to load Apple Root CA certificate', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function handle(Request $request)
    {
        $rawNotification = $request->getContent();

        if (! $request->hasValidSignature()) {
            Log::warning('App Store Webhook: Invalid or missing URL signature', [
                'ip' => $request->ip(),
                'query' => $request->query(),
            ]);

            return response()->json(['error' => 'Invalid signature'], 403);
        }

        try {

            if (! $rawNotification) {
                Log::error('App Store Webhook: No request content provided');

                return response()->json(['error' => 'No request content provided'], 400);
            }
            if (! $this->rootCertificatePEM) {
                Log::error('App Store Webhook: Root certificate not available');

                return response()->json(['error' => 'Root certificate not available'], 500);
            }

            // Decode and verify the signedPayload using Apple's root certificate
            $responseBodyV2 = ResponseBodyV2::createFromRawNotification(
                $rawNotification,
                $this->rootCertificatePEM
            );

            // Get the notification type and payload
            $notificationType = $responseBodyV2->getNotificationType();
            $subtype = $responseBodyV2->getSubtype();
            $notificationUUID = $responseBodyV2->getNotificationUUID();
            $rawSignedDate = $responseBodyV2->getSignedDate();
            $version = $responseBodyV2->getVersion();

            // Convert signed date to proper datetime
            $signedDate = $this->convertAppleTimestamp($rawSignedDate);

            // Get app metadata
            $appMetadata = $responseBodyV2->getAppMetadata();

            // Get the transaction info and renewal info
            $transactionInfo = $appMetadata ? $appMetadata->getTransactionInfo() : null;
            $renewalInfo = $appMetadata ? $appMetadata->getRenewalInfo() : null;

            // Extract key data
            $appAccountToken = $transactionInfo ? $transactionInfo->getAppAccountToken() : null;
            $originalTransactionId = $transactionInfo ? $transactionInfo->getOriginalTransactionId() : null;
            $transactionId = $transactionInfo ? $transactionInfo->getTransactionId() : null;
            $productId = $transactionInfo ? $transactionInfo->getProductId() : null;

            $existingAppleNotification = AppleNotification::where('notification_uuid', $notificationUUID)->first();

            if ($existingAppleNotification) {
                Log::info('App Store Webhook: Duplicate notification received', [
                    'notification_uuid' => $notificationUUID,
                ]);

                return response()->json(['status' => 'success', 'message' => 'Duplicate notification received'], 200);
            }

            $appleNotification = AppleNotification::create([
                'notification_type' => $notificationType,
                'subtype' => $subtype,
                'notification_uuid' => $notificationUUID,
                'version' => $version,
                'signed_date' => $signedDate,
                'payload' => json_decode($rawNotification, true),
                'transaction_info' => $transactionInfo ? json_decode(json_encode($transactionInfo), true) : null,
                'renewal_info' => $renewalInfo ? json_decode(json_encode($renewalInfo), true) : null,
                'app_account_token' => $appAccountToken,
                'original_transaction_id' => $originalTransactionId,
                'transaction_id' => $transactionId,
                'product_id' => $productId,
            ]);

            AppStoreWebhookReceived::dispatch(
                $notificationType,
                $subtype,
                $transactionInfo,
                $renewalInfo,
                $responseBodyV2,
                $appleNotification
            );

            return response()->json(['status' => 'success'], 200);
        } catch (AppStoreServerNotificationException $e) {
            Log::error('App Store Webhook verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Verification failed'], 400);
        } catch (\Exception $e) {
            Log::error('App Store Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Convert Apple timestamp to proper Carbon datetime
     */
    private function convertAppleTimestamp($timestamp)
    {
        if (! $timestamp) {
            return null;
        }

        // If the timestamp is a string that looks like a large number (milliseconds)
        if (is_string($timestamp) && is_numeric($timestamp)) {
            $timestamp = (int) $timestamp;
        }

        // If it's already a Carbon/DateTime object, return as is
        if ($timestamp instanceof Carbon || $timestamp instanceof \DateTime) {
            return $timestamp;
        }

        // If it's a string that's not numeric, try to parse it as a date string
        if (is_string($timestamp) && ! is_numeric($timestamp)) {
            try {
                return Carbon::parse($timestamp);
            } catch (\Exception $e) {
                Log::error('Failed to parse Apple date string', [
                    'timestamp' => $timestamp,
                    'error' => $e->getMessage(),
                ]);

                return null;
            }
        }

        // Handle numeric timestamps
        if (is_numeric($timestamp)) {
            $timestamp = (int) $timestamp;

            // If timestamp is in milliseconds (greater than year 2100 in seconds)
            // This is a safe check since Apple's timestamps are usually in milliseconds
            if ($timestamp > 4102444800000) { // 2100-01-01 in milliseconds
                $timestamp = $timestamp / 1000; // Convert milliseconds to seconds
            } elseif ($timestamp > 4102444800) { // 2100-01-01 in seconds
                // Already in seconds, but seems too large - might be milliseconds with fewer digits
                // Let's check if this is a reasonable timestamp
                if ($timestamp > 32503680000) { // Year 3000 in seconds
                    $timestamp = $timestamp / 1000;
                }
            }

            try {
                // Try to create Carbon instance from timestamp
                return Carbon::createFromTimestamp($timestamp);
            } catch (\Exception $e) {
                Log::error('Failed to convert Apple timestamp', [
                    'timestamp' => $timestamp,
                    'error' => $e->getMessage(),
                ]);

                return null;
            }
        }

        Log::error('Unknown Apple timestamp format', [
            'timestamp' => $timestamp,
            'type' => gettype($timestamp),
        ]);

        return null;
    }
}
