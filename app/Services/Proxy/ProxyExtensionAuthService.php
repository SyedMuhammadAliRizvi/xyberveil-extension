<?php

namespace App\Services\Proxy;

use App\Jobs\Proxy\ProvisionProxyUserJob;
use App\Models\ProxyRefreshToken;
use App\Models\User;
use App\Support\ProxyApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProxyExtensionAuthService
{
    public function __construct(
        private ProxyTokenService $tokenService,
        private ProxyExtensionDeviceService $deviceService,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function register(array $payload): array
    {
        $email = mb_strtolower(trim((string) $payload['email']));

        $existing = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        if ($existing?->hasProxyAccess()) {
            $this->abort('Email already registered', 400);
        }

        if ($existing) {
            $existing->update([
                'name' => trim((string) $payload['display_name']) ?: $existing->name,
                'password' => Hash::make((string) $payload['password']),
                'linux_username' => $existing->linux_username ?: User::generateLinuxUsername($existing->id),
                'email_verified_at' => null,
            ]);
            $user = $existing->fresh();
        } else {
            $user = User::query()->create([
                'name' => trim((string) $payload['display_name']),
                'email' => $email,
                'linux_username' => 'pxtmp'.Str::random(8),
                'password' => Hash::make((string) $payload['password']),
                'role' => 'user',
                'email_verified_at' => null,
            ]);

            $user->update([
                'linux_username' => User::generateLinuxUsername($user->id),
            ]);
        }

        return [
            'message' => 'Registration received — awaiting admin approval.',
            'user_id' => $user->id,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function login(array $payload): array
    {
        $email = mb_strtolower(trim((string) $payload['email']));
        $user = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $user || ! $user->hasProxyAccess() || ! Hash::check((string) $payload['password'], $user->password)) {
            $this->abort('Invalid email or password', 401);
        }

        $this->assertAccountActive($user);

        return $this->issueTokens($user, $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function refresh(array $payload): array
    {
        $refreshToken = ProxyRefreshToken::query()
            ->where('token', (string) $payload['refresh_token'])
            ->first();

        if (! $refreshToken || $refreshToken->expires_at->isPast() || $refreshToken->isRevoked()) {
            $this->abort('Refresh token expired or not found', 401);
        }

        $user = $refreshToken->user;

        if (! $user || ! $user->isProxyActive()) {
            $this->abort('User not active', 401);
        }

        $deviceKey = trim((string) ($payload['device_key'] ?? $refreshToken->device_key ?? ''));

        try {
            $this->deviceService->assertDeviceAuthorized($user->id, $deviceKey ?: null, true);
        } catch (HttpResponseException $exception) {
            $refreshToken->delete();
            throw $exception;
        }

        return [
            'access_token' => $this->tokenService->createAccessToken($user->id, $user->role),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function logout(array $payload): array
    {
        $refreshToken = ProxyRefreshToken::query()
            ->where('token', (string) $payload['refresh_token'])
            ->first();

        $user = $refreshToken?->user;

        // Kept as a log, not deleted — flagged so it can no longer be used
        // to mint access tokens (see the revoked_at check in refresh()).
        $refreshToken?->update(['revoked_at' => now()]);

        if ($user) {
            $this->logoutXyberveilSession($user);
        }

        return ['message' => 'Logged out'];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function googleLogin(array $payload): array
    {
        try {
            $response = Http::withToken((string) $payload['access_token'])
                ->timeout(8)
                ->get('https://www.googleapis.com/oauth2/v3/userinfo');
            $response->throw();
            $info = $response->json();
        } catch (\Throwable) {
            $this->abort('Invalid Google token', 401);
        }

        $googleEmail = mb_strtolower(trim((string) ($info['email'] ?? '')));
        $googleName = $info['name'] ?? $info['given_name'] ?? strtok($googleEmail, '@');
        $googleSub = isset($info['sub']) ? (string) $info['sub'] : null;

        if ($googleEmail === '') {
            $this->abort('Could not retrieve email from Google', 400);
        }

        $user = User::query()->whereRaw('LOWER(email) = ?', [$googleEmail])->first();
        $shouldProvision = false;

        if (! $user) {
            $user = User::query()->create([
                'email' => $googleEmail,
                'name' => (string) $googleName,
                'linux_username' => 'pxtmp'.Str::random(8),
                'password' => Hash::make(Str::random(32)),
                'role' => 'user',
                'email_verified_at' => now(),
                'google_id' => $googleSub,
            ]);

            $user->update([
                'linux_username' => User::generateLinuxUsername($user->id),
            ]);

            $shouldProvision = true;
        } else {
            $shouldProvision = ! $user->isProxyActive();
        }

        if ($user->isBanned()) {
            $this->abort('Account suspended — contact support', 403);
        }

        $user = $this->ensureGoogleUserIsActive($user->fresh(), (string) $googleName, $googleSub);

        if ($shouldProvision) {
            ProvisionProxyUserJob::dispatch($user->id);
        }

        return $this->issueTokens($user, $payload);
    }

    private function ensureGoogleUserIsActive(User $user, string $displayName, ?string $googleSub): User
    {
        if (! $user->linux_username) {
            $user->update([
                'linux_username' => User::generateLinuxUsername($user->id),
            ]);
            $user->refresh();
        }

        $profileUpdates = [];

        if ($googleSub) {
            $profileUpdates['google_id'] = $googleSub;
        }

        if (! trim($user->name)) {
            $profileUpdates['name'] = $displayName;
        }

        if ($profileUpdates !== []) {
            $user->update($profileUpdates);
            $user->refresh();
        }

        $this->activateProxyAccess($user);

        return $user->fresh();
    }

    /**
     * Verifies the OTP directly against XyberVeil's v3 API, server-side —
     * never trusts the extension's claim that verification already
     * happened (mirrors how googleLogin() independently verifies the
     * Google access_token instead of trusting a client-supplied email).
     *
     * @param  array<string, mixed>  $payload
     */
    public function otpLogin(array $payload): array
    {
        $email = mb_strtolower(trim((string) ($payload['email'] ?? '')));
        $code = trim((string) ($payload['code'] ?? ''));

        if ($email === '' || $code === '') {
            $this->abort('Email and code are required', 422);
        }

        $xyberveilSession = $this->verifyXyberveilOtp($email, $code, $payload);

        $user = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();
        $shouldProvision = false;

        if (! $user) {
            $user = User::query()->create([
                'email' => $email,
                'name' => strtok($email, '@') ?: 'Member',
                'linux_username' => 'pxtmp'.Str::random(8),
                'password' => Hash::make(Str::random(32)),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);

            $user->update([
                'linux_username' => User::generateLinuxUsername($user->id),
            ]);

            $shouldProvision = true;
        } else {
            $shouldProvision = ! $user->isProxyActive();
        }

        if ($user->isBanned()) {
            $this->abort('Account suspended — contact support', 403);
        }

        $user = $this->ensureProxyUserIsActive($user->fresh());
        $this->rememberXyberveilSession($user, $xyberveilSession);

        if ($shouldProvision) {
            ProvisionProxyUserJob::dispatch($user->id);
        }

        return $this->issueTokens($user, $payload, [
            'xyberveil_response' => $xyberveilSession['raw'] ?? null,
        ]);
    }

    /**
     * Confirms the guest UUID directly against XyberVeil's v3 API,
     * server-side (same reasoning as otpLogin() above). Stable per-UUID
     * guest account.
     *
     * @param  array<string, mixed>  $payload
     */
    public function guestLogin(array $payload): array
    {
        $uuid = trim((string) ($payload['uuid'] ?? ''));

        if ($uuid === '') {
            $this->abort('uuid is required', 422);
        }

        $xyberveilSession = $this->verifyXyberveilGuest($uuid);

        $guestEmail = sprintf('guest-%s@guest.xyberveil.local', mb_strtolower($uuid));

        $user = User::query()->whereRaw('LOWER(email) = ?', [$guestEmail])->first();
        $shouldProvision = false;

        if (! $user) {
            $user = User::query()->create([
                'email' => $guestEmail,
                'name' => 'Guest',
                'linux_username' => 'pxtmp'.Str::random(8),
                'password' => Hash::make(Str::random(32)),
                'role' => 'guest',
                'email_verified_at' => now(),
            ]);

            $user->update([
                'linux_username' => User::generateLinuxUsername($user->id),
            ]);

            $shouldProvision = true;
        } else {
            $shouldProvision = ! $user->isProxyActive();
        }

        if ($user->isBanned()) {
            $this->abort('Account suspended — contact support', 403);
        }

        $user = $this->ensureProxyUserIsActive($user->fresh());
        $this->rememberXyberveilSession($user, $xyberveilSession);

        if ($shouldProvision) {
            ProvisionProxyUserJob::dispatch($user->id);
        }

        return $this->issueTokens($user, $payload);
    }

    /**
     * @param  array<string, mixed>  $devicePayload
     * @return array{token_id: ?int, api_token: ?string, raw: array<string, mixed>}
     */
    private function verifyXyberveilOtp(string $email, string $code, array $devicePayload): array
    {
        $deviceDetails = [
            'type' => 'Extension',
            'name' => trim(sprintf(
                '%s %s',
                (string) ($devicePayload['os_name'] ?? ''),
                (string) ($devicePayload['os_version'] ?? ''),
            )) ?: ($devicePayload['browser_name'] ?? null),
            'id' => $devicePayload['device_key'] ?? null,
            'version' => $devicePayload['extension_version'] ?? null,
        ];

        try {
            $response = Http::timeout(10)->post('https://api.xyberveil.com/v3/auth/verify-otp', [
                'email' => $email,
                'code' => $code,
                'attachSubscription' => true,
                'deviceDetails' => $deviceDetails,
            ]);
        } catch (\Throwable) {
            $this->abort('Could not reach the verification service. Try again.', 502);
        }

        $body = $response->json() ?? [];

        if (! $response->successful() || ($body['state'] ?? null) === false) {
            $this->abort((string) ($body['message'] ?? 'Invalid or expired code.'), 401);
        }

        return array_merge(
            $this->extractXyberveilSession((string) ($body['response']['ApiToken'] ?? '')),
            ['raw' => $body],
        );
    }

    /**
     * @return array{token_id: ?int, api_token: ?string}
     */
    private function verifyXyberveilGuest(string $uuid): array
    {
        try {
            $response = Http::asMultipart()->timeout(10)->post('https://api.xyberveil.com/v3/continue-as-guest', [
                ['name' => 'UUID', 'contents' => $uuid],
                ['name' => 'device_id', 'contents' => $uuid],
                ['name' => 'groud_id', 'contents' => '1'],
            ]);
        } catch (\Throwable) {
            $this->abort('Could not reach the guest session service. Try again.', 502);
        }

        $body = $response->json() ?? [];

        if (! $response->successful() || ($body['state'] ?? null) === false) {
            $this->abort((string) ($body['message'] ?? 'Could not start a guest session.'), 401);
        }

        return $this->extractXyberveilSession((string) ($body['response']['token'] ?? ''));
    }

    /**
     * Sanctum tokens are formatted "{id}|{plaintext}" — the id prefix is
     * kept for reference/debugging (e.g. matching activeSessions entries),
     * even though /v3/signout itself only needs the bearer token.
     *
     * @return array{token_id: ?int, api_token: ?string}
     */
    private function extractXyberveilSession(string $token): array
    {
        if ($token === '') {
            return ['token_id' => null, 'api_token' => null];
        }

        $id = strtok($token, '|');

        return [
            'token_id' => is_numeric($id) ? (int) $id : null,
            'api_token' => $token,
        ];
    }

    /**
     * @param  array{token_id: ?int, api_token: ?string}  $session
     */
    private function rememberXyberveilSession(User $user, array $session): void
    {
        if (! $session['token_id'] || ! $session['api_token']) {
            return;
        }

        $user->forceFill([
            'xyberveil_token_id' => $session['token_id'],
            'xyberveil_api_token' => $session['api_token'],
        ])->save();
    }

    /**
     * Ends the user's XyberVeil-side session (if any) so it stops counting
     * against their device/session limit. Best-effort: local logout must
     * still succeed even if XyberVeil is unreachable or already expired it.
     */
    private function logoutXyberveilSession(User $user): void
    {
        if (! $user->xyberveil_token_id || ! $user->xyberveil_api_token) {
            Log::info('xyberveil.logout.skipped', ['user_id' => $user->id, 'reason' => 'no stored session']);

            return;
        }

        // Both calls are made, in order — logout first (ends that specific
        // tokenId), then signout (ends the session for the bearer token
        // used). Each is independent/best-effort: either one failing must
        // not block the other or the local logout.
        $this->callXyberveilEndSession(
            $user,
            "https://api.xyberveil.com/v3/logout/{$user->xyberveil_token_id}",
            'logout',
        );
        $this->callXyberveilEndSession(
            $user,
            'https://api.xyberveil.com/v3/signout',
            'signout',
        );

        $user->forceFill([
            'xyberveil_token_id' => null,
            'xyberveil_api_token' => null,
        ])->save();
    }

    private function callXyberveilEndSession(User $user, string $url, string $label): void
    {
        try {
            $response = Http::withToken($user->xyberveil_api_token)
                ->timeout(8)
                ->post($url);

            $context = [
                'user_id' => $user->id,
                'token_id' => $user->xyberveil_token_id,
                'status' => $response->status(),
                'body' => $response->body(),
            ];

            $response->successful()
                ? Log::info("xyberveil.{$label}.ok", $context)
                : Log::warning("xyberveil.{$label}.failed", $context);
        } catch (\Throwable $e) {
            Log::warning("xyberveil.{$label}.exception", [
                'user_id' => $user->id,
                'token_id' => $user->xyberveil_token_id,
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function ensureProxyUserIsActive(User $user): User
    {
        if (! $user->linux_username) {
            $user->update([
                'linux_username' => User::generateLinuxUsername($user->id),
            ]);
            $user->refresh();
        }

        $this->activateProxyAccess($user);

        return $user->fresh();
    }

    /**
     * @param  array<string, mixed>  $devicePayload
     */
    /**
     * @param  array<string, mixed>  $devicePayload
     * @param  array<string, mixed>  $refreshTokenExtra  Extra columns to store on the new proxy_refresh_tokens row.
     */
    private function issueTokens(User $user, array $devicePayload, array $refreshTokenExtra = []): array
    {
        $refreshToken = $this->tokenService->createRefreshToken();

        ProxyRefreshToken::query()->create(array_merge([
            'user_id' => $user->id,
            'token' => $refreshToken,
            'device_key' => trim((string) ($devicePayload['device_key'] ?? '')) ?: null,
            'expires_at' => now()->addDays(config('proxy.refresh_token_ttl_days', 30)),
        ], $refreshTokenExtra));

        $this->deviceService->upsertExtensionDevice(
            $user,
            $devicePayload['device_key'] ?? null,
            $devicePayload['browser_name'] ?? null,
            (string) ($devicePayload['browser_version'] ?? ''),
            (string) ($devicePayload['os_name'] ?? ''),
            (string) ($devicePayload['os_version'] ?? ''),
            (string) ($devicePayload['extension_version'] ?? ''),
            $devicePayload['ip_address'] ?? null,
            $devicePayload['timezone'] ?? null,
        );

        $this->deviceService->assertDeviceAuthorized(
            $user->id,
            $devicePayload['device_key'] ?? null,
            true,
        );

        return [
            'access_token' => $this->tokenService->createAccessToken($user->id, $user->role),
            'refresh_token' => $refreshToken,
            'user' => array_merge([
                'id' => $user->id,
                'email' => $user->email,
                'display_name' => $user->display_name,
                'role' => $user->role,
            ], $this->accountProfile($user)),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function accountProfile(User $user): array
    {
        $daysLeft = null;
        if ($user->isOnTrial()) {
            $daysLeft = $user->trialDaysRemaining();
        } elseif ($user->hasTrialExpired()) {
            $daysLeft = 0;
        }

        $subscription = $user->activeSubscription()->with('plan')->first();
        $plan = $subscription?->plan;

        return [
            'member_id' => sprintf('QX%06d', $user->id),
            'created_at' => $user->created_at?->toIso8601String(),
            'trial_started_at' => $user->trial_started_at?->toIso8601String(),
            'trial_ends_at' => $user->trial_ends_at?->toIso8601String(),
            'trial_days_left' => $daysLeft,
            'trial_status' => $user->getTrialStatus(),
            'is_subscribed' => $subscription !== null,
            'can_connect' => $user->canConnectProxy(),
            'plan' => $plan ? [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'status' => $subscription->status,
                'ends_at' => $subscription->ends_at?->toIso8601String(),
            ] : null,
            'subscription_ends_at' => $subscription?->ends_at?->toIso8601String(),
        ];
    }

    public function activateProxyAccess(User $user): void
    {
        if (! $user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        if (! $user->hasUsedTrial()) {
            $user->startTrial((int) config('proxy.trial_days', 30));
        }
    }

    private function assertAccountActive(User $user): void
    {
        if ($user->isProxyPending()) {
            $this->abort('Account pending admin approval', 403);
        }

        if ($user->isBanned()) {
            $this->abort('Account suspended — contact support', 403);
        }
    }

    private function abort(string $message, int $status): never
    {
        throw new HttpResponseException(ProxyApiResponse::detail($message, $status));
    }
}
