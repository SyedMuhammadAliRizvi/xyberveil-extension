<?php

namespace App\Jobs\Proxy;

use App\Models\ProxyServer;
use App\Services\Proxy\ProxyProvisioningService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use phpseclib3\Net\SFTP;
use phpseclib3\Net\SSH2;

class SetupProxyServerJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 600;

    public function __construct(public int $serverId) {}

    public function handle(): void
    {
        $server = ProxyServer::query()->find($this->serverId);

        if (! $server) {
            return;
        }

        $lines = ['Connecting via SSH…'];
        $server->update(['setup_log' => implode("\n", $lines)]);

        $monitorIp = (string) config('services.proxy.allowed_agent_ip', '127.0.0.1');
        $agentPath = storage_path('app/proxy/proxy-agent.py');

        if (! is_file($agentPath)) {
            $server->update([
                'setup_status' => 'failed',
                'setup_log' => "Missing agent file: {$agentPath}",
            ]);

            return;
        }

        $script = str_replace(
            ['__PROXY_PORT__', '__SOCKS_PORT__', '__AGENT_PORT__', '__AGENT_KEY__', '__MONITOR_IP__'],
            [
                (string) $server->proxy_port,
                (string) $server->socks_port,
                (string) $server->agent_port,
                $server->agent_key ?? '',
                $monitorIp,
            ],
            $this->installScript()
        );

        try {
            if ($server->sshPasswordDecryptFailed()) {
                throw new \RuntimeException('SSH password was encrypted on another machine. Copy the same APP_KEY into .env or edit this server and save the SSH password again.');
            }

            if (! $server->sshPassword()) {
                throw new \RuntimeException('SSH password is not set. Edit the server first.');
            }

            $ssh = new SSH2($server->ip, $server->ssh_port);
            $ssh->setTimeout(300);

            if (! $ssh->login($server->ssh_user, $server->sshPassword())) {
                throw new \RuntimeException('SSH authentication failed');
            }

            $lines[] = "✓ SSH connected to {$server->ip}";

            $sftp = new SFTP($server->ip, $server->ssh_port);
            $sftp->setTimeout(120);

            if (! $sftp->login($server->ssh_user, $server->sshPassword())) {
                throw new \RuntimeException('SFTP authentication failed');
            }

            $sftp->mkdir('/opt/proxy-agent', -1, true);
            $sftp->mkdir('/etc/proxy-agent', -1, true);

            if (! $sftp->put('/opt/proxy-agent/main.py', $agentPath, SFTP::SOURCE_LOCAL_FILE)) {
                throw new \RuntimeException('Failed to upload proxy-agent.py via SFTP');
            }

            $lines[] = '✓ Uploaded proxy-agent.py via SFTP';
            $server->update(['setup_log' => implode("\n", $lines)]);

            $ssh->exec('cat > /tmp/proxy_setup.sh << \'ENDSCRIPT\''."\n".$script."\nENDSCRIPT");
            $output = $ssh->exec('chmod +x /tmp/proxy_setup.sh && bash /tmp/proxy_setup.sh 2>&1');
            $lines[] = $output;
            $exitCode = $ssh->getExitStatus();

            if ($exitCode === 0) {
                $server->update([
                    'setup_status' => 'ready',
                    'setup_log' => implode("\n", $lines),
                ]);
                app(ProxyProvisioningService::class)->provisionAllUsersOnServer($server->id);

                return;
            }

            $server->update([
                'setup_status' => 'failed',
                'setup_log' => implode("\n", $lines),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Proxy server SSH setup failed', [
                'server_id' => $server->id,
                'error' => $exception->getMessage(),
            ]);

            $lines[] = '✗ '.$exception->getMessage();
            $server->update([
                'setup_status' => 'failed',
                'setup_log' => implode("\n", $lines),
            ]);
        }
    }

    private function installScript(): string
    {
        return <<<'BASH'
#!/bin/bash
set -e
export DEBIAN_FRONTEND=noninteractive
echo "==> Updating packages & installing Python"
apt-get update -qq
apt-get install -y -qq python3 python3-pip python3-venv

if [ ! -f /opt/proxy-agent/main.py ]; then
  echo "ERROR: /opt/proxy-agent/main.py missing (upload failed)"
  exit 1
fi

echo "==> Installing Python dependencies"
python3 -m venv /opt/proxy-agent/venv
/opt/proxy-agent/venv/bin/pip install -q fastapi uvicorn

echo "==> Creating systemd service"
cat > /etc/systemd/system/proxy-agent.service << ENDSVC
[Unit]
Description=SecureProxy Agent (HTTP CONNECT + SOCKS5 + Management API)
After=network.target

[Service]
Environment="AGENT_KEY=__AGENT_KEY__"
Environment="USERS_FILE=/etc/proxy-agent/users.cfg"
Environment="PROXY_PORT=__PROXY_PORT__"
Environment="SOCKS_PORT=__SOCKS_PORT__"
Environment="AGENT_PORT=__AGENT_PORT__"
ExecStart=/opt/proxy-agent/venv/bin/uvicorn main:app --host 0.0.0.0 --port __AGENT_PORT__
WorkingDirectory=/opt/proxy-agent
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
ENDSVC

systemctl daemon-reload
systemctl enable proxy-agent
systemctl restart proxy-agent

echo "==> Configuring firewall"
ufw allow __PROXY_PORT__/tcp comment 'SecureProxy HTTP' 2>/dev/null || true
ufw allow __SOCKS_PORT__/tcp comment 'SecureProxy SOCKS5' 2>/dev/null || true
iptables -I INPUT -p tcp --dport __AGENT_PORT__ -j DROP 2>/dev/null || true
iptables -I INPUT -p tcp --dport __AGENT_PORT__ -s __MONITOR_IP__ -j ACCEPT 2>/dev/null || true

echo "==> Done"
sleep 3
systemctl status proxy-agent --no-pager | head -15
BASH;
    }
}
