<?php

use App\Console\Commands\RefreshAppleCertificate;
use App\Jobs\FetchProxyUsersSummary;
use App\Jobs\SendTrialExpiryNotifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::command(RefreshAppleCertificate::class)
    ->monthly();

Schedule::job(new SendTrialExpiryNotifications)
    ->dailyAt('09:00')
    ->onFailure(function () {
        Log::error('Failed to send trial expiry notifications');
    });

Schedule::job(new FetchProxyUsersSummary)
    ->everyTwoMinutes()
    ->onFailure(function () {
        Log::channel('vps')->error('Failed to fetch proxy users summary');
    });
