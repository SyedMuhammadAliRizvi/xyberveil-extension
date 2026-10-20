<?php

namespace App\Jobs\Proxy;

use App\Services\Proxy\ProxyProvisioningService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProvisionAllUsersOnProxyServerJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $serverId) {}

    public function handle(ProxyProvisioningService $provisioningService): void
    {
        $provisioningService->provisionAllUsersOnServer($this->serverId);
    }
}
