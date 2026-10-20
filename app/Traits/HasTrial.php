<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasTrial
{
    /**
     * Start the trial period for the user
     *
     * @param  int  $days  Trial duration in days (default: 7)
     */
    public function startTrial(int $days = 7): bool
    {
        if ($this->hasUsedTrial()) {
            return false;
        }

        $this->update([
            'trial_started_at' => now(),
            'trial_ends_at' => now()->addDays($days),
            'trial_used' => true,
            'trial_expired_notification_sent' => false,
        ]);

        return true;
    }

    /**
     * Check if user has used their trial
     */
    public function hasUsedTrial(): bool
    {
        return (bool) $this->trial_used;
    }

    /**
     * Check if user is currently on trial
     */
    public function isOnTrial(): bool
    {
        return $this->hasUsedTrial()
            && $this->trial_started_at
            && $this->trial_ends_at
            && now()->between($this->trial_started_at, $this->trial_ends_at);
    }

    /**
     * Check if trial has expired
     */
    public function hasTrialExpired(): bool
    {
        return $this->hasUsedTrial()
            && $this->trial_ends_at
            && now()->isAfter($this->trial_ends_at);
    }

    /**
     * Get remaining trial days
     */
    public function trialDaysRemaining(): int
    {
        if (! $this->isOnTrial()) {
            return 0;
        }

        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    /**
     * Get remaining trial hours
     */
    public function trialHoursRemaining(): int
    {
        if (! $this->isOnTrial()) {
            return 0;
        }

        return max(0, now()->diffInHours($this->trial_ends_at, false));
    }

    /**
     * Get trial progress percentage (0-100)
     */
    public function trialProgressPercentage(): float
    {
        if (! $this->hasUsedTrial() || ! $this->trial_started_at || ! $this->trial_ends_at) {
            return 0.0;
        }

        $totalDuration = $this->trial_started_at->diffInSeconds($this->trial_ends_at);
        $elapsed = $this->trial_started_at->diffInSeconds(now());

        if ($totalDuration <= 0) {
            return 100.0;
        }

        return min(100.0, max(0.0, ($elapsed / $totalDuration) * 100));
    }

    /**
     * Check if trial is ending soon (within specified hours)
     *
     * @param  int  $hours  Hours before expiry to consider "ending soon"
     */
    public function isTrialEndingSoon(int $hours = 24): bool
    {
        if (! $this->isOnTrial()) {
            return false;
        }

        return $this->trialHoursRemaining() <= $hours;
    }

    /**
     * Extend trial by specified days
     */
    public function extendTrial(int $days): bool
    {
        if (! $this->hasUsedTrial() || ! $this->trial_ends_at) {
            return false;
        }

        $this->update([
            'trial_ends_at' => $this->trial_ends_at->addDays($days),
            'trial_expired_notification_sent' => false,
        ]);

        return true;
    }

    /**
     * End trial immediately
     */
    public function endTrial(): bool
    {
        if (! $this->hasUsedTrial()) {
            return false;
        }

        $this->update([
            'trial_ends_at' => now(),
        ]);

        return true;
    }

    /**
     * Reset trial (admin only - allows user to trial again)
     */
    public function resetTrial(): bool
    {
        $this->update([
            'trial_started_at' => null,
            'trial_ends_at' => null,
            'trial_used' => false,
            'trial_expired_notification_sent' => false,
        ]);

        return true;
    }

    /**
     * Mark trial expiry notification as sent
     */
    public function markTrialExpiryNotificationSent(): bool
    {
        if (! $this->hasUsedTrial()) {
            return false;
        }

        $this->update(['trial_expired_notification_sent' => true]);

        return true;
    }

    /**
     * Check if trial expiry notification has been sent
     */
    public function hasTrialExpiryNotificationBeenSent(): bool
    {
        return $this->trial_expired_notification_sent;
    }

    /**
     * Get trial status as a string
     */
    public function getTrialStatus(): string
    {
        if (! $this->hasUsedTrial()) {
            return 'not_started';
        }

        if ($this->isOnTrial()) {
            return 'active';
        }

        if ($this->hasTrialExpired()) {
            return 'expired';
        }

        return 'unknown';
    }

    /**
     * Get trial status with human-readable description
     */
    public function getTrialStatusWithDescription(): array
    {
        $status = $this->getTrialStatus();

        $descriptions = [
            'not_started' => 'Trial not started',
            'active' => 'Trial active ('.$this->trialDaysRemaining().' days remaining)',
            'expired' => 'Trial expired',
            'unknown' => 'Trial status unknown',
        ];

        return [
            'status' => $status,
            'description' => $descriptions[$status] ?? 'Unknown status',
            'days_remaining' => $this->trialDaysRemaining(),
            'hours_remaining' => $this->trialHoursRemaining(),
            'progress_percentage' => $this->trialProgressPercentage(),
        ];
    }

    /**
     * Scope to get users on active trial
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeOnTrial($query)
    {
        return $query->where('trial_used', true)
            ->whereNotNull('trial_started_at')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now());
    }

    /**
     * Scope to get users with expired trials
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeTrialExpired($query)
    {
        return $query->where('trial_used', true)
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<=', now());
    }

    /**
     * Scope to get users who haven't used trial yet
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeTrialNotUsed($query)
    {
        return $query->where('trial_used', false);
    }

    /**
     * Scope to get users whose trial is ending soon
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeTrialEndingSoon($query, int $hours = 24)
    {
        return $query->where('trial_used', true)
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now())
            ->where('trial_ends_at', '<=', now()->addHours($hours));
    }
}
