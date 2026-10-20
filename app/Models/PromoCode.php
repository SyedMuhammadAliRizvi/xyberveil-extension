<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'min_purchase_amount',
        'applicable_plan_ids',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'min_purchase_amount' => 'decimal:2',
            'applicable_plan_ids' => 'array',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function usages()
    {
        return $this->hasMany(PromoCodeUsage::class);
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        $now = now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return true;
    }

    public function appliesToPlan(int $planId): bool
    {
        if ($this->applicable_plan_ids === null) {
            return true;
        }

        return in_array($planId, $this->applicable_plan_ids);
    }
}
