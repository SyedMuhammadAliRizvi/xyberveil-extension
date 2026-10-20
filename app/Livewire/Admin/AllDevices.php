<?php

namespace App\Livewire\Admin;

use App\Models\BlockedIp;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class AllDevices extends Component
{
    use WithPagination;

    public string $search = '';

    public string $platformFilter = '';

    public string $statusFilter = '';

    public string $deviceTypeFilter = '';

    public int $perPage = 15;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPlatformFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedDeviceTypeFilter(): void
    {
        $this->resetPage();
    }

    public function blockDevice(int $deviceId, string $reason = ''): void
    {
        $device = UserDevice::findOrFail($deviceId);
        $device->update([
            'is_blocked' => true,
            'blocked_at' => now(),
            'blocked_reason' => $reason ?: 'Blocked by admin',
        ]);

        // Delete the associated Sanctum token so the device is immediately kicked out
        if ($device->token_id) {
            $device->token()->delete();
            $device->update(['token_id' => null]);
        }

        $this->dispatch('sweetAlert', title: 'Blocked', message: 'Device has been blocked.', type: 'success');
    }

    public function unblockDevice(int $deviceId): void
    {
        $device = UserDevice::findOrFail($deviceId);
        $device->update([
            'is_blocked' => false,
            'blocked_at' => null,
            'blocked_reason' => null,
        ]);

        $this->dispatch('sweetAlert', title: 'Unblocked', message: 'Device has been unblocked.', type: 'success');
    }

    public function revokeDevice(int $deviceId): void
    {
        $device = UserDevice::findOrFail($deviceId);

        if ($device->token_id) {
            $device->token()->delete();
        }

        $device->update([
            'revoked_at' => now(),
            'token_id' => null,
        ]);

        $this->dispatch('sweetAlert', title: 'Revoked', message: 'Device access has been revoked.', type: 'success');
    }

    public function removeDevice(int $deviceId): void
    {
        $device = UserDevice::findOrFail($deviceId);

        if ($device->token_id) {
            $device->token()->delete();
        }

        $device->delete();

        $this->dispatch('sweetAlert', title: 'Removed', message: 'Device record removed.', type: 'success');
    }

    public function blockDeviceIp(int $deviceId, string $reason = ''): void
    {
        $device = UserDevice::findOrFail($deviceId);

        if (! $device->ip_address) {
            $this->dispatch('sweetAlert', title: 'Error', message: 'No IP address associated with this device.', type: 'error');

            return;
        }

        BlockedIp::updateOrCreate(
            ['ip_address' => $device->ip_address],
            [
                'reason' => $reason ?: "Blocked via device management (device #{$deviceId})",
                'blocked_by' => Auth::id(),
            ]
        );

        Cache::forget("blocked_ip:{$device->ip_address}");

        $this->dispatch('sweetAlert', title: 'IP Blocked', message: "IP {$device->ip_address} has been blocked.", type: 'success');
    }

    public function unblockDeviceIp(string $ip): void
    {
        $blocked = BlockedIp::where('ip_address', $ip)->first();

        if (! $blocked) {
            $this->dispatch('sweetAlert', title: 'Not Found', message: 'IP is not in the blocked list.', type: 'info');

            return;
        }

        Cache::forget("blocked_ip:{$ip}");
        $blocked->delete();

        $this->dispatch('sweetAlert', title: 'IP Unblocked', message: "IP {$ip} has been unblocked.", type: 'success');
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->platformFilter = '';
        $this->statusFilter = '';
        $this->deviceTypeFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $devices = UserDevice::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('model', 'like', "%{$this->search}%")
                        ->orWhere('ip_address', 'like', "%{$this->search}%")
                        ->orWhere('os', 'like', "%{$this->search}%")
                        ->orWhere('country', 'like', "%{$this->search}%")
                        ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                            ->orWhere('email', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->platformFilter, fn ($q) => $q->where('platform', $this->platformFilter))
            ->when($this->deviceTypeFilter, fn ($q) => $q->where('device_type', $this->deviceTypeFilter))
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_blocked', false)->whereNull('revoked_at'))
            ->when($this->statusFilter === 'blocked', fn ($q) => $q->where('is_blocked', true))
            ->when($this->statusFilter === 'revoked', fn ($q) => $q->whereNotNull('revoked_at'))
            ->latest()
            ->paginate($this->perPage);

        // Build a lookup set of currently blocked IPs for the current page
        $blockedIpSet = BlockedIp::active()
            ->whereIn('ip_address', $devices->pluck('ip_address')->filter()->unique()->values())
            ->pluck('ip_address')
            ->flip()
            ->toArray();

        return view('livewire.admin.all-devices', compact('devices', 'blockedIpSet'))
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}
