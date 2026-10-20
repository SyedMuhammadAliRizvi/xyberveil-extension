<?php

namespace App\Jobs\Proxy;

use App\Services\Proxy\ProxyProvisioningService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeprovisionProxyUserJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $linuxUsername) {}

    public function handle(ProxyProvisioningService $provisioningService): void
    {
        $provisioningService->deprovisionUserEverywhere($this->linuxUsername);
    }
}
