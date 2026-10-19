<?php

namespace App\Livewire\Admin;

use App\Models\ProxyServer;
use App\Services\LocationService;
use App\Services\Proxy\ProxyQueueRunner;
use App\Services\ProxyMonitorService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use phpseclib3\Net\SSH2;

class ProxyServers extends Component
{
    public array $servers = [];

    public ?string $loadError = null;

    public bool $isLoading = false;

    public ?int $serverId = null;

    public $name;

    public $country;

    public $flag = '🌐';

    public $ip;

    public $proxy_port = 10080;

    public $socks_port = 10081;

    public $agent_port = 9191;

    public $ssh_user = 'root';

    public $ssh_password;

    public $ssh_port = 22;

    public bool $isEdit = false;

    public ?string $viewingLog = null;

    public ?int $selectedServer = null;

    public ?string $selectedServerLabel = null;

    public bool $isTerminalBusy = false;

    public ?int $runningSetupServerId = null;

    public string $terminalOutput = "Ready to connect...\n";

    public bool $isDetectingLocation = false;

    public ?string $locationDetectError = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
            'country' => 'required|string|max:80',
            'flag' => 'nullable|string|max:8',
            'ip' => 'required|ip',
            'proxy_port' => 'required|integer',
            'socks_port' => 'required|integer',
            'agent_port' => 'required|integer',
            'ssh_user' => 'required|string',
            'ssh_password' => $this->isEdit ? 'nullable|string' : 'required|string',
            'ssh_port' => 'required|integer',
        ];
    }

    public function mount(): void
    {
        $this->loadServers();
    }

    public function loadServers(): void
    {
        $this->isLoading = true;
        $this->loadError = null;

        try {
            $this->servers = app(ProxyMonitorService::class)->servers();
        } catch (\Throwable $e) {
            $this->servers = [];
            $this->loadError = 'Could not load proxy servers: '.$e->getMessage();
        } finally {
            $this->isLoading = false;
        }
    }

    public function hasInstalling(): bool
    {
        foreach ($this->servers as $server) {
            if ($server['setup_status'] === 'installing') {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{total: int, active: int, installing: int, failed: int}
     */
    public function getStatsProperty(): array
    {
        return [
            'total' => count($this->servers),
            'active' => collect($this->servers)->where('setup_status', 'ready')->count(),
            'installing' => collect($this->servers)->where('setup_status', 'installing')->count(),
            'failed' => collect($this->servers)->where('setup_status', 'failed')->count(),
        ];
    }

    public function resetForm(): void
    {
        $this->reset([
            'serverId', 'name', 'country', 'ip', 'ssh_password', 'isEdit',
        ]);
        $this->flag = '🌐';
        $this->proxy_port = 10080;
        $this->socks_port = 10081;
        $this->agent_port = 9191;
        $this->ssh_user = 'root';
        $this->ssh_port = 22;
        $this->resetValidation();
    }

    /**
     * Auto-fills country + flag from the IP as soon as the admin finishes
     * typing it (fires on blur, see wire:model.live.blur in the view) —
     * reuses the same LocationService the device-geolocation jobs use.
     * Leaves whatever the admin already has if detection fails or the IP
     * can't be resolved (e.g. still typing, local/private IP), so this
     * never clobbers a manual override with nothing.
     */
    public function updatedIp(string $value): void
    {
        $this->locationDetectError = null;

        if (! filter_var($value, FILTER_VALIDATE_IP)) {
            return;
        }

        $this->isDetectingLocation = true;

        try {
            $location = app(LocationService::class)->getLocationFromIP($value);
            $code = $location['countryCode'] ?? (preg_match('/^[A-Za-z]{2}$/', (string) ($location['country'] ?? '')) ? $location['country'] : null);

            if (! $code) {
                $this->locationDetectError = 'Could not detect location for this IP — enter country/flag manually.';

                return;
            }

            $this->country = $location['country'] && strlen($location['country']) > 2 ? $location['country'] : strtoupper($code);
            $this->flag = $this->countryCodeToFlagEmoji($code) ?: $this->flag;
        } finally {
            $this->isDetectingLocation = false;
        }
    }

    private function countryCodeToFlagEmoji(string $code): ?string
    {
        $code = strtoupper(trim($code));

        if (! preg_match('/^[A-Z]{2}$/', $code)) {
            return null;
        }

        return mb_chr(127397 + ord($code[0])).mb_chr(127397 + ord($code[1]));
    }

    public function editServer(int $id): void
    {
        $server = collect($this->servers)->firstWhere('id', $id);

        if (! $server) {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Server not found', type: 'error');

            return;
        }

        $this->resetForm();
        $this->isEdit = true;
        $this->serverId = $server['id'];
        $this->name = $server['name'];
        $this->country = $server['country'];
        $this->flag = $server['flag'];
        $this->ip = $server['ip'];
        $this->proxy_port = $server['proxy_port'];
        $this->socks_port = $server['socks_port'];
        $this->agent_port = $server['agent_port'];
        $this->ssh_user = $server['ssh_user'];
        $this->ssh_port = $server['ssh_port'];
    }

    public function saveServer(): void
    {
        $this->validate();

        $payload = [
            'name' => $this->name,
            'country' => $this->country,
            'flag' => $this->flag ?: '🌐',
            'ip' => $this->ip,
            'proxy_port' => (int) $this->proxy_port,
            'socks_port' => (int) $this->socks_port,
            'agent_port' => (int) $this->agent_port,
            'ssh_user' => $this->ssh_user,
            'ssh_port' => (int) $this->ssh_port,
        ];

        if ($this->ssh_password) {
            $payload['ssh_password'] = $this->ssh_password;
        }

        try {
            if ($this->isEdit && $this->serverId) {
                app(ProxyMonitorService::class)->updateServer($this->serverId, $payload);
            } else {
                app(ProxyMonitorService::class)->createServer($payload);
            }

            $this->dispatch('closeModal', model: 'proxyServerModal');
            $this->dispatch('sweetAlert', title: 'Success', message: 'Proxy server '.($this->isEdit ? 'updated' : 'created').'.', type: 'success');
            $this->resetForm();
            $this->loadServers();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    public function deleteServer(int $id): void
    {
        try {
            app(ProxyMonitorService::class)->deleteServer($id);
            $this->dispatch('sweetAlert', title: 'Deleted', message: 'Proxy server deleted.', type: 'success');
            $this->loadServers();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    public function runSetup(int $id): void
    {
        $this->runningSetupServerId = $id;

        try {
            $result = app(ProxyMonitorService::class)->setupServer($id);
            $status = $result['setup_status'] ?? null;

            if ($status === 'ready') {
                $this->dispatch('sweetAlert', title: 'Ready', message: $result['message'] ?? 'Server is ready.', type: 'success');
            } elseif ($status === 'failed') {
                $this->dispatch('sweetAlert', title: 'Setup failed', message: 'Check the setup log for details.', type: 'error');
            } else {
                $this->dispatch('sweetAlert', title: 'Setup finished', message: $result['message'] ?? 'Done.', type: 'info');
            }

            $this->loadServers();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        } finally {
            $this->runningSetupServerId = null;
        }
    }

    public function syncUsers(int $id): void
    {
        try {
            $result = app(ProxyMonitorService::class)->syncServer($id);
            $this->dispatch('sweetAlert', title: 'Synced', message: $result['message'] ?? 'Users synced.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    public function viewLog(int $id): void
    {
        $server = collect($this->servers)->firstWhere('id', $id);
        $this->viewingLog = $server['setup_log'] ?? 'No log available yet.';
        $this->dispatch('openLogModal');
    }

    public function openTerminal(int $serverId): void
    {
        $server = ProxyServer::query()->find($serverId);

        if (! $server) {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Server not found', type: 'error');

            return;
        }

        $this->selectedServer = $server->id;
        $this->selectedServerLabel = "{$server->flag} {$server->name} ({$server->ip})";
        $pendingJobs = DB::table('jobs')->count();

        $this->terminalOutput = implode("\n", [
            "Selected: {$this->selectedServerLabel}",
            "SSH: {$server->ssh_user}@{$server->ip}:{$server->ssh_port}",
            "Setup status: {$server->setup_status}",
            "Pending queue jobs: {$pendingJobs}",
            '',
            'Ready. Use Run SSH, Run Setup Script, or Run Queue Worker.',
            '',
        ]);

        $this->dispatch('scroll-terminal');
    }

    public function connectSSH(): void
    {
        if ($this->isTerminalBusy || ! $this->selectedServer) {
            return;
        }

        $this->isTerminalBusy = true;

        try {
            $server = $this->requireSelectedServer();
            $this->appendTerminal("\n=== Run SSH ===");
            $this->appendTerminal("Connecting to {$server->ip}:{$server->ssh_port}…");

            $ssh = $this->openSshConnection($server);
            $hostname = trim((string) $ssh->exec('hostname'));
            $uptime = trim((string) $ssh->exec('uptime -p 2>/dev/null || uptime'));

            $this->appendTerminal('Connected successfully.');
            $this->appendTerminal("Hostname: {$hostname}");
            $this->appendTerminal("Uptime: {$uptime}");
            $this->appendTerminal('Ready to execute script or process the queue.');
        } catch (\Throwable $e) {
            $this->appendTerminal('✗ '.$e->getMessage());
        } finally {
            $this->isTerminalBusy = false;
            $this->dispatch('scroll-terminal');
        }
    }

    public function executeScript(): void
    {
        if ($this->isTerminalBusy || ! $this->selectedServer) {
            return;
        }

        $this->isTerminalBusy = true;

        try {
            $server = $this->requireSelectedServer();
            $this->appendTerminal("\n=== Execute Setup Script ===");
            $this->appendTerminal("Running full proxy-agent install on {$server->ip}…");
            $this->appendTerminal('This may take ~1–2 minutes.');

            $result = app(ProxyMonitorService::class)->setupServer($server->id);
            $server->refresh();

            $this->appendTerminal($result['message'] ?? 'Setup finished.');
            $this->appendTerminal("Setup status: {$server->setup_status}");

            if ($server->setup_log) {
                $this->appendTerminal('--- setup log ---');
                $this->appendTerminal($this->tailText($server->setup_log, 60));
            }

            $this->loadServers();

            if ($server->setup_status === 'ready') {
                $this->dispatch('sweetAlert', title: 'Ready', message: 'Proxy agent installed successfully.', type: 'success');
            } else {
                $this->dispatch('sweetAlert', title: 'Setup failed', message: 'Check the terminal log for details.', type: 'error');
            }
        } catch (\Throwable $e) {
            $this->appendTerminal('✗ '.$e->getMessage());
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        } finally {
            $this->isTerminalBusy = false;
            $this->dispatch('scroll-terminal');
        }
    }

    public function runQueueWorker(): void
    {
        if ($this->isTerminalBusy) {
            return;
        }

        $this->isTerminalBusy = true;

        try {
            $this->appendTerminal("\n=== Run Queue Worker ===");

            $pending = DB::table('jobs')->count();
            $this->appendTerminal("Pending jobs before start: {$pending}");

            if ($pending === 0) {
                $this->appendTerminal('Queue is empty — nothing to process.');

                return;
            }

            foreach (DB::table('jobs')->orderBy('id')->limit(10)->get() as $job) {
                $payload = json_decode($job->payload, true);
                $name = $payload['displayName'] ?? 'UnknownJob';
                $this->appendTerminal("  • #{$job->id} {$name}");
            }

            if ($pending > 10) {
                $this->appendTerminal('  … and '.($pending - 10).' more');
            }

            $this->appendTerminal('Starting worker (stop-when-empty, max 5 min)…');

            $result = ProxyQueueRunner::drain(300);

            if ($result['output'] !== '') {
                $this->appendTerminal($result['output']);
            }

            $this->appendTerminal("Worker finished. Remaining jobs: {$result['pending_after']}");

            if ($this->selectedServer) {
                $server = ProxyServer::query()->find($this->selectedServer);
                if ($server) {
                    $this->appendTerminal("Server setup status: {$server->setup_status}");
                    if ($server->setup_log) {
                        $this->appendTerminal('--- setup log (tail) ---');
                        $this->appendTerminal($this->tailText($server->setup_log, 40));
                    }
                }
            }

            $this->loadServers();
            $this->dispatch(
                'sweetAlert',
                title: 'Queue worker finished',
                message: "Remaining jobs: {$result['pending_after']}",
                type: $result['pending_after'] === 0 ? 'success' : 'info'
            );
        } catch (\Throwable $e) {
            $this->appendTerminal('✗ '.$e->getMessage());
            $this->dispatch('sweetAlert', title: 'Queue worker error', message: $e->getMessage(), type: 'error');
        } finally {
            $this->isTerminalBusy = false;
            $this->dispatch('scroll-terminal');
        }
    }

    private function requireSelectedServer(): ProxyServer
    {
        if (! $this->selectedServer) {
            throw new \RuntimeException('No server selected. Close and reopen the terminal.');
        }

        $server = ProxyServer::query()->find($this->selectedServer);

        if (! $server) {
            throw new \RuntimeException('Selected server was deleted.');
        }

        if (! $server->sshPassword()) {
            if ($server->sshPasswordDecryptFailed()) {
                throw new \RuntimeException('SSH password was encrypted on another machine. Copy the same APP_KEY into .env or edit this server and save the SSH password again.');
            }

            throw new \RuntimeException('SSH password is not set. Edit the server first.');
        }

        return $server;
    }

    private function openSshConnection(ProxyServer $server): SSH2
    {
        $ssh = new SSH2($server->ip, (int) $server->ssh_port);
        $ssh->setTimeout(30);

        if (! $ssh->login($server->ssh_user, $server->sshPassword())) {
            throw new \RuntimeException('SSH authentication failed. Check user/password/port.');
        }

        return $ssh;
    }

    private function appendTerminal(string $line): void
    {
        $this->terminalOutput .= rtrim($line)."\n";
    }

    private function tailText(string $text, int $lines): string
    {
        $parts = preg_split("/\r\n|\n|\r/", $text) ?: [];

        return implode("\n", array_slice($parts, -$lines));
    }

    public function render()
    {
        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.proxy-servers')
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}
