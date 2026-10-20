<?php

namespace App\Events;

use App\Models\AppleNotification;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Readdle\AppStoreServerAPI\RenewalInfo;
use Readdle\AppStoreServerAPI\ResponseBodyV2;
use Readdle\AppStoreServerAPI\TransactionInfo;

class AppStoreWebhookReceived
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $notificationType,
        public ?string $subtype,
        public ?TransactionInfo $transactionInfo,
        public ?RenewalInfo $renewalInfo,
        public ResponseBodyV2 $responseBodyV2,
        public AppleNotification $appleNotification
    ) {}
}
