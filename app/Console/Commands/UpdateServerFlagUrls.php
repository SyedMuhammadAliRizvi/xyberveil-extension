<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;

class UpdateServerFlagUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servers:update-flag-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all server flag URLs from old format (64x48) to new format (w160)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting flag URL update...');

        $servers = Server::whereNotNull('country_code')->get();

        if ($servers->isEmpty()) {
            $this->warn('No servers found with country codes.');

            return self::SUCCESS;
        }

        $this->info("Found {$servers->count()} server(s) to update.");

        $progressBar = $this->output->createProgressBar($servers->count());
        $progressBar->start();

        $updatedCount = 0;

        foreach ($servers as $server) {
            $countryCode = strtolower($server->country_code);
            $newFlagUrl = "https://flagcdn.com/w160/{$countryCode}.png";

            // Only update if the URL is different
            if ($server->flag_url !== $newFlagUrl) {
                $server->update(['flag_url' => $newFlagUrl]);
                $updatedCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✓ Successfully updated {$updatedCount} server flag URL(s).");

        return self::SUCCESS;
    }
}
