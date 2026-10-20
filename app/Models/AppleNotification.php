<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppleNotification extends Model
{
    protected $fillable = [
        'notification_type',
        'subtype',
        'notification_uuid',
        'version',
        'signed_date',
        'payload',
        'transaction_info',
        'renewal_info',
        'app_account_token',
        'original_transaction_id',
        'transaction_id',
        'product_id',
        'processed',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'transaction_info' => 'array',
        'renewal_info' => 'array',
        'signed_date' => 'datetime',
        'processed_at' => 'datetime',
        'processed' => 'boolean',
    ];
}
