<?php

namespace App\Livewire\Admin;

use App\Models\BlockedIp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class AllBlockedIps extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 15;

    // Add form
    public string $newIp = '';

    public string $newReason = '';

    public string $newExpiresAt = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function addIp(): void
    {
        $this->validate([
            'newIp' => 'required|ip|unique:blocked_ips,ip_address',
            'newReason' => 'nullable|string|max:255',
            'newExpiresAt' => 'nullable|date|after:now',
        ]);

        BlockedIp::create([
            'ip_address' => $this->newIp,
            'reason' => $this->newReason ?: null,
            'blocked_by' => Auth::id(),
            'expires_at' => $this->newExpiresAt ?: null,
        ]);

        Cache::forget("blocked_ip:{$this->newIp}");

        $this->reset('newIp', 'newReason', 'newExpiresAt');
        $this->dispatch('closeModal');
        $this->dispatch('sweetAlert', title: 'Blocked', message: 'IP address has been blocked.', type: 'success');
    }

    public function unblock(int $id): void
    {
        $blocked = BlockedIp::findOrFail($id);
        Cache::forget("blocked_ip:{$blocked->ip_address}");
        $blocked->delete();

        $this->dispatch('sweetAlert', title: 'Unblocked', message: 'IP address has been unblocked.', type: 'success');
    }

    public function render()
    {
        $blockedIps = BlockedIp::with('blockedBy')
            ->when($this->search, fn ($q) => $q->where('ip_address', 'like', "%{$this->search}%")
                ->orWhere('reason', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.all-blocked-ips', compact('blockedIps'))
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}
