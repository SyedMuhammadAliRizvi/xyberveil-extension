<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use App\Traits\HasSubscriptions;
use App\Traits\HasTrial;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasSlug, HasSubscriptions, HasTrial, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'role',
        'linux_username',
        'email_verified_at',
        'last_login',
        'banned_at',
        'ban_reason',
        'apple_id',
        'google_id',
        'xyberveil_token_id',
        'xyberveil_api_token',
        'paddle_id',
        'remember_token',
        'app_account_token',
        'trial_started_at',
        'trial_ends_at',
        'trial_used',
        'trial_expired_notification_sent',
        'custom_device_limit',
        'dashboard_layout',
        'dashboard_widget_sizes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
            'banned_at' => 'datetime',
            'last_login' => 'datetime',
            'trial_started_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'trial_used' => 'boolean',
            'trial_expired_notification_sent' => 'boolean',
            'custom_device_limit' => 'integer',
            'dashboard_layout' => 'array',
            'dashboard_widget_sizes' => 'array',
        ];
    }

    public function hasProxyAccess(): bool
    {
        return $this->linux_username !== null;
    }

    public function isProxyPending(): bool
    {
        return $this->hasProxyAccess() && $this->email_verified_at === null;
    }

    public function isProxyActive(): bool
    {
        return $this->hasProxyAccess()
            && $this->email_verified_at !== null
            && ! $this->isBanned();
    }

    public function proxyStatus(): ?string
    {
        if (! $this->hasProxyAccess()) {
            return null;
        }

        if ($this->isBanned()) {
            return 'banned';
        }

        if ($this->isProxyPending()) {
            return 'pending';
        }

        return 'active';
    }

    public function canConnectProxy(): bool
    {
        if ($this->isSubscribed()) {
            return true;
        }

        return $this->isOnTrial();
    }

    public static function generateLinuxUsername(int $userId): string
    {
        return sprintf('px%06d', $userId);
    }

    public function proxyExtensionDevices()
    {
        return $this->hasMany(ProxyExtensionDevice::class);
    }

    public function proxyCredentials()
    {
        return $this->hasMany(ProxyCredential::class);
    }

    public function proxySessions()
    {
        return $this->hasMany(ProxySession::class);
    }

    public function proxyRefreshTokens()
    {
        return $this->hasMany(ProxyRefreshToken::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function deviceLimit(): int
    {
        if ($this->custom_device_limit !== null) {
            return $this->custom_device_limit;
        }

        $subscription = $this->activeSubscription()->with('plan')->first();
        if ($subscription && $subscription->plan && $subscription->plan->max_devices !== null) {
            return $subscription->plan->max_devices;
        }

        return (int) config('app.free_user_max_devices', 1);
    }

    public function activeDevicesCount(): int
    {
        return $this->devices()->whereNull('revoked_at')->where('is_blocked', false)->count();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function isBanned(): bool
    {
        return ! is_null($this->banned_at);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasAnyRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }
}
