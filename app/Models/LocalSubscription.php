<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocalSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'grace_ends_at',
        'amount_paid',
        'currency',
        'status',
        'cancelled_by',
        'cancelled_reason',
        'is_recurring',
        'promo_code_id',
        'discount_applied',
        'previous_subscription_id',
        'proration_meta',
        'provider',
        'appstore_original_transaction_id',
        'appstore_transaction_id',
        'appstore_product_id',
        'googleplay_order_id',
        'googleplay_purchase_token',
        'googleplay_product_id',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'grace_ends_at' => 'datetime',
            'is_recurring' => 'boolean',
            'discount_applied' => 'decimal:2',
            'proration_meta' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function previous(): BelongsTo
    {
        return $this->belongsTo(LocalSubscription::class, 'previous_subscription_id');
    }

    public function nextUpgrade()
    {
        return $this->hasOne(LocalSubscription::class, 'previous_subscription_id');
    }

    public function isTrialing()
    {
        return $this->status === 'trialing' &&
            $this->trial_ends_at &&
            $this->trial_ends_at->isFuture();
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled' && now()->lt($this->ends_at);
    }

    public function isActive()
    {
        return $this->status === 'active' && now()->lt($this->ends_at);
    }

    public function isInGrace()
    {
        return $this->status === 'grace' && now()->lt($this->grace_ends_at);
    }

    public function isExpired()
    {
        return
            $this->status === 'expired'
            || ($this->status === 'cancelled' && now()->gte($this->ends_at))
            || now()->gt($this->grace_ends_at ?? $this->ends_at);
    }
}
