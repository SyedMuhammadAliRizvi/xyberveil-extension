<?php

namespace App\Jobs;

use App\Models\LocalSubscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateSubscriptionStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [10, 30, 60];

    public $logger;

    public function __construct()
    {
        $this->logger = Log::channel('subscriptions');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = now();
        $totalUpdated = 0;

        $this->logger->info('[UpdateSubscriptionStatusJob] Starting subscription status update for LOCAL subscriptions only...');

        try {
            DB::transaction(function () use ($now, &$totalUpdated) {
                // 1. Update trialing subscriptions to active (trial period ended)
                $totalUpdated += $this->updateTrialingToActive($now);

                // 2. Update active subscriptions to expired (billing period ended)
                $totalUpdated += $this->updateActiveToExpired($now);

                // 3. Update active/expired subscriptions to grace period
                $totalUpdated += $this->updateToGracePeriod($now);

                // 4. Update grace period subscriptions to expired/cancelled
                $totalUpdated += $this->updateGraceToExpired($now);

                // 5. Handle failed payment scenarios
                $totalUpdated += $this->handleFailedPayments($now);
            });

            $this->logger->info("[UpdateSubscriptionStatusJob] Subscription status update completed. Total updated: {$totalUpdated}");
        } catch (\Exception $e) {
            $this->logger->error('[UpdateSubscriptionStatusJob] Failed to update subscription statuses', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    private function updateTrialingToActive(Carbon $now): int
    {
        $subscriptions = LocalSubscription::where('status', 'trialing')
            ->where('provider', 'local')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<=', $now)
            ->get();

        $count = 0;
        foreach ($subscriptions as $subscription) {
            $subscription->update([
                'status' => 'active',
                'updated_at' => $now,
            ]);
            $count++;
        }

        if ($count > 0) {
            $this->logger->info("[UpdateSubscriptionStatusJob] Updated {$count} subscriptions from trialing to active");
        }

        return $count;
    }

    private function updateActiveToExpired(Carbon $now): int
    {
        $subscriptions = LocalSubscription::where('status', 'active')
            ->where('provider', 'local')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', $now)
            ->where('is_recurring', false)
            ->with('plan')
            ->get();

        $count = 0;
        foreach ($subscriptions as $subscription) {
            $plan = $subscription->plan;

            if ($plan && $plan->grace_period > 0) {
                $graceEndsAt = $plan->getGraceEndsAt($subscription->ends_at);
                $subscription->update([
                    'status' => 'grace',
                    'grace_ends_at' => $graceEndsAt,
                    'updated_at' => $now,
                ]);
            } else {
                $subscription->update([
                    'status' => 'expired',
                    'ends_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $count++;
        }

        if ($count > 0) {
            $this->logger->info("[UpdateSubscriptionStatusJob] Updated {$count} active subscriptions");
        }

        return $count;
    }

    private function updateToGracePeriod(Carbon $now): int
    {
        $subscriptions = LocalSubscription::where('status', 'active')
            ->where('provider', 'local')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', $now)
            ->where('is_recurring', true)
            ->whereHas('plan', function ($query) {
                $query->where('grace_period', '>', 0);
            })
            ->with('plan')
            ->get();

        $count = 0;
        foreach ($subscriptions as $subscription) {
            $plan = $subscription->plan;
            $graceEndsAt = $plan->getGraceEndsAt($subscription->ends_at);

            $subscription->update([
                'status' => 'grace',
                'grace_ends_at' => $graceEndsAt,
                'updated_at' => $now,
            ]);
            $count++;
        }

        if ($count > 0) {
            $this->logger->info("[UpdateSubscriptionStatusJob] Updated {$count} subscriptions to grace period");
        }

        return $count;
    }

    private function updateGraceToExpired(Carbon $now): int
    {
        $subscriptions = LocalSubscription::where('status', 'grace')
            ->where('provider', 'local')
            ->whereNotNull('grace_ends_at')
            ->where('grace_ends_at', '<=', $now)
            ->get();

        $count = 0;
        foreach ($subscriptions as $subscription) {
            $subscription->update([
                'status' => 'expired',
                'cancelled_by' => 'system',
                'cancelled_reason' => 'Grace period expired',
                'updated_at' => $now,
            ]);
            $count++;
        }

        if ($count > 0) {
            $this->logger->info("[UpdateSubscriptionStatusJob] Updated {$count} subscriptions from grace to expired");
        }

        return $count;
    }

    private function handleFailedPayments(Carbon $now): int
    {
        $subscriptions = LocalSubscription::where('status', 'failed')
            ->where('provider', 'local')
            ->where('updated_at', '<=', $now->copy()->subDays(7))
            ->get();

        $count = 0;
        foreach ($subscriptions as $subscription) {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_by' => 'system',
                'cancelled_reason' => 'Payment failed - auto cancelled after 7 days',
                'updated_at' => $now,
            ]);
            $count++;
        }

        if ($count > 0) {
            $this->logger->info("[UpdateSubscriptionStatusJob] Updated {$count} failed subscriptions to cancelled");
        }

        return $count;
    }

    public function failed(\Throwable $exception): void
    {
        Log::channel('subscriptions')->error('[UpdateSubscriptionStatusJob] Job failed completely', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
