<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProxySession extends Model
{
    protected $fillable = [
        'user_id',
        'proxy_server_id',
        'started_at',
        'ended_at',
        'client_ip',
        'duration_seconds',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
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

    public function isActive(): bool
    {
        return $this->ended_at === null;
    }
}
