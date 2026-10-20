<?php

namespace Tests\Feature;

use App\Livewire\Admin\ProxyManager;
use App\Models\ProxyServer;
use App\Models\ProxySession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProxyServerManageTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_connected_users_loads_active_database_sessions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $proxyUser = User::factory()->create([
            'linux_username' => 'alice',
            'role' => 'user',
        ]);
        $server = ProxyServer::query()->create([
            'name' => 'Test Proxy',
            'country' => 'Germany',
            'flag' => '🇩🇪',
            'ip' => '203.0.113.10',
            'proxy_port' => 10080,
            'socks_port' => 10081,
            'agent_port' => 9191,
            'agent_key' => 'test-agent-key',
            'ssh_user' => 'root',
            'ssh_port' => 22,
            'setup_status' => 'ready',
        ]);

        ProxySession::query()->create([
            'user_id' => $proxyUser->id,
            'proxy_server_id' => $server->id,
            'started_at' => now()->subHour(),
            'client_ip' => '10.0.0.5',
        ]);

        Livewire::actingAs($admin)
            ->test(ProxyManager::class, ['proxyServer' => $server])
            ->call('fetchConnectedUsers')
            ->assertSet('totalConnected', 1)
            ->assertSet('connectedUsers.0.name', 'alice')
            ->assertSet('connectedUsers.0.ip_address', '10.0.0.5');
    }

    public function test_admin_can_view_proxy_server_manage_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $server = ProxyServer::query()->create([
            'name' => 'Test Proxy',
            'country' => 'Germany',
            'flag' => '🇩🇪',
            'ip' => '203.0.113.10',
            'proxy_port' => 10080,
            'socks_port' => 10081,
            'agent_port' => 9191,
            'agent_key' => 'test-agent-key',
            'ssh_user' => 'root',
            'ssh_port' => 22,
            'setup_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('proxy-servers.manage', $server));

        $response->assertOk();
        $response->assertSee('Manage Proxy Server');
        $response->assertSee('Test Proxy');
    }

    public function test_guest_cannot_view_proxy_server_manage_page(): void
    {
        $server = ProxyServer::query()->create([
            'name' => 'Hidden Proxy',
            'country' => 'US',
            'flag' => '🇺🇸',
            'ip' => '203.0.113.11',
            'proxy_port' => 10080,
            'socks_port' => 10081,
            'agent_port' => 9191,
            'agent_key' => 'test-agent-key',
            'ssh_user' => 'root',
            'ssh_port' => 22,
            'setup_status' => 'pending',
        ]);

        $this->get(route('proxy-servers.manage', $server))->assertRedirect();
    }
}
