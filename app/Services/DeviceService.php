<?php

namespace App\Services;

use App\Jobs\ResolveDeviceLocation;
use App\Models\User;
use App\Models\UserDevice;
use Browser;
use Illuminate\Http\Request;
use Laravel\Sanctum\NewAccessToken;

class DeviceService
{
    /**
     * Register or update a device record after a successful login.
     * Location is resolved asynchronously via a queued job.
     *
     * @param  array{
     *   name?: string,
     *   model?: string,
     *   platform?: string,
     *   os?: string,
     *   device_type?: string,
     *   app_version?: string,
     *   device_fingerprint?: string,
     * } $deviceData
     */

    /**
     * Returns true when logging in would create a new device entry and the user
     * is already at their device limit. A known fingerprint that maps to an
     * existing active device is always treated as a re-login, not a new slot.
     * Web sessions (platform='web') are exempt — they have their own cap managed
     * via pruneWebSessionsIfNeeded().
     */
    public function isAtDeviceLimit(User $user, ?string $fingerprint, ?string $platform = null): bool
    {
        if ($platform === 'web') {
            return false;
        }

        if ($fingerprint) {
            $existingActive = $user->devices()
                ->where('device_fingerprint', $fingerprint)
                ->whereNull('revoked_at')
                ->where('is_blocked', false)
                ->exists();

            if ($existingActive) {
                return false;
            }
        }

        return $user->activeDevicesCount() >= $user->deviceLimit();
    }

    /**
     * Check if the device identified by fingerprint is blocked for this user.
     * Call this before creating a Sanctum token.
     */
    public function isDeviceBlocked(User $user, ?string $fingerprint): bool
    {
        if (! $fingerprint) {
            return false;
        }

        return $user->devices()
            ->where('device_fingerprint', $fingerprint)
            ->where('is_blocked', true)
            ->exists();
    }

    public function registerDevice(User $user, NewAccessToken $newToken, Request $request, array $deviceData = []): UserDevice
    {
        $fingerprint = $deviceData['device_fingerprint'] ?? null;
        $tokenId = $newToken->accessToken->id;
        $platform = $deviceData['platform'] ?? null;

        // Prefer an explicit IP from the request body (client-provided), fall back to Laravel's detected IP
        $ip = $request->input('ip_address') ?: $request->ip();

        // If a fingerprint is provided, reuse the existing device record for this physical device
        $device = null;
        if ($fingerprint) {
            $device = $user->devices()->where('device_fingerprint', $fingerprint)->first();
        }

        // Always-safe attributes — never touch block/revoke status on an existing record
        $mutableAttributes = [
            'token_id' => $tokenId,
            'name' => $deviceData['name'] ?? null,
            'model' => $deviceData['model'] ?? null,
            'platform' => $platform,
            'os' => $deviceData['os'] ?? null,
            'device_type' => $deviceData['device_type'] ?? null,
            'app_version' => $deviceData['app_version'] ?? null,
            'browser' => null,
            'device_fingerprint' => $fingerprint,
            'ip_address' => $ip,
            'last_seen_at' => now(),
        ];

        // For web sessions, auto-detect browser, OS, and device type from the User-Agent header
        if ($platform === 'web') {
            $ua = $request->header('User-Agent', '');

            if ($ua) {
                $parsed = Browser::parse($ua);

                $family = $parsed->browserFamily();
                $major = $parsed->browserVersionMajor();
                $browserString = trim($family.($major ? ' '.$major : ''));

                $mutableAttributes['browser'] = $browserString ?: null;

                $platformName = $parsed->platformName() ?: null;

                if (empty($mutableAttributes['os'])) {
                    $mutableAttributes['os'] = $platformName;
                }

                if (empty($mutableAttributes['device_type'])) {
                    $mutableAttributes['device_type'] = strtolower($parsed->deviceType()) ?: null;
                }

                // Auto-generate a friendly name when the client doesn't provide one
                if (empty($mutableAttributes['name'])) {
                    $nameParts = array_filter([$browserString, $platformName]);
                    $mutableAttributes['name'] = $nameParts ? implode(' on ', $nameParts) : 'Web Session';
                }
            }

            // Prune oldest web session when the cap is reached (only for genuinely new sessions)
            if (! $device) {
                $this->pruneWebSessionsIfNeeded($user);
            }
        }

        if ($device) {
            // Preserve the existing block/revoke state — do NOT reset it on re-login
            $device->update($mutableAttributes);
        } else {
            $device = $user->devices()->create(array_merge($mutableAttributes, [
                'is_blocked' => false,
                'blocked_at' => null,
                'blocked_reason' => null,
                'revoked_at' => null,
            ]));
        }

        // Resolve location in the background — no need to block the login response
        ResolveDeviceLocation::dispatch($device->id, $ip);

        return $device;
    }

    /**
     * Update last_seen_at and IP for the device tied to the current token.
     * Only fires if the last update was more than 5 minutes ago to reduce DB load.
     * Location update is also dispatched as a background job.
     */
    public function touchDevice(User $user, int $tokenId, string $ip): void
    {
        $device = UserDevice::where('token_id', $tokenId)->first();

        if (! $device) {
            return;
        }

        $stale = is_null($device->last_seen_at) || $device->last_seen_at->diffInMinutes(now()) >= 5;

        if (! $stale) {
            return;
        }

        $device->update([
            'last_seen_at' => now(),
            'ip_address' => $ip,
        ]);

        // Only re-resolve location if the IP changed
        if ($device->ip_address !== $ip) {
            ResolveDeviceLocation::dispatch($device->id, $ip);
        }
    }

    /**
     * Ensure a user never exceeds 20 active web sessions.
     * When at capacity, the oldest session (by last_seen_at) is revoked.
     */
    private function pruneWebSessionsIfNeeded(User $user): void
    {
        $webSessionLimit = 20;

        $activeWebSessions = $user->devices()
            ->where('platform', 'web')
            ->whereNull('revoked_at')
            ->where('is_blocked', false)
            ->orderBy('last_seen_at')
            ->get();

        if ($activeWebSessions->count() >= $webSessionLimit) {
            $excess = $activeWebSessions->count() - $webSessionLimit + 1;
            $toRevoke = $activeWebSessions->take($excess);

            foreach ($toRevoke as $old) {
                $old->update(['revoked_at' => now()]);
                $old->token?->delete();
            }
        }
    }
}
