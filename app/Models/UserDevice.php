<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\PersonalAccessToken;

class UserDevice extends Model
{
    protected $fillable = [
        'user_id',
        'token_id',
        'name',
        'model',
        'platform',
        'os',
        'device_type',
        'app_version',
        'browser',
        'device_fingerprint',
        'ip_address',
        'city',
        'country',
        'country_code',
        'is_blocked',
        'blocked_at',
        'blocked_reason',
        'revoked_at',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'is_blocked' => 'boolean',
            'blocked_at' => 'datetime',
            'revoked_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(PersonalAccessToken::class, 'token_id');
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }

    public function isRevoked(): bool
    {
        return ! is_null($this->revoked_at);
    }

    public function isActive(): bool
    {
        return ! $this->isBlocked() && ! $this->isRevoked();
    }

    /** @return array<string, string> */
    public function platformIcon(): string
    {
        return match ($this->platform) {
            'android' => 'mdi:android',
            'ios' => 'mdi:apple-ios',
            'macos' => 'mdi:apple',
            'windows' => 'mdi:microsoft-windows',
            'web' => 'mdi:web',
            default => 'mdi:devices',
        };
    }
}
