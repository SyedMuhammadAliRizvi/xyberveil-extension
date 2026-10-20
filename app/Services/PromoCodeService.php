<?php

namespace App\Services;

use App\Models\LocalSubscription;
use App\Models\PromoCode;
use App\Models\PromoCodeUsage;
use App\Models\User;

class PromoCodeService
{
    /**
     * Validate a promo code for a given plan and user.
     * Returns the PromoCode on success, or throws a \RuntimeException with the reason.
     */
    public function validate(string $code, int $planId, ?User $user = null): PromoCode
    {
        $promo = PromoCode::where('code', strtoupper(trim($code)))->first();

        if (! $promo) {
            throw new \RuntimeException('Promo code not found.');
        }

        if (! $promo->isValid()) {
            throw new \RuntimeException('This promo code is expired or inactive.');
        }

        if (! $promo->appliesToPlan($planId)) {
            throw new \RuntimeException('This promo code does not apply to the selected plan.');
        }

        if ($user && $promo->usages()->where('user_id', $user->id)->exists()) {
            throw new \RuntimeException('You have already used this promo code.');
        }

        return $promo;
    }

    /**
     * Calculate the discounted price for a given plan price and promo code.
     */
    public function calculateDiscount(PromoCode $promo, float $originalPrice): array
    {
        if ($promo->min_purchase_amount !== null && $originalPrice < (float) $promo->min_purchase_amount) {
            throw new \RuntimeException(
                "Minimum purchase amount of {$promo->min_purchase_amount} required for this code."
            );
        }

        $discountAmount = $promo->discount_type === 'percentage'
            ? round($originalPrice * ((float) $promo->discount_value / 100), 2)
            : min((float) $promo->discount_value, $originalPrice);

        $finalPrice = max(0, round($originalPrice - $discountAmount, 2));

        return [
            'original_price' => $originalPrice,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
        ];
    }

    /**
     * Record that a promo code was used for a subscription.
     */
    public function recordUsage(PromoCode $promo, User $user, LocalSubscription $subscription, float $discountAmount): void
    {
        PromoCodeUsage::create([
            'promo_code_id' => $promo->id,
            'user_id' => $user->id,
            'local_subscription_id' => $subscription->id,
            'discount_applied' => $discountAmount,
            'used_at' => now(),
        ]);

        $promo->increment('used_count');
    }
}
