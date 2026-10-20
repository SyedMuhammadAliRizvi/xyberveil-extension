<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateUserSlugs extends Command
{
    protected $signature = 'users:generate-slugs {--force : Regenerate slugs for all users} {--chunk=200 : Chunk size for processing}';

    protected $description = 'Generate slugs for users that are missing one';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Generating slugs...');

        // Get chunk size from option
        $chunkSize = (int) $this->option('chunk');

        $query = User::query();

        if (! $this->option('force')) {
            $query->where(function ($q) {
                $q->whereNull('slug')->orWhere('slug', '');
            });
        }

        // Get total count for progress bar
        $totalUsers = $query->count();

        if ($totalUsers === 0) {
            $this->info('✅ No users need slug generation.');

            return self::SUCCESS;
        }

        $this->info("📊 Found {$totalUsers} users to process in chunks of {$chunkSize}.");

        // Ask for confirmation on large datasets
        if ($totalUsers > 5000 && ! $this->confirm('This will process a large number of records. Continue?')) {
            $this->info('❌ Operation cancelled.');

            return self::FAILURE;
        }

        // Create progress bar
        $progressBar = $this->output->createProgressBar($totalUsers);
        $progressBar->setFormat('verbose');
        $progressBar->start();

        $count = 0;
        $errors = 0;
        $startTime = microtime(true);

        // Use database transaction for better performance
        DB::transaction(function () use ($query, $chunkSize, $progressBar, &$count, &$errors) {
            // Process users in chunks
            $query->chunk($chunkSize, function ($users) use ($progressBar, &$count, &$errors) {
                foreach ($users as $user) {
                    try {
                        // Check if user already has a slug (in case of --force)
                        $originalSlug = $user->slug;

                        // Generate slug using the sluggable package
                        $user->generateSlug();

                        // Only save if slug actually changed (optimization)
                        if ($user->slug !== $originalSlug) {
                            $user->save();
                        }

                        $count++;
                        $progressBar->advance();

                    } catch (\Exception $e) {
                        $errors++;
                        $this->error("Failed to generate slug for user ID {$user->id}: ".$e->getMessage());
                        $progressBar->advance(); // Still advance progress bar
                    }
                }

                // Optional: Add small delay to prevent overwhelming the database
                if ($count % 1000 === 0) {
                    usleep(100000); // 0.1 second pause every 1000 records
                }
            });
        });

        $progressBar->finish();
        $this->newLine();

        $endTime = microtime(as_float: true);
        $executionTime = round($endTime - $startTime, 2);

        $this->info("✅ Slugs generated for {$count} users.");
        if ($errors > 0) {
            $this->warn("⚠️  {$errors} errors occurred during processing.");
        }
        $this->info("⏱️  Execution time: {$executionTime} seconds");

        // Memory usage info
        $memoryUsage = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
        $this->info("💾 Peak memory usage: {$memoryUsage} MB");

        return self::SUCCESS;
    }
}
