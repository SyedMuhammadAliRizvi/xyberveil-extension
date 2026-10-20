<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProxyExtensionDevice extends Model
{
    protected $fillable = [
        'user_id',
        'device_key',
        'device_type',
        'browser_name',
        'browser_version',
        'os_name',
        'os_version',
        'extension_version',
        'ip_address',
        'city',
        'country',
        'country_code',
        'timezone',
        'is_allowed',
        'last_seen',
    ];

    protected function casts(): array
    {
        return [
            'is_allowed' => 'boolean',
            'last_seen' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
