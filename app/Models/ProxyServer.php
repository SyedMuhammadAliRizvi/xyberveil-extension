<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProxyServer extends Model
{
    protected $fillable = [
        'infra_server_id',
        'name',
        'country',
        'flag',
        'ip',
        'proxy_port',
        'socks_port',
        'agent_port',
        'agent_key',
        'ssh_user',
        'ssh_password',
        'ssh_port',
        'is_active',
        'setup_status',
        'setup_log',
    ];

    protected $hidden = [
        'ssh_password',
        'agent_key',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'ssh_password' => 'encrypted',
        ];
    }

    public function hasStoredSshPassword(): bool
    {
        return filled($this->getRawOriginal('ssh_password'));
    }

    public function sshPasswordDecryptFailed(): bool
    {
        return $this->hasStoredSshPassword() && $this->sshPassword() === null;
    }

    public function sshPassword(): ?string
    {
        if (! $this->hasStoredSshPassword()) {
            return null;
        }

        try {
            return $this->ssh_password;
        } catch (DecryptException) {
            return null;
        }
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ProxySession::class);
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(ProxyCredential::class);
    }
}
