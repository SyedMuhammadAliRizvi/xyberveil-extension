<?php

namespace App\Jobs;

use App\Models\User;
use App\Traits\UsesDynamicSmtp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendEmailVerification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, UsesDynamicSmtp;

    public $user;

    public $tries = 3;

    public $timeout = 120;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->applySmtpConfig();

            $this->user->sendEmailVerificationNotification();
        } catch (Throwable $e) {
            Log::error("Failed to send email verification to {$this->user->email}: ".$e->getMessage());
            throw $e;
        }
    }

    public function backoff(): array
    {
        return [3, 6, 10];
    }

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function failed(?Throwable $exception)
    {
        Log::error('Failed to send email verification: '.$exception->getMessage());
    }
}
