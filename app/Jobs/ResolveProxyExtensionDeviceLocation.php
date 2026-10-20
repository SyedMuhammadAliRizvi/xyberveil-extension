<?php

namespace App\Jobs;

use App\Models\ProxyExtensionDevice;
use App\Services\LocationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ResolveProxyExtensionDeviceLocation implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly int $deviceId, public readonly string $ip) {}

    public function handle(LocationService $locationService): void
    {
        $device = ProxyExtensionDevice::find($this->deviceId);

        if (! $device) {
            return;
        }

        $location = $locationService->getLocationFromIP($this->ip);

        $device->update([
            'city' => $location['city'] ?? null,
            'country' => $location['country'] ?? null,
            'country_code' => $location['countryCode'] ?? null,
        ]);
    }
}
