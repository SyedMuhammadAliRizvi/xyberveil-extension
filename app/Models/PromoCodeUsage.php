<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeUsage extends Model
{
    protected $fillable = [
        'promo_code_id',
        'user_id',
        'local_subscription_id',
        'discount_applied',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_applied' => 'decimal:2',
            'used_at' => 'datetime',
        ];
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(LocalSubscription::class, 'local_subscription_id');
    }
}
