<?php

namespace App\Jobs\Proxy;

use App\Services\Proxy\ProxyProvisioningService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProvisionProxyUserJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $userId) {}

    public function handle(ProxyProvisioningService $provisioningService): void
    {
        $provisioningService->provisionUserEverywhere($this->userId);
    }
}
