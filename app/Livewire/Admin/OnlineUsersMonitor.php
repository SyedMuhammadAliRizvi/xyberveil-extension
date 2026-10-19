<?php

namespace App\Livewire\Admin;

use App\Jobs\FetchProxyUsersSummary;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class OnlineUsersMonitor extends Component
{
    public array $proxySummary = [];

    public bool $isProxyRefreshing = false;

    public function mount(): void
    {
        $this->loadProxyFromCache();

        if (empty($this->proxySummary['fetched_at'])) {
            $this->fetchProxySummary();
        }
    }

    public function loadProxyFromCache(): void
    {
        $cached = Cache::get('proxy_users_summary');

        $this->proxySummary = $cached ?? [
            'fetched_at' => null,
            'error' => null,
            'stats' => null,
            'sessions' => [],
        ];
    }

    public function refreshProxy(): void
    {
        $this->isProxyRefreshing = true;
        $this->fetchProxySummary();
        $this->isProxyRefreshing = false;
        $this->dispatch('sweetAlert', title: 'Refreshed', message: 'Proxy users data updated.', type: 'success');
    }

    private function fetchProxySummary(): void
    {
        FetchProxyUsersSummary::dispatchSync();
        $this->loadProxyFromCache();
    }

    public function pollRefresh(): void
    {
        $this->loadProxyFromCache();
    }

    public function render()
    {
        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.online-users-monitor')
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}
