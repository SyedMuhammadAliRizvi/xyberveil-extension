<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

class GenerateAppStoreWebhookUrl extends Command
{
    protected $signature = 'appstore:url';

    protected $description = 'Generate a permanent signed URL for the App Store webhook endpoint';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating App Store Webhook URL...');
        $this->info('Make sure your APP_URL is correctly set in the .env file.');
        $this->info('--------------------------------------------------------------');
        $url = URL::signedRoute('webhook.appstore');
        $this->info('✅ Signed App Store Webhook URL (copy this into App Store Connect):');
        $this->line($url);
        $this->info('--------------------------------------------------------------');
    }
}
