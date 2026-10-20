<?php

namespace App\Mail;

use App\Models\LocalSubscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class BillingFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public LocalSubscription $subscription,
        public string $failureReason,
        public bool $hasGracePeriod = false,
        public ?Carbon $gracePeriodEndsAt = null
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->hasGracePeriod
            ? 'Action Required: Payment Issue with Your Subscription'
            : 'Urgent: Update Your Payment Method';

        return new Envelope(
            subject: $subject,
            tags: ['billing-failed', 'subscription', $this->subscription->provider],
            metadata: [
                'user_id' => $this->user->id,
                'subscription_id' => $this->subscription->id,
                'plan_name' => $this->subscription->plan->name,
                'has_grace_period' => $this->hasGracePeriod,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.billing-failed',
            with: [
                'userName' => $this->user->name ?? 'Valued Customer',
                'planName' => $this->subscription->plan->name,
                'currency' => strtoupper($this->subscription->currency),
                'amount' => number_format($this->subscription->amount_paid, 2),
                'failureReason' => $this->failureReason,
                'hasGracePeriod' => $this->hasGracePeriod,
                'gracePeriodEndsAt' => $this->gracePeriodEndsAt,
                'subscriptionEndsAt' => $this->subscription->ends_at,
                'provider' => ucfirst($this->subscription->provider),
                'isAppStore' => $this->subscription->provider === 'appstore',
                'isPaddle' => $this->subscription->provider === 'paddle',
                'updatePaymentUrl' => $this->getUpdatePaymentUrl(),
                'supportEmail' => 'support@safeprovpn.com',
                'daysUntilExpiry' => $this->getDaysUntilExpiry(),
            ],
        );
    }

    private function getUpdatePaymentUrl(): string
    {
        return 'https://apps.apple.com/account/billing';
    }

    private function getDaysUntilExpiry(): int
    {
        $expiryDate = $this->hasGracePeriod && $this->gracePeriodEndsAt
            ? $this->gracePeriodEndsAt
            : $this->subscription->ends_at;

        return max(0, now()->diffInDays($expiryDate, false));
    }
}
