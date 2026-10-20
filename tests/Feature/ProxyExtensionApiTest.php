<?php

namespace Tests\Feature;

use App\Models\ProxyServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProxyExtensionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_proxy_user_can_register_and_login(): void
    {
        $register = $this->postJson('/api/proxy/auth/register', [
            'email' => 'proxy@example.com',
            'display_name' => 'Proxy User',
            'password' => 'Password1!',
        ]);

        $register->assertCreated()
            ->assertJsonPath('message', 'Registration received — awaiting admin approval.');

        $user = User::query()->where('email', 'proxy@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
        $this->assertSame('pending', $user->proxyStatus());
        $this->assertSame('px000001', $user->linux_username);

        $user->email_verified_at = now();
        $user->startTrial(30);
        $user->save();

        $login = $this->postJson('/api/proxy/auth/login', [
            'email' => 'proxy@example.com',
            'password' => 'Password1!',
            'device_key' => 'device-key-123456',
            'browser_name' => 'Chrome',
            'ip_address' => '203.0.113.10',
            'timezone' => 'Asia/Karachi',
        ]);

        $login->assertOk()
            ->assertJsonStructure(['access_token', 'refresh_token', 'user' => ['id', 'email', 'member_id', 'trial_ends_at']]);

        $this->assertDatabaseHas('proxy_extension_devices', [
            'user_id' => $user->id,
            'device_key' => 'device-key-123456',
            'browser_name' => 'Chrome',
            'ip_address' => '203.0.113.10',
            'timezone' => 'Asia/Karachi',
        ]);

        $token = $login->json('access_token');

        $this->getJson('/api/proxy/me', [
            'Authorization' => 'Bearer '.$token,
        ])->assertOk()
            ->assertJsonPath('email', 'proxy@example.com')
            ->assertJsonStructure(['trial_started_at', 'trial_ends_at']);
    }

    public function test_active_user_can_list_proxy_servers(): void
    {
        User::query()->create([
            'name' => 'Active User',
            'email' => 'active@example.com',
            'linux_username' => 'px000001',
            'password' => Hash::make('Password1!'),
            'role' => 'user',
            'email_verified_at' => now(),
            'trial_started_at' => now(),
            'trial_ends_at' => now()->addDays(30),
            'trial_used' => true,
        ]);

        ProxyServer::query()->create([
            'name' => 'US East',
            'country' => 'United States',
            'flag' => '🇺🇸',
            'ip' => '10.0.0.1',
            'is_active' => true,
            'setup_status' => 'ready',
        ]);

        $login = $this->postJson('/api/proxy/auth/login', [
            'email' => 'active@example.com',
            'password' => 'Password1!',
        ]);

        $token = $login->json('access_token');

        $this->getJson('/api/proxy/servers', [
            'Authorization' => 'Bearer '.$token,
        ])->assertOk()
            ->assertJsonFragment(['name' => 'US East']);
    }

    public function test_google_login_auto_approves_pending_user(): void
    {
        Http::fake([
            'https://www.googleapis.com/oauth2/v3/userinfo' => Http::response([
                'email' => 'google@example.com',
                'name' => 'Google User',
                'sub' => 'google-subject-123',
            ]),
        ]);

        User::query()->create([
            'name' => 'Pending User',
            'email' => 'google@example.com',
            'linux_username' => 'px000003',
            'password' => Hash::make('Password1!'),
            'role' => 'user',
            'email_verified_at' => null,
        ]);

        $response = $this->postJson('/api/proxy/auth/google', [
            'access_token' => 'fake-google-token',
            'device_key' => 'device-key-123456',
            'browser_name' => 'Chrome',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'refresh_token', 'user']);

        $user = User::query()->where('email', 'google@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('active', $user->proxyStatus());
        $this->assertTrue($user->canConnectProxy());
    }

    public function test_expired_trial_user_cannot_start_proxy_session(): void
    {
        $user = User::query()->create([
            'name' => 'Expired Trial',
            'email' => 'expired@example.com',
            'linux_username' => 'px000002',
            'password' => Hash::make('Password1!'),
            'role' => 'user',
            'email_verified_at' => now(),
            'trial_started_at' => now()->subDays(40),
            'trial_ends_at' => now()->subDays(10),
            'trial_used' => true,
        ]);

        ProxyServer::query()->create([
            'name' => 'US East',
            'country' => 'United States',
            'flag' => '🇺🇸',
            'ip' => '10.0.0.1',
            'is_active' => true,
            'setup_status' => 'ready',
        ]);

        $login = $this->postJson('/api/proxy/auth/login', [
            'email' => 'expired@example.com',
            'password' => 'Password1!',
            'device_key' => 'device-key-123456',
            'browser_name' => 'Chrome',
        ]);

        $token = $login->json('access_token');

        $this->getJson('/api/proxy/me', [
            'Authorization' => 'Bearer '.$token,
        ])->assertOk()
            ->assertJsonPath('can_connect', false)
            ->assertJsonPath('trial_status', 'expired');

        $this->postJson('/api/proxy/sessions/start', [
            'server_id' => 1,
        ], [
            'Authorization' => 'Bearer '.$token,
        ])->assertForbidden()
            ->assertJsonPath('detail', 'Trial expired — subscribe to continue.');
    }

    public function test_proxy_me_includes_active_plan_for_subscribed_user(): void
    {
        $user = User::query()->create([
            'name' => 'Plan User',
            'email' => 'planuser@example.com',
            'linux_username' => 'px000004',
            'password' => Hash::make('Password1!'),
            'role' => 'user',
            'email_verified_at' => now(),
            'trial_started_at' => now()->subDays(5),
            'trial_ends_at' => now()->addDays(25),
            'trial_used' => true,
        ]);

        $plan = \App\Models\Plan::query()->create([
            'name' => 'Pro Monthly Plan',
            'slug' => 'pro-monthly-plan',
            'description' => 'Monthly access',
            'type' => 'regular',
            'is_active' => true,
            'original_price' => 10.00,
            'discount_price' => 8.50,
            'invoice_period' => 1,
            'invoice_interval' => 'month',
        ]);

        $user->subscribeTo($plan);

        $login = $this->postJson('/api/proxy/auth/login', [
            'email' => 'planuser@example.com',
            'password' => 'Password1!',
            'device_key' => 'device-key-123456',
            'browser_name' => 'Chrome',
        ]);

        $token = $login->json('access_token');

        $this->getJson('/api/proxy/me', [
            'Authorization' => 'Bearer '.$token,
        ])->assertOk()
            ->assertJsonPath('is_subscribed', true)
            ->assertJsonPath('can_connect', true)
            ->assertJsonPath('plan.name', 'Pro Monthly Plan')
            ->assertJsonStructure(['plan' => ['id', 'name', 'slug', 'status', 'ends_at'], 'subscription_ends_at']);
    }

    public function test_google_login_registers_new_mac_device(): void
    {
        Http::fake([
            'https://www.googleapis.com/oauth2/v3/userinfo' => Http::response([
                'email' => 'macboss@example.com',
                'name' => 'Mac Boss',
                'sub' => 'google-subject-mac',
            ]),
        ]);

        $user = User::query()->create([
            'name' => 'Mac Boss',
            'email' => 'macboss@example.com',
            'linux_username' => 'px000005',
            'password' => Hash::make('Password1!'),
            'role' => 'user',
            'email_verified_at' => now(),
            'trial_started_at' => now(),
            'trial_ends_at' => now()->addDays(30),
            'trial_used' => true,
        ]);

        $response = $this->postJson('/api/proxy/auth/google', [
            'access_token' => 'fake-google-token',
            'device_key' => 'mac-device-key-001',
            'browser_name' => 'Google Chrome',
            'browser_version' => '120.0.0.0',
            'os_name' => 'macOS',
            'os_version' => '14.2',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'refresh_token']);

        $this->assertDatabaseHas('proxy_extension_devices', [
            'user_id' => $user->id,
            'device_key' => 'mac-device-key-001',
            'browser_name' => 'Google Chrome',
            'os_name' => 'macOS',
            'is_allowed' => true,
        ]);
    }
}
