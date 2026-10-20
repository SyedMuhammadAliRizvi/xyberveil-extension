<?php

namespace App\Services\Proxy;

use App\Models\ProxyCredential;
use App\Models\ProxyServer;
use App\Models\User;
use Illuminate\Support\Str;

class ProxyProvisioningService
{
    public function __construct(private ProxyAgentClient $agentClient) {}

    public function provisionUserOnServer(User $user, ProxyServer $server): bool
    {
        $credential = ProxyCredential::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'proxy_server_id' => $server->id,
            ],
            [
                'proxy_password' => $this->generatePassword(),
            ]
        );

        $ok = $this->agentClient->call($server, 'POST', '/users', [
            'username' => $user->linux_username,
            'password' => $credential->proxy_password,
        ]);

        if ($ok) {
            $credential->update([
                'synced' => true,
                'synced_at' => now(),
            ]);
        }

        return $ok;
    }

    public function provisionUserEverywhere(int $userId): void
    {
        $user = User::query()->find($userId);

        if (! $user || ! $user->isProxyActive()) {
            return;
        }

        $servers = ProxyServer::query()->where('is_active', true)->get();

        foreach ($servers as $server) {
            $this->provisionUserOnServer($user, $server);
        }
    }

    public function provisionAllUsersOnServer(int $serverId): void
    {
        $server = ProxyServer::query()->find($serverId);

        if (! $server) {
            return;
        }

        $users = User::query()->whereNotNull('linux_username')->whereNotNull('email_verified_at')->whereNull('banned_at')->get();

        foreach ($users as $user) {
            $this->provisionUserOnServer($user, $server);
        }
    }

    public function deprovisionUserEverywhere(string $linuxUsername): void
    {
        $servers = ProxyServer::query()->get();

        foreach ($servers as $server) {
            $this->agentClient->call($server, 'DELETE', '/users/'.$linuxUsername);
        }
    }

    public function generatePassword(int $length = 24): string
    {
        return Str::password($length, letters: true, numbers: true, symbols: false);
    }
}
