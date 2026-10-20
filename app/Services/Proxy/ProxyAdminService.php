<?php

namespace App\Services\Proxy;

use App\Jobs\Proxy\DeprovisionProxyUserJob;
use App\Jobs\Proxy\ProvisionAllUsersOnProxyServerJob;
use App\Jobs\Proxy\ProvisionProxyUserJob;
use App\Jobs\Proxy\SetupProxyServerJob;
use App\Models\ProxyCredential;
use App\Models\ProxyExtensionDevice;
use App\Models\ProxyRefreshToken;
use App\Models\ProxyServer;
use App\Models\ProxySession;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProxyAdminService
{
    public function dashboard(): array
    {
        return [
            'users' => [
                'total' => User::query()->whereNotNull('linux_username')->count(),
                'active' => User::query()->whereNotNull('linux_username')->whereNotNull('email_verified_at')->whereNull('banned_at')->count(),
                'pending' => User::query()->whereNotNull('linux_username')->whereNull('email_verified_at')->count(),
                'banned' => User::query()->whereNotNull('linux_username')->whereNotNull('banned_at')->count(),
            ],
            'servers' => [
                'total' => ProxyServer::query()->count(),
                'active' => ProxyServer::query()->where('is_active', true)->count(),
            ],
            'sessions' => [
                'live' => ProxySession::query()->whereNull('ended_at')->count(),
                'total' => ProxySession::query()->count(),
            ],
        ];
    }

    public function users(): array
    {
        return User::query()
            ->whereNotNull('linux_username')
            ->withCount('proxySessions')
            ->with(['proxyExtensionDevices' => fn ($query) => $query->orderByDesc('last_seen')])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (User $user) => $this->formatUserListItem($user))
            ->values()
            ->all();
    }

    public function userDetail(int $id): array
    {
        $user = User::query()
            ->whereNotNull('linux_username')
            ->withCount('proxySessions')
            ->with([
                'proxyExtensionDevices' => fn ($query) => $query->orderByDesc('last_seen'),
                'proxySessions' => fn ($query) => $query->with('server')->orderByDesc('started_at')->limit(50),
            ])
            ->findOrFail($id);

        return [
            'id' => $user->id,
            'email' => $user->email,
            'display_name' => $user->display_name,
            'linux_username' => $user->linux_username,
            'role' => $user->role,
            'status' => $user->proxyStatus(),
            'created_at' => $user->created_at?->toIso8601String(),
            'approved_at' => $user->email_verified_at?->toIso8601String(),
            'notes' => $user->ban_reason,
            'trial_started_at' => $user->trial_started_at?->toIso8601String(),
            'trial_ends_at' => $user->trial_ends_at?->toIso8601String(),
            'session_count' => $user->proxy_sessions_count,
            'devices' => $user->proxyExtensionDevices->map(fn (ProxyExtensionDevice $device) => $this->formatDevice($device))->values()->all(),
            'sessions' => $user->proxySessions->map(fn (ProxySession $session) => $this->formatSession($session))->values()->all(),
        ];
    }

    public function userAction(int $id, string $action, ?string $notes = null): array
    {
        $user = User::query()->whereNotNull('linux_username')->findOrFail($id);

        return match ($action) {
            'approve' => $this->approveUser($user, $notes),
            'ban' => $this->banUser($user, $notes),
            'unban' => $this->unbanUser($user),
            'delete' => $this->deleteUser($user),
            'reset_password' => $this->resetPassword($user),
            default => throw ValidationException::withMessages([
                'action' => "Unknown action: {$action}",
            ]),
        };
    }

    public function deleteUserDevice(int $userId, int $deviceId): void
    {
        $device = ProxyExtensionDevice::query()
            ->where('user_id', $userId)
            ->whereKey($deviceId)
            ->firstOrFail();

        $this->revokeDeviceTokens($userId, $device->device_key);
        $device->delete();
    }

    public function updateUserDevice(int $userId, int $deviceId, bool $isAllowed): array
    {
        $device = ProxyExtensionDevice::query()
            ->where('user_id', $userId)
            ->whereKey($deviceId)
            ->firstOrFail();

        $device->update(['is_allowed' => $isAllowed]);

        if (! $isAllowed) {
            $this->revokeDeviceTokens($userId, $device->device_key);
        }

        return [
            'message' => $isAllowed ? 'Device allowed' : 'Device blocked — browser will be logged out',
            'is_allowed' => $device->is_allowed,
        ];
    }

    public function servers(): array
    {
        return ProxyServer::query()
            ->orderBy('created_at')
            ->get()
            ->map(fn (ProxyServer $server) => $this->formatServer($server))
            ->values()
            ->all();
    }

    public function createServer(array $data): array
    {
        $agentKey = $data['agent_key'] ?? Str::random(32);

        $server = ProxyServer::query()->create([
            'name' => $data['name'],
            'country' => $data['country'],
            'flag' => $data['flag'] ?? '🌐',
            'ip' => $data['ip'],
            'proxy_port' => (int) ($data['proxy_port'] ?? 10080),
            'socks_port' => (int) ($data['socks_port'] ?? 10081),
            'agent_port' => (int) ($data['agent_port'] ?? 9191),
            'agent_key' => $agentKey,
            'ssh_user' => $data['ssh_user'] ?? 'root',
            'ssh_password' => $data['ssh_password'] ?? null,
            'ssh_port' => (int) ($data['ssh_port'] ?? 22),
            'setup_status' => 'pending',
        ]);

        ProvisionAllUsersOnProxyServerJob::dispatch($server->id);

        return [
            'id' => $server->id,
            'agent_key' => $agentKey,
            'message' => 'Server added',
        ];
    }

    public function updateServer(int $id, array $data): array
    {
        $server = ProxyServer::query()->findOrFail($id);

        $server->update(collect($data)->only([
            'name', 'country', 'flag', 'ip', 'proxy_port', 'socks_port',
            'ssh_user', 'ssh_port', 'is_active', 'ssh_password',
        ])->filter(fn ($value) => $value !== null)->all());

        return ['message' => 'Updated'];
    }

    public function deleteServer(int $id): void
    {
        ProxyServer::query()->whereKey($id)->delete();
    }

    public function setupServer(int $id): array
    {
        $server = ProxyServer::query()->findOrFail($id);

        if (! $server->sshPassword()) {
            $message = $server->sshPasswordDecryptFailed()
                ? 'SSH password cannot be decrypted on this machine — copy APP_KEY from the original server or re-enter the SSH password in Edit Server'
                : 'SSH password not set — edit the server first';

            throw ValidationException::withMessages([
                'ssh_password' => $message,
            ]);
        }

        $server->update([
            'setup_status' => 'installing',
            'setup_log' => 'Starting installation…',
        ]);

        // Run immediately so admin does not need a background queue worker for setup.
        SetupProxyServerJob::dispatchSync($server->id);

        $server->refresh();

        return [
            'message' => $server->setup_status === 'ready'
                ? 'Installation completed — server is ready'
                : 'Installation finished with status: '.$server->setup_status,
            'setup_status' => $server->setup_status,
        ];
    }

    public function syncServer(int $id): array
    {
        ProxyServer::query()->findOrFail($id);

        ProvisionAllUsersOnProxyServerJob::dispatchSync($id);

        return ['message' => 'Sync completed'];
    }

    /**
     * @return array<string, mixed>
     */
    public function serverDetail(int $id): array
    {
        $server = ProxyServer::query()->findOrFail($id);

        return $this->formatServer($server);
    }

    /**
     * @return array<string, mixed>
     */
    public function serverManageStats(int $id): array
    {
        $server = ProxyServer::query()->findOrFail($id);

        $activeSessions = ProxySession::query()
            ->where('proxy_server_id', $id)
            ->whereNull('ended_at')
            ->count();

        $totalSessions = ProxySession::query()
            ->where('proxy_server_id', $id)
            ->count();

        $credentialsCount = ProxyCredential::query()
            ->where('proxy_server_id', $id)
            ->count();

        $syncedCredentials = ProxyCredential::query()
            ->where('proxy_server_id', $id)
            ->where('synced', true)
            ->count();

        $agentStatus = null;
        $agentError = null;
        $agentConnections = null;
        $provisionedUsers = null;
        $agentUserCount = null;

        $agentConnectionsList = [];

        if ($server->setup_status === 'ready' && $server->agent_key) {
            try {
                $client = app(ProxyAgentClient::class);
                $health = $client->get($server, '/health');
                $agentStatus = $health['status'] ?? 'unknown';
                $agentUserCount = $health['user_count'] ?? null;

                $users = $client->get($server, '/users');
                $provisionedUsers = count($users['users'] ?? []);

                $connections = $client->get($server, '/connections');
                $agentConnections = $connections['count'] ?? 0;
                $agentConnectionsList = $connections['connections'] ?? [];
            } catch (\Throwable $exception) {
                $agentError = $exception->getMessage();
                $agentStatus = 'error';
            }
        }

        return [
            'active_sessions' => $activeSessions,
            'total_sessions' => $totalSessions,
            'credentials_count' => $credentialsCount,
            'synced_credentials' => $syncedCredentials,
            'agent_status' => $agentStatus,
            'agent_error' => $agentError,
            'agent_connections' => $agentConnections,
            'agent_connections_list' => $agentConnectionsList,
            'provisioned_users' => $provisionedUsers,
            'agent_user_count' => $agentUserCount,
        ];
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function sessions(array $params = []): array
    {
        $query = ProxySession::query()->with(['user', 'server']);

        if (! empty($params['live_only'])) {
            $query->whereNull('ended_at');
        }

        if (! empty($params['user_id'])) {
            $query->where('user_id', $params['user_id']);
        }

        if (! empty($params['server_id'])) {
            $query->where('proxy_server_id', $params['server_id']);
        }

        $limit = (int) ($params['limit'] ?? 200);

        return $query
            ->orderByDesc('started_at')
            ->limit($limit)
            ->get()
            ->map(fn (ProxySession $session) => $this->formatSessionListItem($session))
            ->values()
            ->all();
    }

    private function approveUser(User $user, ?string $notes): array
    {
        app(ProxyExtensionAuthService::class)->activateProxyAccess($user);

        ProvisionProxyUserJob::dispatch($user->id);

        return ['message' => "Action 'approve' applied to user {$user->id}"];
    }

    private function banUser(User $user, ?string $notes): array
    {
        $user->update([
            'banned_at' => now(),
            'ban_reason' => $notes ?: $user->ban_reason,
        ]);

        return ['message' => "Action 'ban' applied to user {$user->id}"];
    }

    private function unbanUser(User $user): array
    {
        $user->update([
            'banned_at' => null,
            'ban_reason' => null,
        ]);
        ProvisionProxyUserJob::dispatch($user->id);

        return ['message' => "Action 'unban' applied to user {$user->id}"];
    }

    private function deleteUser(User $user): array
    {
        $linuxUsername = $user->linux_username;
        $userId = $user->id;
        $isProxyOnly = ! $user->devices()->exists();

        $user->proxyCredentials()->delete();
        $user->proxyExtensionDevices()->delete();
        $user->proxyRefreshTokens()->delete();
        $user->proxySessions()->delete();

        if ($isProxyOnly) {
            $user->delete();
        } else {
            $user->update([
                'linux_username' => null,
            ]);
        }

        if ($linuxUsername) {
            DeprovisionProxyUserJob::dispatch($linuxUsername);
        }

        return ['message' => "Action 'delete' applied to user {$userId}"];
    }

    private function resetPassword(User $user): array
    {
        $newPassword = app(ProxyProvisioningService::class)->generatePassword();
        $user->update(['password' => Hash::make($newPassword)]);

        return [
            'message' => 'Password reset',
            'new_password' => $newPassword,
        ];
    }

    private function revokeDeviceTokens(int $userId, ?string $deviceKey): void
    {
        $key = trim((string) $deviceKey);

        if ($key === '') {
            return;
        }

        ProxyRefreshToken::query()
            ->where('user_id', $userId)
            ->where('device_key', $key)
            ->delete();
    }

    private function formatUserListItem(User $user): array
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'display_name' => $user->display_name,
            'linux_username' => $user->linux_username,
            'role' => $user->role,
            'status' => $user->proxyStatus(),
            'created_at' => $user->created_at?->toIso8601String(),
            'approved_at' => $user->email_verified_at?->toIso8601String(),
            'notes' => $user->ban_reason,
            'session_count' => $user->proxy_sessions_count ?? $user->proxySessions()->count(),
            'device_count' => $user->proxyExtensionDevices->count(),
            'devices' => $user->proxyExtensionDevices->map(fn (ProxyExtensionDevice $device) => $this->formatDevice($device))->values()->all(),
        ];
    }

    private function formatDevice(ProxyExtensionDevice $device): array
    {
        return [
            'id' => $device->id,
            'device_key' => $device->device_key,
            'device_type' => $device->device_type,
            'browser_name' => $device->browser_name,
            'browser_version' => $device->browser_version,
            'os_name' => $device->os_name,
            'os_version' => $device->os_version,
            'extension_version' => $device->extension_version,
            'ip_address' => $device->ip_address,
            'city' => $device->city,
            'country' => $device->country,
            'country_code' => $device->country_code,
            'is_allowed' => $device->is_allowed,
            'last_seen' => $device->last_seen?->toIso8601String(),
            'created_at' => $device->created_at?->toIso8601String(),
        ];
    }

    private function formatSession(ProxySession $session): array
    {
        $ended = $session->ended_at;

        return [
            'id' => $session->id,
            'server_name' => $session->server?->name,
            'server_flag' => $session->server?->flag,
            'started_at' => $session->started_at?->toIso8601String(),
            'ended_at' => $ended?->toIso8601String(),
            'duration_seconds' => $ended
                ? $session->duration_seconds
                : (int) $session->started_at?->diffInSeconds(now()),
            'client_ip' => $session->client_ip,
            'is_active' => $ended === null,
        ];
    }

    private function formatSessionListItem(ProxySession $session): array
    {
        $formatted = $this->formatSession($session);

        return array_merge($formatted, [
            'user_email' => $session->user?->email,
            'user_display' => $session->user?->display_name,
        ]);
    }

    private function formatServer(ProxyServer $server): array
    {
        return [
            'id' => $server->id,
            'infra_server_id' => $server->infra_server_id,
            'name' => $server->name,
            'country' => $server->country,
            'flag' => $server->flag,
            'ip' => $server->ip,
            'proxy_port' => $server->proxy_port,
            'socks_port' => $server->socks_port,
            'agent_port' => $server->agent_port,
            'ssh_user' => $server->ssh_user,
            'ssh_port' => $server->ssh_port,
            'is_active' => $server->is_active,
            'setup_status' => $server->setup_status,
            'setup_log' => $server->setup_log,
            'has_ssh_password' => $server->hasStoredSshPassword(),
            'ssh_password_needs_reset' => $server->sshPasswordDecryptFailed(),
            'created_at' => $server->created_at?->toIso8601String(),
        ];
    }
}
