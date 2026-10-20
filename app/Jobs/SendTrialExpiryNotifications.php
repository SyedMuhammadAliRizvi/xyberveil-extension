<?php

namespace App\Jobs;

use App\Mail\TrialExpiryNotificationMail;
use App\Models\User;
use App\Traits\UsesDynamicSmtp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTrialExpiryNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UsesDynamicSmtp;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     *
     * @var int
     */
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->applySmtpConfig();

        $users = User::trialEndingSoon(24)
            ->where('trial_expired_notification_sent', false)
            ->get();

        if ($users->isEmpty()) {
            Log::info('No users found with trials ending soon');

            return;
        }

        $notificationsSent = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                // Send email notification
                Mail::to($user->email)->send(new TrialExpiryNotificationMail($user));

                // Mark notification as sent
                $user->markTrialExpiryNotificationSent();

                $notificationsSent++;

                Log::info("Trial expiry notification sent to user: {$user->email}");
            } catch (\Exception $e) {
                $errors++;
                Log::error("Failed to send trial expiry notification to user {$user->email}: ".$e->getMessage());
            }
        }

        Log::info("Trial expiry notifications completed. Sent: {$notificationsSent}, Errors: {$errors}");
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Trial expiry notifications job failed: '.$exception->getMessage());
    }
}
