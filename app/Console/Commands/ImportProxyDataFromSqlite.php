<?php

namespace App\Console\Commands;

use App\Models\ProxyCredential;
use App\Models\ProxyExtensionDevice;
use App\Models\ProxyRefreshToken;
use App\Models\ProxyServer;
use App\Models\ProxySession;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportProxyDataFromSqlite extends Command
{
    protected $signature = 'proxy:import-sqlite {path=C:/xampp/htdocs/vps-monitor/data/proxy.db}';

    protected $description = 'Import SecureProxy data from vps-monitor SQLite into the local database';

    public function handle(): int
    {
        $path = $this->argument('path');

        if (! is_file($path)) {
            $this->error("SQLite file not found: {$path}");

            return self::FAILURE;
        }

        $sqlite = new \PDO('sqlite:'.$path);
        $sqlite->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        DB::transaction(function () use ($sqlite): void {
            $idMap = $this->importUsers($sqlite);
            $this->importServers($sqlite);
            $this->importCredentials($sqlite, $idMap);
            $this->importDevices($sqlite, $idMap);
            $this->importSessions($sqlite, $idMap);
            $this->importRefreshTokens($sqlite, $idMap);
        });

        $this->info('Proxy data imported successfully.');

        return self::SUCCESS;
    }

    /**
     * @return array<int, int>
     */
    private function importUsers(\PDO $sqlite): array
    {
        $rows = $sqlite->query('SELECT * FROM proxy_users')->fetchAll(\PDO::FETCH_ASSOC);
        $idMap = [];

        foreach ($rows as $row) {
            $email = mb_strtolower((string) $row['email']);
            $user = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();

            if ($user) {
                $user->update([
                    'linux_username' => $row['linux_username'],
                    'email_verified_at' => $row['status'] === 'active' ? ($row['approved_at'] ?? now()) : null,
                    'banned_at' => $row['status'] === 'banned' ? ($row['approved_at'] ?? now()) : null,
                    'ban_reason' => $row['notes'],
                ]);
                if ($row['status'] === 'active' && $row['approved_at'] && ! $user->trial_used) {
                    $user->update([
                        'trial_started_at' => $row['approved_at'],
                        'trial_ends_at' => \Illuminate\Support\Carbon::parse($row['approved_at'])->addDays((int) config('proxy.trial_days', 30)),
                        'trial_used' => true,
                    ]);
                }
            } else {
                $user = User::query()->create([
                    'name' => $row['display_name'] ?: $row['email'],
                    'email' => $row['email'],
                    'password' => $row['password_hash'],
                    'role' => $row['role'] === 'admin' ? 'admin' : 'user',
                    'linux_username' => $row['linux_username'],
                    'email_verified_at' => $row['status'] === 'active' ? ($row['approved_at'] ?? now()) : null,
                    'banned_at' => $row['status'] === 'banned' ? ($row['approved_at'] ?? now()) : null,
                    'ban_reason' => $row['notes'],
                    'trial_started_at' => $row['status'] === 'active' ? $row['approved_at'] : null,
                    'trial_ends_at' => $row['status'] === 'active' && $row['approved_at']
                        ? \Illuminate\Support\Carbon::parse($row['approved_at'])->addDays((int) config('proxy.trial_days', 30))
                        : null,
                    'trial_used' => $row['status'] === 'active',
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['created_at'],
                ]);
            }

            $idMap[(int) $row['id']] = $user->id;
        }

        $this->line('Imported '.count($rows).' proxy users into users table.');

        return $idMap;
    }

    private function importServers(\PDO $sqlite): void
    {
        $rows = $sqlite->query('SELECT * FROM proxy_servers')->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            ProxyServer::query()->updateOrCreate(
                ['id' => $row['id']],
                [
                    'infra_server_id' => $row['infra_server_id'] ?? null,
                    'name' => $row['name'],
                    'country' => $row['country'],
                    'flag' => $row['flag'],
                    'ip' => $row['ip'],
                    'proxy_port' => $row['proxy_port'],
                    'socks_port' => $row['socks_port'],
                    'agent_port' => $row['agent_port'],
                    'agent_key' => $row['agent_key'],
                    'ssh_user' => $row['ssh_user'],
                    'ssh_password' => $row['ssh_password'],
                    'ssh_port' => $row['ssh_port'],
                    'is_active' => (bool) $row['is_active'],
                    'setup_status' => $row['setup_status'],
                    'setup_log' => $row['setup_log'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['created_at'],
                ]
            );
        }

        $this->line('Imported '.count($rows).' proxy servers.');
    }

    /**
     * @param  array<int, int>  $idMap
     */
    private function importCredentials(\PDO $sqlite, array $idMap): void
    {
        $rows = $sqlite->query('SELECT * FROM proxy_credentials')->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            ProxyCredential::query()->updateOrCreate(
                [
                    'user_id' => $idMap[(int) $row['user_id']] ?? $row['user_id'],
                    'proxy_server_id' => $row['server_id'],
                ],
                [
                    'proxy_password' => $row['proxy_password'],
                    'synced' => (bool) $row['synced'],
                    'synced_at' => $row['synced_at'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['created_at'] ?? $row['created_at'],
                ]
            );
        }

        $this->line('Imported '.count($rows).' proxy credentials.');
    }

    /**
     * @param  array<int, int>  $idMap
     */
    private function importDevices(\PDO $sqlite, array $idMap): void
    {
        if (! $this->sqliteTableExists($sqlite, 'proxy_extension_devices')) {
            $this->warn('Skipped extension devices — table not found in SQLite.');

            return;
        }

        $rows = $sqlite->query('SELECT * FROM proxy_extension_devices')->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            ProxyExtensionDevice::query()->updateOrCreate(
                ['id' => $row['id']],
                [
                    'user_id' => $idMap[(int) $row['user_id']] ?? $row['user_id'],
                    'device_key' => $row['device_key'],
                    'device_type' => $row['device_type'],
                    'browser_name' => $row['browser_name'],
                    'browser_version' => $row['browser_version'],
                    'os_name' => $row['os_name'],
                    'os_version' => $row['os_version'],
                    'extension_version' => $row['extension_version'],
                    'is_allowed' => (bool) ($row['is_allowed'] ?? true),
                    'last_seen' => $row['last_seen'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['created_at'],
                ]
            );
        }

        $this->line('Imported '.count($rows).' extension devices.');
    }

    /**
     * @param  array<int, int>  $idMap
     */
    private function importSessions(\PDO $sqlite, array $idMap): void
    {
        $rows = $sqlite->query('SELECT * FROM proxy_sessions')->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            ProxySession::query()->updateOrCreate(
                ['id' => $row['id']],
                [
                    'user_id' => isset($row['user_id']) ? ($idMap[(int) $row['user_id']] ?? null) : null,
                    'proxy_server_id' => $row['server_id'],
                    'started_at' => $row['started_at'],
                    'ended_at' => $row['ended_at'],
                    'client_ip' => $row['client_ip'],
                    'duration_seconds' => $row['duration_seconds'],
                    'created_at' => $row['started_at'],
                    'updated_at' => $row['started_at'],
                ]
            );
        }

        $this->line('Imported '.count($rows).' proxy sessions.');
    }

    /**
     * @param  array<int, int>  $idMap
     */
    private function importRefreshTokens(\PDO $sqlite, array $idMap): void
    {
        $rows = $sqlite->query('SELECT * FROM proxy_refresh_tokens')->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            ProxyRefreshToken::query()->updateOrCreate(
                ['id' => $row['id']],
                [
                    'user_id' => $idMap[(int) $row['user_id']] ?? $row['user_id'],
                    'token' => $row['token'],
                    'device_key' => $row['device_key'] ?? null,
                    'expires_at' => $row['expires_at'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['created_at'],
                ]
            );
        }

        $this->line('Imported '.count($rows).' refresh tokens.');
    }

    private function sqliteTableExists(\PDO $sqlite, string $table): bool
    {
        $statement = $sqlite->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name = ?");
        $statement->execute([$table]);

        return (bool) $statement->fetchColumn();
    }
}
