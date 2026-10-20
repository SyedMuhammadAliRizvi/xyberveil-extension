<?php

namespace App\Console\Commands;

use App\Jobs\SendTrialExpiryNotifications;
use App\Models\User;
use Illuminate\Console\Command;

class TestTrialCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial:test {action} {--user-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test trial functionality (actions: send-notifications, user-info, grant-trial)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'send-notifications':
                return $this->testSendNotifications();

            case 'user-info':
                return $this->showUserTrialInfo();

            case 'grant-trial':
                return $this->grantTrialToUser();

            default:
                $this->error("Unknown action: {$action}");
                $this->info('Available actions: send-notifications, user-info, grant-trial');

                return self::FAILURE;
        }
    }

    private function testSendNotifications(): int
    {
        $this->info('🚀 Testing trial expiry notifications...');

        $job = new SendTrialExpiryNotifications;
        $job->handle();

        $this->info('✅ Test completed');

        return self::SUCCESS;
    }

    private function showUserTrialInfo(): int
    {
        $userId = $this->option('user-id');

        if (! $userId) {
            $this->error('Please provide --user-id option');

            return self::FAILURE;
        }

        $user = User::find($userId);

        if (! $user) {
            $this->error("User with ID {$userId} not found");

            return self::FAILURE;
        }

        $trialDetails = $user->getTrialStatusWithDescription();

        $this->info("📊 Trial Info for {$user->name} ({$user->email}):");
        $this->table(
            ['Property', 'Value'],
            [
                ['Status', $trialDetails['status']],
                ['Description', $trialDetails['description']],
                ['Days Remaining', $trialDetails['days_remaining']],
                ['Hours Remaining', $trialDetails['hours_remaining']],
                ['Progress %', number_format($trialDetails['progress_percentage'], 1).'%'],
                ['Started At', $user->trial_started_at?->format('Y-m-d H:i:s') ?? 'N/A'],
                ['Ends At', $user->trial_ends_at?->format('Y-m-d H:i:s') ?? 'N/A'],
                ['Has Used Trial', $user->hasUsedTrial() ? 'Yes' : 'No'],
                ['Is On Trial', $user->isOnTrial() ? 'Yes' : 'No'],
                ['Has Expired', $user->hasTrialExpired() ? 'Yes' : 'No'],
                ['Ending Soon', $user->isTrialEndingSoon() ? 'Yes' : 'No'],
            ]
        );

        return self::SUCCESS;
    }

    private function grantTrialToUser(): int
    {
        $userId = $this->option('user-id');

        if (! $userId) {
            $this->error('Please provide --user-id option');

            return self::FAILURE;
        }

        $user = User::find($userId);

        if (! $user) {
            $this->error("User with ID {$userId} not found");

            return self::FAILURE;
        }

        if ($user->hasUsedTrial()) {
            $this->warn("User {$user->name} has already used their trial");

            return self::FAILURE;
        }

        $days = $this->ask('How many days for the trial?', '7');
        $success = $user->startTrial((int) $days);

        if ($success) {
            $this->info("✅ Trial granted to {$user->name} for {$days} days");
        } else {
            $this->error("❌ Failed to grant trial to {$user->name}");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
