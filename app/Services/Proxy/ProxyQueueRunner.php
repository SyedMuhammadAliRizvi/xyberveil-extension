<?php

namespace App\Services\Proxy;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ProxyQueueRunner
{
    /**
     * Process pending database-queue jobs until empty or time limit.
     *
     * @return array{processed: bool, pending_before: int, pending_after: int, output: string}
     */
    public static function drain(int $maxTimeSeconds = 300): array
    {
        $pendingBefore = (int) DB::table('jobs')->count();

        if ($pendingBefore === 0) {
            return [
                'processed' => false,
                'pending_before' => 0,
                'pending_after' => 0,
                'output' => '',
            ];
        }

        set_time_limit($maxTimeSeconds + 60);

        Artisan::call('queue:work', [
            '--stop-when-empty' => true,
            '--max-time' => $maxTimeSeconds,
            '--timeout' => 600,
            '--tries' => 1,
            '--verbose' => true,
        ]);

        return [
            'processed' => true,
            'pending_before' => $pendingBefore,
            'pending_after' => (int) DB::table('jobs')->count(),
            'output' => trim(Artisan::output()),
        ];
    }
}
