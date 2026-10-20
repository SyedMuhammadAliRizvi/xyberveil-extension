<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Server extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'android', 'ios', 'macos', 'windows', 'gaming', 'streaming', 'longitude', 'latitude', 'type', 'status', 'country_code', 'flag_url'];

    protected $casts = [
        'android' => 'boolean',
        'ios' => 'boolean',
        'macos' => 'boolean',
        'windows' => 'boolean',
        'gaming' => 'boolean',
        'streaming' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->useDisk('media')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg']);
    }

    public function subServers()
    {
        return $this->hasMany(SubServer::class);
    }

    public function activeSubServer()
    {
        return $this->hasOne(SubServer::class)->where('status', true);
    }

    public function isPremium()
    {
        return $this->type === 'premium';
    }

    public function isActive()
    {
        return $this->status === 1;
    }
}
