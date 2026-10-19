<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubServer extends Model
{
    protected $fillable = ['server_id', 'name', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function isActive()
    {
        return $this->status == 1;
    }
}
