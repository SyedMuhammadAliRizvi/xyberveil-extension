<?php

namespace App\Jobs;

use App\Mail\BillingFailedMail;
use App\Models\LocalSubscription;
use App\Models\User;
use App\Traits\UsesDynamicSmtp;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendBillingFailedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UsesDynamicSmtp;

    public $tries = 3;

    public $timeout = 120;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public LocalSubscription $subscription,
        public string $failureReason,
        public bool $hasGracePeriod = false,
        public ?string $gracePeriodEndsAt = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Double-check user still exists and has valid email
            if (! $this->user->email || ! filter_var($this->user->email, FILTER_VALIDATE_EMAIL)) {
                Log::error('Cannot send billing failed email - invalid user email', [
                    'user_id' => $this->user->id,
                    'email' => $this->user->email,
                ]);

                return;
            }

            // Check if subscription is still in failed/grace state to avoid outdated emails
            $currentSubscription = $this->user->localSubscriptions()->find($this->subscription->id);
            if (! $currentSubscription || ! in_array($currentSubscription->status, ['failed', 'grace'])) {
                Log::info('Skipping billing failed email - subscription status changed', [
                    'user_id' => $this->user->id,
                    'subscription_id' => $this->subscription->id,
                    'current_status' => $currentSubscription?->status,
                ]);

                return;
            }

            $graceEndsAt = $this->gracePeriodEndsAt
                ? Carbon::parse($this->gracePeriodEndsAt)
                : null;

            $this->applySmtpConfig();

            Mail::to($this->user->email)->send(new BillingFailedMail(
                $this->user,
                $this->subscription,
                $this->failureReason,
                $this->hasGracePeriod,
                $graceEndsAt
            ));

            Log::info('Billing failed email sent successfully', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'subscription_id' => $this->subscription->id,
                'has_grace_period' => $this->hasGracePeriod,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send billing failed email', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'subscription_id' => $this->subscription->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    public function backoff(): array
    {
        return [3, 6, 10];
    }

    public function retryUntil()
    {
        return now()->addMinutes(5); // Retry for 5 minutes
    }

    public function failed(?Throwable $exception)
    {
        Log::error('Failed to send ('.$this->user->email.') billing failed email: '.$exception->getMessage());
    }
}
