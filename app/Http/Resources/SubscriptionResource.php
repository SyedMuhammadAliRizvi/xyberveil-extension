<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'plan' => $this->whenLoaded('plan', fn () => new PlanResource($this->plan)),

            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'trial_ends_at' => $this->trial_ends_at,
            'grace_ends_at' => $this->grace_ends_at,

            'amount_paid' => $this->amount_paid,
            'currency' => $this->currency,

            'status' => $this->status,
            'is_recurring' => $this->is_recurring,
            'provider' => $this->provider,

            'cancelled_by' => $this->cancelled_by,
            'cancelled_reason' => $this->cancelled_reason,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
