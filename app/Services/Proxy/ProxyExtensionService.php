<?php

namespace App\Services\Proxy;

use App\Models\ProxyCredential;
use App\Models\ProxyExtensionDevice;
use App\Models\ProxyServer;
use App\Models\ProxySession;
use App\Models\User;
use App\Support\ProxyApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProxyExtensionService
{
    public function __construct(
        private ProxyExtensionAuthService $authService,
        private ProxyExtensionDeviceService $deviceService,
        private ProxyProvisioningService $provisioningService,
    ) {}

    public function me(User $user): array
    {
        $active = ProxySession::query()
            ->where('user_id', $user->id)
            ->whereNull('ended_at')
            ->first();

        return array_merge([
            'id' => $user->id,
            'email' => $user->email,
            'display_name' => $user->display_name,
            'role' => $user->role,
            'status' => $user->proxyStatus(),
            'linux_username' => $user->linux_username,
            'active_session' => $active ? [
                'session_id' => $active->id,
                'server_id' => $active->proxy_server_id,
                'started_at' => $active->started_at?->toIso8601String(),
            ] : null,
        ], $this->authService->accountProfile($user));
    }

    /**
     * Live plan/subscription details from XyberVeil's own v3 API — called
     * server-side with the user's stored session token (set by
     * ProxyExtensionAuthService::otpLogin()/guestLogin()), never trusted
     * from the extension directly.
     */
    public function xyberveilPlan(User $user): array
    {
        if (! $user->xyberveil_api_token) {
            return ['available' => false];
        }

        try {
            $response = Http::withToken($user->xyberveil_api_token)
                ->timeout(8)
                ->get('https://api.xyberveil.com/v3/details');
        } catch (\Throwable $e) {
            Log::warning('xyberveil.details.exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return ['available' => false];
        }

        if (! $response->successful()) {
            Log::warning('xyberveil.details.failed', [
                'user_id' => $user->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['available' => false];
        }

        $data = $response->json() ?? [];

        return [
            'available' => true,
            'plan' => $data['plan'] ?? null,
            'active' => $data['active'] ?? null,
            'expired' => $data['expired'] ?? null,
            'expiry_date' => $data['expiry_date'] ?? null,
            'is_pro' => $data['is_pro'] ?? null,
            'is_trial' => $data['is_trial'] ?? null,
            'days' => $data['days'] ?? null,
            'plan_obj' => isset($data['plan_obj']) ? [
                'title' => $data['plan_obj']['title'] ?? null,
                'total_price_of_plan' => $data['plan_obj']['total_price_of_plan'] ?? null,
                'price_per_month' => $data['plan_obj']['price_per_month'] ?? null,
                'save_percent' => $data['plan_obj']['save_percent'] ?? null,
            ] : null,
        ];
    }

    public function servers(User $user): array
    {
        if ($user->proxyStatus() !== 'active') {
            $this->abort('Account not active', 403);
        }

        return ProxyServer::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn (ProxyServer $server) => [
                'id' => $server->id,
                'name' => $server->name,
                'country' => $server->country,
                'flag' => $server->flag,
                'proxy_port' => $server->proxy_port,
                'socks_port' => $server->socks_port,
            ])
            ->values()
            ->all();
    }

    public function credentials(User $user, int $serverId): array
    {
        if ($user->proxyStatus() !== 'active') {
            $this->abort('Account not active', 403);
        }

        $this->assertCanConnect($user);

        $server = ProxyServer::query()
            ->whereKey($serverId)
            ->where('is_active', true)
            ->first();

        if (! $server) {
            $this->abort('Server not found or inactive', 404);
        }

        $credential = ProxyCredential::query()
            ->where('user_id', $user->id)
            ->where('proxy_server_id', $server->id)
            ->first();

        if (! $credential) {
            $this->provisioningService->provisionUserOnServer($user, $server);
            $credential = ProxyCredential::query()
                ->where('user_id', $user->id)
                ->where('proxy_server_id', $server->id)
                ->first();
        }

        if (! $credential) {
            $this->abort(
                'Credentials not yet provisioned for this server. Please wait a moment or contact the administrator.',
                503
            );
        }

        return [
            'host' => $server->ip,
            'proxy_port' => $server->proxy_port,
            'socks_port' => $server->socks_port,
            'username' => $user->linux_username,
            'password' => $credential->proxy_password,
        ];
    }

    public function startSession(User $user, int $serverId, Request $request): array
    {
        if ($user->proxyStatus() !== 'active') {
            $this->abort('Account not active', 403);
        }

        $this->assertCanConnect($user);

        $now = now();

        $openSessions = ProxySession::query()
            ->where('user_id', $user->id)
            ->whereNull('ended_at')
            ->get();

        foreach ($openSessions as $session) {
            $session->update([
                'ended_at' => $now,
                'duration_seconds' => (int) $session->started_at?->diffInSeconds($now),
            ]);
        }

        $session = ProxySession::query()->create([
            'user_id' => $user->id,
            'proxy_server_id' => $serverId,
            'started_at' => $now,
            'client_ip' => $this->clientIp($request),
        ]);

        return ['session_id' => $session->id];
    }

    public function endSession(User $user, int $sessionId): array
    {
        $session = ProxySession::query()
            ->whereKey($sessionId)
            ->where('user_id', $user->id)
            ->first();

        if (! $session) {
            $this->abort('Session not found', 404);
        }

        $now = now();
        $session->update([
            'ended_at' => $now,
            'duration_seconds' => (int) $session->started_at?->diffInSeconds($now),
        ]);

        return ['duration_seconds' => $session->duration_seconds];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function registerDevice(User $user, array $payload): array
    {
        $existing = ProxyExtensionDevice::query()
            ->where('device_key', (string) $payload['device_key'])
            ->first();

        if ($existing && $existing->user_id !== $user->id) {
            $this->abort('Device key already registered to another account', 409);
        }

        if ($existing && ! $existing->is_allowed) {
            $this->abort(ProxyExtensionDeviceService::DEVICE_UNAUTHORIZED_MSG, 403);
        }

        $this->deviceService->upsertExtensionDevice(
            $user,
            (string) $payload['device_key'],
            (string) $payload['browser_name'],
            (string) ($payload['browser_version'] ?? ''),
            (string) ($payload['os_name'] ?? ''),
            (string) ($payload['os_version'] ?? ''),
            (string) ($payload['extension_version'] ?? ''),
            $payload['ip_address'] ?? null,
            $payload['timezone'] ?? null,
        );

        $device = ProxyExtensionDevice::query()
            ->where('device_key', (string) $payload['device_key'])
            ->firstOrFail();

        return [
            'id' => $device->id,
            'device_key' => $device->device_key,
            'browser_name' => $device->browser_name,
            'last_seen' => $device->last_seen?->toIso8601String(),
        ];
    }

    public function listDevices(User $user, ?string $currentDeviceKey = null): array
    {
        return ProxyExtensionDevice::query()
            ->where('user_id', $user->id)
            ->orderByDesc('last_seen')
            ->get()
            ->map(fn (ProxyExtensionDevice $device) => [
                'id' => $device->id,
                'device_key' => $device->device_key,
                'device_type' => $device->device_type,
                'browser_name' => $device->browser_name,
                'browser_version' => $device->browser_version,
                'os_name' => $device->os_name,
                'os_version' => $device->os_version,
                'extension_version' => $device->extension_version,
                'is_allowed' => $device->is_allowed,
                'last_seen' => $device->last_seen?->toIso8601String(),
                'is_current' => $currentDeviceKey !== null && $device->device_key === $currentDeviceKey,
            ])
            ->values()
            ->all();
    }

    public function deviceStatus(User $user, string $deviceKey): array
    {
        $this->deviceService->assertDeviceAuthorized($user->id, $deviceKey, true);

        $device = ProxyExtensionDevice::query()
            ->where('user_id', $user->id)
            ->where('device_key', trim($deviceKey))
            ->first();

        return [
            'authorized' => true,
            'is_allowed' => (bool) ($device?->is_allowed),
        ];
    }

    private function clientIp(Request $request): string
    {
        $forwarded = $request->header('X-Forwarded-For');

        if ($forwarded) {
            return trim(explode(',', $forwarded)[0]);
        }

        return $request->ip() ?? 'unknown';
    }

    private function assertCanConnect(User $user): void
    {
        if ($user->canConnectProxy()) {
            return;
        }

        // Local trial/subscription has lapsed — fall back to the same live
        // XyberVeil plan shown on the Account screen before blocking.
        $xyberveilPlan = $this->xyberveilPlan($user);
        if ($xyberveilPlan['available'] && $xyberveilPlan['active']) {
            return;
        }

        $this->abort('Trial expired — subscribe to continue.', 403);
    }

    private function abort(string $message, int $status): never
    {
        throw new HttpResponseException(ProxyApiResponse::detail($message, $status));
    }
}
