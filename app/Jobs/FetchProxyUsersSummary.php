<?php

namespace App\Jobs;

use App\Services\Proxy\ProxyAdminService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class FetchProxyUsersSummary implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $uniqueFor = 120;

    public function handle(ProxyAdminService $adminService): void
    {
        try {
            Cache::put('proxy_users_summary', [
                'fetched_at' => now()->toISOString(),
                'error' => null,
                'stats' => $adminService->dashboard(),
                'sessions' => $adminService->sessions(['live_only' => true]),
            ], 180);
        } catch (\Throwable $exception) {
            Cache::put('proxy_users_summary', [
                'fetched_at' => now()->toISOString(),
                'error' => $exception->getMessage(),
                'stats' => null,
                'sessions' => [],
            ], 180);
        }
    }
}
