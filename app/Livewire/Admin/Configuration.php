<?php

namespace App\Livewire\Admin;

use App\Models\Option;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Livewire\Component;

class Configuration extends Component
{
    public $navTab = 'email';

    public $host;

    public $port;

    public $username;

    public $password;

    public $address;

    public $name;

    public $tos;

    public $privacy_policy;

    public $refund_policy;

    // Monitoring API token
    public $monitor_api_token;

    public function mount(): void
    {
        $settings = SmtpSetting::first();
        $this->host = $settings->host ?? config('mail.mailers.smtp.host');
        $this->port = $settings->port ?? config('mail.mailers.smtp.port');
        $this->username = $settings->username ?? config('mail.mailers.smtp.username');
        $this->password = $settings->password ?? config('mail.mailers.smtp.password');
        $this->address = $settings->address ?? config('mail.from.address');
        $this->name = $settings->name ?? config('mail.from.name');

        $this->tos = Option::where('key', 'tos')->first()->value ?? '';
        $this->privacy_policy = Option::where('key', 'privacy_policy')->first()->value ?? '';
        $this->refund_policy = Option::where('key', 'refund_policy')->first()->value ?? '';

        // Load monitoring API token
        $this->monitor_api_token = Option::where('key', 'monitor_api_token')->first()->value ?? null;
    }

    public function emailConfig()
    {
        $this->validate([
            'host' => 'required|string|max:255',
            'port' => 'required|integer',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'address' => 'required|email|max:255',
            'name' => 'required|string|max:255',
        ]);

        SmtpSetting::updateOrCreate(
            ['id' => 1],
            [
                'host' => $this->host,
                'port' => $this->port,
                'username' => $this->username,
                'password' => $this->password,
                'address' => $this->address,
                'name' => $this->name,
            ]
        );

        Artisan::call('config:clear');
        Artisan::call('queue:restart');

        $this->dispatch('sweetToast', message: 'Mail settings updated successfully.', type: 'success');
    }

    public function policyConfig()
    {
        $this->validate([
            'refund_policy' => 'required|string',
            'tos' => 'required|string',
            'privacy_policy' => 'required|string',
        ]);

        Option::updateOrCreate(
            ['key' => 'refund_policy'],
            ['value' => $this->refund_policy]
        );

        Option::updateOrCreate(
            ['key' => 'tos'],
            ['value' => $this->tos]
        );

        Option::updateOrCreate(
            ['key' => 'privacy_policy'],
            ['value' => $this->privacy_policy]
        );

        $this->dispatch('sweetToast', message: 'Policies settings updated successfully.', type: 'success');
    }

    public function generateMonitoringToken(): void
    {
        $newToken = 'monitor_'.Str::random(60);

        Option::updateOrCreate(
            ['key' => 'monitor_api_token'],
            ['value' => $newToken]
        );

        $this->monitor_api_token = $newToken;

        $this->dispatch('sweetToast', message: 'Monitoring API token generated successfully.', type: 'success');
    }

    public function regenerateMonitoringToken(): void
    {
        $newToken = 'monitor_'.Str::random(60);

        Option::updateOrCreate(
            ['key' => 'monitor_api_token'],
            ['value' => $newToken]
        );

        $this->monitor_api_token = $newToken;

        $this->dispatch('sweetToast', message: 'Monitoring API token regenerated successfully.', type: 'success');
    }

    public function render()
    {
        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.configuration')
            ->extends('layouts.tailadmin')
            ->section('content');
    }

    public function updatedUsername(): void
    {
        $this->address = $this->username;
    }
}
