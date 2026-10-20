<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProxyCredential extends Model
{
    protected $fillable = [
        'user_id',
        'proxy_server_id',
        'proxy_password',
        'synced',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'synced' => 'boolean',
            'synced_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(ProxyServer::class, 'proxy_server_id');
    }
}
