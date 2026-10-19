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
        if (! $user->canConnectProxy()) {
            $this->abort('Trial expired — subscribe to continue.', 403);
        }
    }

    private function abort(string $message, int $status): never
    {
        throw new HttpResponseException(ProxyApiResponse::detail($message, $status));
    }
}
