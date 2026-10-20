<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Readdle\AppStoreServerAPI\Util\Helper;

class RefreshAppleCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apple:refresh-certificate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the Apple Root CA certificate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Refreshing Apple Root CA certificate...');

        try {
            $certificateUrl = 'https://www.apple.com/certificateauthority/AppleRootCA-G3.cer';
            $certificatePath = storage_path('app/private/certificates/AppleRootCA-G3.cer');

            // Create directory if it doesn't exist
            if (! is_dir(dirname($certificatePath))) {
                mkdir(dirname($certificatePath), 0755, true);
            }

            // Download the certificate
            $response = Http::timeout(30)->get($certificateUrl);

            if (! $response->successful()) {
                $this->error('Failed to download certificate: '.$response->status());

                return 1;
            }

            // Save the certificate
            file_put_contents($certificatePath, $response->body());

            // Verify the certificate can be converted to PEM
            $pem = Helper::toPEM($response->body());
            if (! $pem) {
                $this->error('Failed to convert certificate to PEM format');

                return 1;
            }

            $this->info('Certificate refreshed successfully');

            return 0;

        } catch (\Exception $e) {
            $this->error('Error refreshing certificate: '.$e->getMessage());

            return 1;
        }
    }
}
