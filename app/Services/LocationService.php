<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LocationService
{
    /**
     * Get human-readable location from IP with fallback.
     */
    public function getLocationFromIP(string $ip, ?string $provider = null): array
    {
        if ($this->isLocalIP($ip)) {
            return [
                'city' => 'Localhost',
                'country' => null,
                'countryCode' => null,
                'lat' => null,
                'lon' => null,
                'formatted' => 'Localhost',
            ];
        }

        $cacheKey = $provider ? "ip_location_{$ip}_{$provider}" : "ip_location_{$ip}";

        return Cache::remember($cacheKey, now()->addSeconds(30), function () use ($ip, $provider) {
            if ($provider === 'ipinfo') {
                return $this->getFromIpInfo($ip) ?? $this->nullLocation();
            }

            // Default: ip-api with ipinfo fallback
            return $this->getFromIpApi($ip) ?? $this->getFromIpInfo($ip) ?? $this->nullLocation();
        });
    }

    protected function nullLocation(): array
    {
        return [
            'city' => null,
            'country' => null,
            'countryCode' => null,
            'lat' => null,
            'lon' => null,
            'formatted' => 'Unknown',
        ];
    }

    protected function getFromIpApi(string $ip): ?array
    {
        try {
            $res = Http::timeout(4)->get("http://ip-api.com/json/{$ip}");
            if ($res->failed() || $res->json('status') !== 'success') {
                return null;
            }

            $data = $res->json();
            $data['provider'] = 'ip-api';
            $data['formatted'] = collect([$data['city'], $data['regionName'] ?? null, $data['country']])->filter()->implode(', ');

            return $data;
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function getFromIpInfo(string $ip): ?array
    {
        try {
            $token = config('services.ipinfo.token');
            $res = Http::timeout(4)->get("https://ipinfo.io/{$ip}/json?token={$token}");
            if ($res->failed()) {
                return null;
            }

            $data = $res->json();
            $data['provider'] = 'ipinfo';
            $data['formatted'] = collect([$data['city'] ?? null, $data['region'] ?? null, $data['country'] ?? null])->filter()->implode(', ');

            return $data;
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function isLocalIP(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
