<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Plan extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'is_active',
        'is_best_deal',
        'original_price',
        'discount_price',
        'currency',
        'trial_period',
        'trial_interval',
        'invoice_period',
        'invoice_interval',
        'grace_period',
        'grace_interval',
        'stripe_price_id',
        'appstore_product_id',
        'playstore_product_id',
        'max_devices',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'string',
            'is_active' => 'boolean',
            'is_best_deal' => 'boolean',
            'original_price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'trial_period' => 'integer',
            'invoice_period' => 'integer',
            'grace_period' => 'integer',
            'max_devices' => 'integer',
        ];
    }

    /**
     * Scope a query to only include active plans.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', 1);
    }

    #[Scope]
    protected function regular(Builder $query): void
    {
        $query->where('type', 'regular');
    }

    #[Scope]
    protected function dedicated(Builder $query): void
    {
        $query->where('type', 'dedicated');
    }

    public function subscriptions()
    {
        return $this->hasMany(LocalSubscription::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getTrialEndsAt(): ?Carbon
    {
        return $this->trial_period > 0
            ? now()->add($this->trial_interval, $this->trial_period)
            : null;
    }

    public function getGraceEndsAt(Carbon $endsAt): ?Carbon
    {
        return $this->grace_period > 0
            ? $endsAt->copy()->add($this->grace_interval, $this->grace_period)
            : null;
    }

    public function getBillingDurationInDays(): int
    {
        return match ($this->invoice_interval) {
            'day' => $this->invoice_period,
            'week' => $this->invoice_period * 7,
            'month' => $this->invoice_period * 30,
            'year' => $this->invoice_period * 365,
        };
    }

    public function getBillingLabel()
    {
        $intervals = [
            'day' => 'Daily',
            'week' => 'Weekly',
            'month' => 'Monthly',
            'year' => 'Yearly',
        ];

        $period = $this->invoice_period;
        $interval = $this->invoice_interval;
        $label = $intervals[$interval] ?? ucfirst($interval);

        if ($period > 1) {
            // Pluralize the label based on the period
            $pluralLabel = Str::plural($label, $period);

            return "Every {$period} {$pluralLabel}";
        }

        return $label;
    }

    public function getCurrencySymbol()
    {
        $symbols = [
            'USD' => '$',
            'PKR' => '₨',
            'EUR' => '€',
            'GBP' => '£',
            'INR' => '₹',
            'JPY' => '¥',
            'CNY' => '¥',
            'AUD' => 'A$',
            'CAD' => 'C$',
            'CHF' => 'CHF',
            'RUB' => '₽',
        ];

        return $symbols[$this->currency] ?? $this->currency;
    }
}
