<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleNotification extends Model
{
    protected $fillable = [
        'notification_type',
        'package_name',
        'event_time_millis',
        'subscription_id',
        'purchase_token',
        'payload',
        'subscription_details',
        'obfuscated_external_account_id',
        'order_id',
        'processed',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'subscription_details' => 'array',
        'processed' => 'boolean',
        'processed_at' => 'datetime',
    ];
}
