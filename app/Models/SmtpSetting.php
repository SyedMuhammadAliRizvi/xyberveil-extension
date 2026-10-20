<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    protected $fillable = [
        'host',
        'port',
        'username',
        'password',
        'address',
        'name',
    ];

    protected $hidden = [
        'password',
    ];
}
