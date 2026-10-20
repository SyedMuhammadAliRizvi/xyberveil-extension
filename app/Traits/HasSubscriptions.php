<?php

namespace App\Traits;

use App\Models\LocalSubscription;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait HasSubscriptions
{
    /**
     * Get all subscriptions for the model.
     */
    public function localSubscriptions(): HasMany
    {
        return $this->hasMany(LocalSubscription::class);
    }

    /**
     * Get the active subscription for the model.
     *
     * @return LocalSubscription|null|HasOne
     */
    public function activeSubscription()
    {
        // Eloquent hasOne relationship for the active subscription
        return $this->hasOne(LocalSubscription::class)
            ->where(function ($q) {
                $q->whereIn('status', ['trialing', 'active', 'cancelled'])
                    ->where('ends_at', '>', now())
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'grace')
                            ->where('grace_ends_at', '>', now());
                    });
            })
            ->latest('ends_at');
    }

    /**
     * Get the current plan of the user.
     *
     * @return LocalSubscription|null
     */
    public function subscribeTo(Plan $plan): LocalSubscription
    {
        return DB::transaction(function () use ($plan) {
            $this->cancelSubscription(null, 'system', 'Cancelled Due to New Subscription', true);

            $trialEndsAt = $plan->getTrialEndsAt();
            $billingDays = $plan->getBillingDurationInDays();
            $endsAt = $trialEndsAt
                ? $trialEndsAt->copy()->addDays($billingDays)
                : now()->addDays($billingDays);

            return $this->LocalSubscriptions()->create([
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'trial_ends_at' => $trialEndsAt,
                'ends_at' => $endsAt,
                'grace_ends_at' => $plan->getGraceEndsAt($endsAt),
                'amount_paid' => $plan->discount_price,
                'currency' => 'USD',
                'status' => $trialEndsAt ? 'trialing' : 'active',
                'is_recurring' => true,
                'provider' => 'local',
            ]);
        });
    }

    public function upgradeTo(Plan $newPlan): LocalSubscription
    {
        return DB::transaction(function () use ($newPlan) {
            $current = $this->activeSubscription;
            $credit = $current ? $this->calculateProrationCredit($current) : 0;
            $price = $newPlan->discount_price ?? $newPlan->original_price;
            $amountDue = max(0, $price - $credit);
            if ($amountDue < 0) {
                $amountDue = 0; // Ensure amount due is not negative
            }

            $startsAt = $current?->starts_at ?? now();
            $trialEndsAt = $newPlan->getTrialEndsAt();
            $billingDays = $newPlan->getBillingDurationInDays();
            $endsAt = $trialEndsAt
                ? $trialEndsAt->copy()->addDays($billingDays)
                : now()->addDays($billingDays);

            $newSub = $this->LocalSubscriptions()->create([
                'plan_id' => $newPlan->id,
                'starts_at' => $startsAt,
                'trial_ends_at' => $trialEndsAt,
                'ends_at' => $endsAt,
                'grace_ends_at' => $newPlan->getGraceEndsAt($endsAt),
                'amount_paid' => $amountDue,
                'status' => $trialEndsAt ? 'trialing' : 'active',
                'is_recurring' => true,
                'provider' => 'local',
                'previous_subscription_id' => $current?->id,
                'proration_meta' => [
                    'credit' => $credit,
                    'from_plan' => $current?->plan->slug,
                    'original_price' => $price,
                ],
            ]);

            if ($current) {
                $current->update([
                    'status' => 'cancelled',
                    'ends_at' => now(),
                    'cancelled_by' => 'system',
                    'cancelled_reason' => 'Upgraded to plan: '.$newPlan->slug,
                ]);
            }

            return $newSub;
        });
    }

    public function cancelSubscription($subscriptionId = null, $cancelledBy = 'user', $reason = null, $immediately = false): bool
    {
        // If specific subscription ID provided, cancel that one
        if ($subscriptionId) {
            $subscription = $this->LocalSubscriptions()->find($subscriptionId);
            if (! $subscription) {
                Log::warning('Subscription not found for cancellation', [
                    'user_id' => $this->id,
                    'subscription_id' => $subscriptionId,
                ]);

                return false;
            }

            return $this->cancelSpecificSubscription($subscription, $cancelledBy, $reason, $immediately);
        }

        // Otherwise, cancel current active subscription (backward compatibility)
        $current = $this->activeSubscription;
        if (! $current) {
            return true; // No active subscription to cancel
        }

        return $this->cancelSpecificSubscription($current, $cancelledBy, $reason, $immediately);
    }

    protected function cancelSpecificSubscription(LocalSubscription $subscription, string $cancelledBy, ?string $reason, bool $immediately): bool
    {
        $updateData = [
            'cancelled_by' => $cancelledBy,
            'cancelled_reason' => $reason ?: 'Cancelled locally',
            'is_recurring' => false,
        ];

        if ($immediately || now()->gte($subscription->ends_at)) {
            // Immediate cancellation or subscription already expired
            $updateData['status'] = 'cancelled';
            $updateData['ends_at'] = now();
        } else {
            // Cancel at period end - keep access until ends_at
            $updateData['status'] = 'cancelled';
            // Keep existing ends_at so user retains access
        }

        $subscription->update($updateData);

        Log::info('Local subscription cancelled', [
            'user_id' => $this->id,
            'subscription_id' => $subscription->id,
            'cancelled_by' => $cancelledBy,
            'immediately' => $immediately,
        ]);

        return true;
    }

    public function calculateProrationCredit(LocalSubscription $sub): float
    {
        if (! $sub->ends_at || ! $sub->amount_paid) {
            return 0;
        }

        $remainingDays = now()->diffInDays($sub->ends_at, false);
        $totalDays = $sub->plan->getBillingDurationInDays();

        if ($remainingDays <= 0 || $totalDays <= 0) {
            return 0;
        }

        return round(($sub->amount_paid / $totalDays) * $remainingDays, 2);
    }

    public function isSubscribed(): bool
    {
        return $this->activeSubscription !== null;
    }

    public function isSubscribedTo(Plan $plan): bool
    {
        $sub = $this->activeSubscription;

        return $sub && $sub->plan_id === $plan->id;
    }

    public function OnTrial(): bool
    {
        $sub = $this->activeSubscription;

        return $sub && $sub->status === 'trialing' && now()->lt($sub->trial_ends_at);
    }

    public function isInGrace(): bool
    {
        $sub = $this->activeSubscription;

        return $sub && $sub->status === 'grace' && now()->lt($sub->grace_ends_at);
    }

    public function isCancelled(): bool
    {
        $sub = $this->activeSubscription;

        return $sub && $sub->status === 'cancelled' && now()->lt($sub->ends_at);
    }

    public function hasExpired(): bool
    {
        $sub = $this->subscriptions()
            ->whereNotNull('ends_at')
            ->latest('ends_at')->first();

        return $sub && now()->gt($sub->grace_ends_at ?? $sub->ends_at);
    }

    public function currentPlan(): ?Plan
    {
        return $this->activeSubscription?->plan;
    }
}
