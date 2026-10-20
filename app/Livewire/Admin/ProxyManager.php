<?php

namespace App\Livewire\Admin;

use App\Models\ProxyServer;
use App\Models\ProxySession;
use App\Services\Proxy\ProxyAgentClient;
use App\Services\ProxyMonitorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ProxyManager extends Component
{
    public ProxyServer $server;

    public array $serverData = [];

    public array $stats = [];

    public array $sessions = [];

    public ?string $loadError = null;

    public $cpuUsage = 0;

    public $ramUsage = 0;

    public $diskUsage = 0;

    public $healthScore = 0;

    public $status = 'unknown';

    public $lastUpdated = null;

    public $isLoading = false;

    public $connectedUsers = [];

    public $totalConnected = 0;

    public $ikev2Connected = 0;

    public $openvpnConnected = 0;

    public $wireguardConnected = 0;

    public $vpnTypeFilter = 'all';

    public $availableScripts = [
        [
            'script' => 'health.sh',
            'label' => 'Run Health Check',
            'color' => 'success',
            'icon' => 'fluent:check-20-regular',
        ],
    ];

    public function placeholder(): string
    {
        return <<<'HTML'
<div class="row mt-2">
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="card" aria-hidden="true">
            <div class="card-body">
                <h5 class="card-title placeholder-glow">
                    <span class="placeholder col-6"></span>
                </h5>
                <p class="card-text placeholder-glow">
                    <span class="placeholder col-7"></span>
                    <span class="placeholder col-4"></span>
                    <span class="placeholder col-4"></span>
                    <span class="placeholder col-6"></span>
                    <span class="placeholder col-8"></span>
                </p>
            </div>
        </div>
    </div>
</div>
HTML;
    }

    public function mount(ProxyServer $proxyServer): void
    {
        $this->server = $proxyServer;
        $this->lastUpdated = now();
        $this->refreshData();

        // Load directly during the initial render instead of via wire:init (an extra
        // round-trip after first paint, and a client-side call path prone to bugs).
        $this->fetchMetrics();
        $this->fetchConnectedUsers();
    }

    /**
     * Livewire 3 + browser tooling may call $wire.toJSON() during error logging.
     *
     * @return array<string, mixed>
     */
    public function toJSON(): array
    {
        return [];
    }

    public function refreshAll(): void
    {
        $this->refreshData();
        $this->fetchMetrics();
        $this->fetchConnectedUsers();
    }

    private function syncStatCards(): void
    {
        $this->ikev2Connected = (int) ($this->stats['provisioned_users'] ?? 0);
        $this->openvpnConnected = (int) ($this->stats['active_sessions'] ?? 0);
        $this->wireguardConnected = (int) ($this->stats['synced_credentials'] ?? 0);
        $this->totalConnected = $this->countActiveSessionsForServer($this->server->id);
    }

    private function countActiveSessionsForServer(int $serverId): int
    {
        return ProxySession::query()
            ->where('proxy_server_id', $serverId)
            ->whereNull('ended_at')
            ->count();
    }

    private function loadConnectedUsersFromDatabase(int $serverId): void
    {
        $activeSessions = ProxySession::query()
            ->where('proxy_server_id', $serverId)
            ->whereNull('ended_at')
            ->with('user')
            ->orderByDesc('started_at')
            ->get();

        $this->totalConnected = $activeSessions->count();
        $this->connectedUsers = $activeSessions->map(function (ProxySession $session) {
            $durationSeconds = (int) $session->started_at?->diffInSeconds(now());

            return [
                'name' => $session->user?->linux_username
                    ?? $session->user?->display_name
                    ?? $session->user?->email
                    ?? 'Unknown',
                'ip_address' => $session->client_ip ?? 'N/A',
                'uptime' => gmdate('H:i:s', max(0, $durationSeconds)),
                'vpn_type' => 'proxy',
            ];
        })->take(100)->values()->all();
    }

    private function freshServer(): ProxyServer
    {
        return ProxyServer::query()->findOrFail($this->server->id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function applyAgentConnections(array $data): void
    {
        $connections = collect($data['connections'] ?? [])->reverse()->values();

        $this->totalConnected = (int) ($data['count'] ?? $connections->count());
        $this->connectedUsers = $connections->map(function (array $connection) {
            $target = strtolower($connection['target'] ?? '');

            return [
                'name' => $connection['user'] ?? 'Unknown',
                'ip_address' => $connection['client_ip'] ?? 'N/A',
                'uptime' => $connection['target'] ?? 'N/A',
                'vpn_type' => str_contains($target, 'socks') ? 'socks' : (str_contains($target, 'http') ? 'http' : 'proxy'),
            ];
        })->take(100)->all();
    }

    private function populateConnectedUsersFromSessions(): void
    {
        $this->loadConnectedUsersFromDatabase($this->server->id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function mergeAgentConnectionActivity(array $data): void
    {
        if (empty($data['connections'])) {
            return;
        }

        $latestByUser = collect($data['connections'])
            ->reverse()
            ->unique(fn (array $connection) => $connection['user'] ?? $connection['client_ip'] ?? '')
            ->keyBy(fn (array $connection) => $connection['user'] ?? '');

        $this->connectedUsers = collect($this->connectedUsers)
            ->map(function (array $user) use ($latestByUser) {
                $agentEntry = $latestByUser->get($user['name']);

                if (! $agentEntry) {
                    return $user;
                }

                $target = strtolower($agentEntry['target'] ?? '');

                return array_merge($user, [
                    'ip_address' => $agentEntry['client_ip'] ?? $user['ip_address'],
                    'vpn_type' => str_contains($target, 'socks') ? 'socks' : (str_contains($target, 'http') ? 'http' : 'proxy'),
                ]);
            })
            ->values()
            ->all();
    }

    public function refreshData(): void
    {
        $this->isLoading = true;
        $this->loadError = null;

        try {
            $service = app(ProxyMonitorService::class);
            $this->server->refresh();
            $this->serverData = $service->serverDetail($this->server->id);
            $this->stats = $service->serverManageStats($this->server->id);
            $this->sessions = $service->sessions([
                'server_id' => $this->server->id,
                'limit' => 50,
            ]);

            $this->syncStatCards();
            $this->status = $this->stats['agent_status'] ?? ($this->serverData['setup_status'] ?? 'unknown');

            if ($this->status === 'ok') {
                $this->status = 'healthy';
            }

            $this->loadConnectedUsersFromDatabase($this->server->id);

            if (! empty($this->stats['agent_connections_list'])) {
                $this->mergeAgentConnectionActivity([
                    'connections' => $this->stats['agent_connections_list'],
                ]);
            }
        } catch (\Throwable $exception) {
            $this->loadError = $exception->getMessage();
        } finally {
            $this->isLoading = false;
        }
    }

    public function fetchMetrics(): void
    {
        try {
            $url = "http://{$this->server->ip}:5001/api/vps-health";
            $response = Http::timeout(10)->get($url);

            if (! $response->successful()) {
                throw new \Exception("Host metrics API failed with status: {$response->status()}");
            }

            $data = $response->json();

            $this->cpuUsage = $data['cpu_usage_percentage'] ?? 0;
            $this->ramUsage = $data['ram_usage_percentage'] ?? 0;
            $this->diskUsage = $data['disk_usage_percentage'] ?? 0;
            $this->healthScore = $data['health_score'] ?? 0;
            $this->status = $data['status'] ?? ($this->stats['agent_status'] ?? 'unknown');
            $this->lastUpdated = ! empty($data['current_date_utc'])
                ? Carbon::parse($data['current_date_utc'])
                : now();

            $this->dispatch(
                'updateUsage',
                cpu: $this->cpuUsage,
                ram: $this->ramUsage,
                disk: $this->diskUsage,
                health: $this->healthScore,
                status: $this->status,
            );
        } catch (\Exception $exception) {
            $this->cpuUsage = 0;
            $this->ramUsage = 0;
            $this->diskUsage = 0;
            $this->healthScore = ($this->stats['agent_status'] ?? '') === 'ok' ? 100 : 0;

            $this->dispatch('updateUsage', cpu: 0, ram: 0, disk: 0, health: $this->healthScore, status: 'error');

            Log::info("Host metrics unavailable for proxy {$this->server->ip}: {$exception->getMessage()}");
        }
    }

    public function fetchConnectedUsers(): void
    {
        $this->loadConnectedUsersFromDatabase($this->server->id);
        $this->lastUpdated = now();
        $this->dispatch('connectedUsersUpdated');
    }

    public function runScript(string $scriptName): void
    {
        $server = $this->freshServer();

        $authParam = $server->ssh_password
            ? ['password' => $server->ssh_password]
            : [];

        $baseParams = [
            'vps-ip' => $server->ip,
            'vps-domain' => $server->ip,
            'username' => $server->ssh_user,
            'script-name' => $scriptName,
        ];

        $baseUrl = config('services.script_runner.url', env('SCRIPT_BASE_URL'));
        $url = $baseUrl.'?'.http_build_query(array_merge($baseParams, $authParam));

        $scriptIndex = array_search($scriptName, array_column($this->availableScripts, 'script'), true);
        $scriptLabel = $scriptIndex !== false
            ? $this->availableScripts[$scriptIndex]['label']
            : $scriptName;

        $this->dispatch('open-script-url', url: $url);
        $this->dispatch(
            'sweetToast',
            type: 'info',
            message: 'Opening script runner for: '.$scriptLabel,
            title: 'Script Execution'
        );
    }

    public function runSetup(): void
    {
        try {
            app(ProxyMonitorService::class)->setupServer($this->server->id);
            $this->dispatch('sweetAlert', title: 'Setup started', message: 'Installing 3proxy + agent over SSH. This can take ~60s.', type: 'info');
            $this->refreshData();
        } catch (\Throwable $exception) {
            $this->dispatch('sweetAlert', title: 'Error', message: $exception->getMessage(), type: 'error');
        }
    }

    public function syncUsers(): void
    {
        try {
            app(ProxyMonitorService::class)->syncServer($this->server->id);
            $this->dispatch('sweetAlert', title: 'Sync started', message: 'Pushing active users to this server.', type: 'info');
        } catch (\Throwable $exception) {
            $this->dispatch('sweetAlert', title: 'Error', message: $exception->getMessage(), type: 'error');
        }
    }

    public function toggleActive(): void
    {
        try {
            app(ProxyMonitorService::class)->updateServer($this->server->id, [
                'is_active' => ! $this->server->is_active,
            ]);
            $this->refreshData();
            $this->dispatch('sweetAlert', title: 'Updated', message: 'Server status updated.', type: 'success');
        } catch (\Throwable $exception) {
            $this->dispatch('sweetAlert', title: 'Error', message: $exception->getMessage(), type: 'error');
        }
    }

    public function isInstalling(): bool
    {
        return ($this->serverData['setup_status'] ?? $this->server->setup_status) === 'installing';
    }

    public function render()
    {
        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.proxy-manager')
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}
