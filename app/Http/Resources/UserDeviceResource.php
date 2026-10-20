<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentToken = $request->user()->currentAccessToken();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'model' => $this->model,
            'platform' => $this->platform,
            'os' => $this->os,
            'device_type' => $this->device_type,
            'app_version' => $this->app_version,
            'browser' => $this->browser,
            'ip_address' => $this->ip_address,
            'city' => $this->city,
            'country' => $this->country,
            'country_code' => $this->country_code,
            'is_blocked' => $this->is_blocked,
            'is_revoked' => $this->isRevoked(),
            'is_active' => $this->isActive(),
            'is_current' => $currentToken && $this->token_id === $currentToken->id,
            'last_seen_at' => $this->last_seen_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
