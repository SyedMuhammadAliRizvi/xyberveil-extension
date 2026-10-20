<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServerMapResource extends JsonResource
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
            'image' => $this->flag_url ?: ($this->getFirstMediaUrl('image') ?: null),
            'name' => $this->name,
            'country_code' => $this->country_code,
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'platforms' => [
                'android' => (bool) $this->android,
                'ios' => (bool) $this->ios,
                'macos' => (bool) $this->macos,
                'windows' => (bool) $this->windows,
            ],
            'gaming' => (bool) $this->gaming,
            'streaming' => (bool) $this->streaming,
            'type' => $this->type,
            'status' => $this->status,
            'connected_users' => $this->connected_users ?? 0,
            'created_at' => $this->created_at,
            'sub_servers' => $this->whenLoaded('subServers', fn () => SubServerResource::collection($this->subServers)),
        ];
    }
}
