<?php

namespace App\Services\Proxy;

use App\Jobs\ResolveProxyExtensionDeviceLocation;
use App\Models\ProxyExtensionDevice;
use App\Models\ProxyRefreshToken;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProxyExtensionDeviceService
{
    public const DEVICE_UNAUTHORIZED_MSG = 'This device is not authorized. Contact support.';

    public const DEVICE_LIMIT_MSG = 'Device limit reached. Upgrade your plan to add this one.';

    public function revokeDeviceTokens(int $userId, ?string $deviceKey): void
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

    public function assertDeviceAuthorized(int $userId, ?string $deviceKey, bool $requireRegistered = false): void
    {
        if (! $deviceKey) {
            return;
        }

        $key = trim($deviceKey);

        if ($key === '' || strlen($key) < 8) {
            return;
        }

        $device = ProxyExtensionDevice::query()->where('device_key', $key)->first();

        if (! $device) {
            if ($requireRegistered) {
                $this->abortForbidden(self::DEVICE_UNAUTHORIZED_MSG);
            }

            return;
        }

        if ($device->user_id !== $userId || ! $device->is_allowed) {
            $this->abortForbidden(self::DEVICE_UNAUTHORIZED_MSG);
        }
    }

    public function upsertExtensionDevice(
        User $user,
        ?string $deviceKey,
        ?string $browserName,
        string $browserVersion = '',
        string $osName = '',
        string $osVersion = '',
        string $extensionVersion = '',
        ?string $ipAddress = null,
        ?string $timezone = null,
    ): void {
        if (! $deviceKey || ! $browserName) {
            return;
        }

        $key = trim($deviceKey);

        if (strlen($key) < 8 || strlen($key) > 64) {
            return;
        }

        $userId = $user->id;
        $ip = $ipAddress ? trim($ipAddress) : null;
        $now = now();
        $device = ProxyExtensionDevice::query()->where('device_key', $key)->first();

        if ($device && $device->user_id !== $userId) {
            $this->abortForbidden(self::DEVICE_UNAUTHORIZED_MSG);
        }

        if (! $device && $browserName) {
            $device = ProxyExtensionDevice::query()
                ->where('user_id', $userId)
                ->where('browser_name', trim($browserName))
                ->where('os_name', $osName)
                ->where('is_allowed', true)
                ->first();

            if ($device) {
                $device->device_key = $key;
            }
        }

        // Checked on every call, not just brand-new devices: count this user's
        // *other* active devices (excluding this one) against their current
        // limit. That way a limit lowered below the existing device count —
        // including down to 0 — takes effect immediately on next use, instead
        // of only blocking devices that don't exist yet.
        if ($this->activeExtensionDeviceCount($userId, $device?->id) >= $user->deviceLimit()) {
            $this->abortForbidden(self::DEVICE_LIMIT_MSG);
        }

        $attributes = [
            'browser_name' => trim($browserName),
            'browser_version' => $browserVersion,
            'os_name' => $osName,
            'os_version' => $osVersion,
            'extension_version' => $extensionVersion,
            'last_seen' => $now,
        ];

        if ($ip) {
            $attributes['ip_address'] = $ip;
        }

        $normalizedTimezone = $this->normalizeTimezone($timezone);

        if ($normalizedTimezone) {
            $attributes['timezone'] = $normalizedTimezone;
        }

        if ($device) {
            if (! $device->is_allowed) {
                $this->abortForbidden(self::DEVICE_UNAUTHORIZED_MSG);
            }

            $previousIp = $device->ip_address;
            $device->fill($attributes);
            $device->save();
            $this->resolveLocationIfNeeded($device, $ip, $previousIp);

            return;
        }

        $device = ProxyExtensionDevice::query()->create(array_merge($attributes, [
            'user_id' => $userId,
            'device_key' => $key,
            'device_type' => 'extension',
            'is_allowed' => true,
        ]));

        $this->resolveLocationIfNeeded($device, $ip);
    }

    private function activeExtensionDeviceCount(int $userId, ?int $excludeDeviceId = null): int
    {
        return ProxyExtensionDevice::query()
            ->where('user_id', $userId)
            ->where('is_allowed', true)
            ->when($excludeDeviceId, fn ($query) => $query->where('id', '!=', $excludeDeviceId))
            ->count();
    }

    private function normalizeTimezone(?string $timezone): ?string
    {
        if ($timezone === null) {
            return null;
        }

        $timezone = trim($timezone);

        if ($timezone === '' || strlen($timezone) > 64) {
            return null;
        }

        if (! in_array($timezone, timezone_identifiers_list(), true)) {
            return null;
        }

        return $timezone;
    }

    private function resolveLocationIfNeeded(ProxyExtensionDevice $device, ?string $ip, ?string $previousIp = null): void
    {
        if (! $ip) {
            return;
        }

        if ($previousIp !== null && $previousIp === $ip && $device->city && $device->country) {
            return;
        }

        ResolveProxyExtensionDeviceLocation::dispatch($device->id, $ip);
    }

    private function abortForbidden(string $message): never
    {
        throw new HttpResponseException(response()->json([
            'detail' => $message,
        ], 403));
    }
}
