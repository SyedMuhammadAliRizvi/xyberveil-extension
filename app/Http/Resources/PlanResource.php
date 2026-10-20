<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,

            'original_price' => $this->original_price,
            'discount_price' => $this->discount_price,

            'invoice_period' => $this->invoice_period,
            'invoice_interval' => $this->invoice_interval,

            'trial_period' => $this->trial_period,
            'trial_interval' => $this->trial_interval,

            'paddle_price_id' => $this->paddle_price_id,
            'appstore_product_id' => $this->appstore_product_id,

            'is_active' => $this->is_active,
            'is_best_deal' => $this->is_best_deal,

            'created_at' => $this->created_at,
        ];
    }
}
