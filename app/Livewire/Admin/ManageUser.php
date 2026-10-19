<?php

namespace App\Livewire\Admin;

use App\Jobs\SendEmailVerification;
use App\Jobs\SendPasswordReset;
use App\Models\BlockedIp;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\ProxyMonitorService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUser extends Component
{
    use WithPagination;

    public User $user;

    public $plans;

    public $selectedPlan;

    public $customDeviceLimit = null;

    public ?string $generatedPassword = null;

    public int $sessionsPerPage = 10;

    public string $activeTab = 'vpn';

    public function mount(User $user)
    {
        $this->user = $user->load($this->deviceRelationsFor($user));
        $this->plans = Plan::all();
        $this->customDeviceLimit = $user->custom_device_limit;

        if ($user->hasProxyAccess() && request()->query('tab') === 'proxy') {
            $this->activeTab = 'proxy';
        }
    }

    /**
     * @return list<string>
     */
    private function deviceRelationsFor(User $user): array
    {
        $relations = [
            'localSubscriptions.plan',
            'activeSubscription.plan',
            'devices',
        ];

        if ($user->hasProxyAccess()) {
            $relations[] = 'proxyExtensionDevices';
        }

        return $relations;
    }

    private function refreshDeviceRelations(): void
    {
        $this->user->refresh()->load($this->deviceRelationsFor($this->user));
    }

    public function blockDevice(int $deviceId, string $reason = ''): void
    {
        $device = UserDevice::where('id', $deviceId)->where('user_id', $this->user->id)->firstOrFail();
        $device->update([
            'is_blocked' => true,
            'blocked_at' => now(),
            'blocked_reason' => $reason ?: 'Blocked by admin',
        ]);

        if ($device->token_id) {
            $device->token()->delete();
            $device->update(['token_id' => null]);
        }

        $this->dispatch('sweetAlert', title: 'Blocked', message: 'Device has been blocked.', type: 'success');
        $this->refreshDeviceRelations();
    }

    public function unblockDevice(int $deviceId): void
    {
        $device = UserDevice::where('id', $deviceId)->where('user_id', $this->user->id)->firstOrFail();
        $device->update(['is_blocked' => false, 'blocked_at' => null, 'blocked_reason' => null]);
        $this->dispatch('sweetAlert', title: 'Unblocked', message: 'Device has been unblocked.', type: 'success');
        $this->refreshDeviceRelations();
    }

    public function revokeDevice(int $deviceId): void
    {
        $device = UserDevice::where('id', $deviceId)->where('user_id', $this->user->id)->firstOrFail();

        if ($device->token_id) {
            $device->token()->delete();
        }

        $device->update(['revoked_at' => now(), 'token_id' => null]);
        $this->dispatch('sweetAlert', title: 'Revoked', message: 'Device access has been revoked.', type: 'success');
        $this->refreshDeviceRelations();
    }

    public function removeDevice(int $deviceId): void
    {
        $device = UserDevice::where('id', $deviceId)->where('user_id', $this->user->id)->firstOrFail();

        if ($device->token_id) {
            $device->token()->delete();
        }

        $device->delete();
        $this->dispatch('sweetAlert', title: 'Removed', message: 'Device record removed.', type: 'success');
        $this->refreshDeviceRelations();
    }

    public function blockDeviceIp(int $deviceId, string $reason = ''): void
    {
        $device = UserDevice::where('id', $deviceId)->where('user_id', $this->user->id)->firstOrFail();

        if (! $device->ip_address) {
            $this->dispatch('sweetAlert', title: 'Error', message: 'No IP address on this device.', type: 'error');

            return;
        }

        BlockedIp::updateOrCreate(
            ['ip_address' => $device->ip_address],
            [
                'reason' => $reason ?: "Blocked via device management (device #{$deviceId})",
                'blocked_by' => Auth::id(),
            ]
        );

        Cache::forget("blocked_ip:{$device->ip_address}");
        $this->dispatch('sweetAlert', title: 'IP Blocked', message: "IP {$device->ip_address} has been blocked.", type: 'success');
        $this->refreshDeviceRelations();
    }

    public function unblockDeviceIp(string $ip): void
    {
        $blocked = BlockedIp::where('ip_address', $ip)->first();

        if (! $blocked) {
            $this->dispatch('sweetAlert', title: 'Not Found', message: 'IP is not in the blocked list.', type: 'info');

            return;
        }

        Cache::forget("blocked_ip:{$ip}");
        $blocked->delete();

        $this->dispatch('sweetAlert', title: 'IP Unblocked', message: "IP {$ip} has been unblocked.", type: 'success');
        $this->refreshDeviceRelations();
    }

    public function revokeAllDevices(): void
    {
        $devices = $this->user->devices()->get();

        foreach ($devices as $device) {
            if ($device->token_id) {
                $device->token()->delete();
            }
            $device->update(['revoked_at' => now(), 'token_id' => null]);
        }

        $this->dispatch('sweetAlert', title: 'All Revoked', message: 'All device sessions have been revoked.', type: 'success');
        $this->refreshDeviceRelations();
    }

    public function addPlan()
    {
        $this->validate([
            'selectedPlan' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($this->selectedPlan);
        if (! $plan) {
            return $this->dispatch('sweetAlert', title: 'Error', message: 'Plan not found.', type: 'error');
        }

        if ($this->user->isSubscribed()) {
            return $this->dispatch('sweetAlert', title: 'Error', message: 'User already has an active subscription.', type: 'error');
        }

        $this->user->subscribeTo($plan);
        $this->dispatch('sweetAlert', title: 'Success', message: 'Plan added successfully.', type: 'success');
        $this->user->refresh();
    }

    public function cancelSubscription($reason, $immediately = false)
    {
        if (! $this->user->isSubscribed()) {
            $this->dispatch('sweetAlert', title: 'Error', message: 'No active subscription found.', type: 'error');

            return;
        }

        $this->user->cancelSubscription(null, 'system', $reason, $immediately);
        $this->dispatch('sweetAlert', title: 'Success', message: 'Subscription cancelled successfully.', type: 'success');
    }

    public function verifyEmailManually()
    {
        $this->user->update(['email_verified_at' => now()]);
        $this->dispatch('sweetAlert', title: 'Success', message: 'Email verified manually.', type: 'success');
        $this->user->refresh();
    }

    public function resendVerificationEmail()
    {
        if (! $this->user->hasVerifiedEmail()) {
            SendEmailVerification::dispatch($this->user)->delay(now()->addSeconds(5));
            $this->dispatch('sweetAlert', title: 'Success', message: 'Verification email resent.', type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Info', message: 'Email is already verified.', type: 'info');
        }
    }

    public function sendPasswordReset()
    {
        $token = Password::createToken($this->user);

        SendPasswordReset::dispatch($this->user, $token)->delay(now()->addSeconds(5));
        $this->dispatch('sweetAlert', title: 'Success', message: 'Password reset email sent.', type: 'success');
    }

    public function banUser($reason = null)
    {
        if ($this->user->isBanned()) {
            $this->dispatch('sweetAlert', title: 'Info', message: 'User is already banned.', type: 'info');

            return;
        }

        if (! $this->user->isBanned()) {
            $this->user->update(['banned_at' => now(), 'ban_reason' => $reason]);
            $this->user->tokens()->delete();
            $this->dispatch('sweetAlert', title: 'Success', message: 'User banned successfully.', type: 'success');
        }
        $this->user->refresh();
    }

    public function unbanUser()
    {
        if ($this->user->isBanned()) {
            $this->user->update(['banned_at' => null, 'ban_reason' => null]);
            $this->dispatch('sweetAlert', title: 'Success', message: 'User unbanned successfully.', type: 'success');
        }
        $this->user->refresh();
    }

    public function setCustomDeviceLimit(): void
    {
        $this->validate([
            'customDeviceLimit' => 'nullable|integer|min:1|max:255',
        ]);

        $this->user->update(['custom_device_limit' => $this->customDeviceLimit ?: null]);
        $this->dispatch('sweetAlert', title: 'Success', message: 'Device limit updated successfully.', type: 'success');
        $this->refreshDeviceRelations();
    }

    public function removeProxyExtensionDevice(int $deviceId): void
    {
        try {
            app(ProxyMonitorService::class)->deleteUserDevice($this->user->id, $deviceId);
            $this->dispatch('sweetAlert', title: 'Removed', message: 'Extension device removed — browser will disconnect and log out.', type: 'success');
            $this->refreshDeviceRelations();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    public function setExtensionDeviceAllowed(int $deviceId, bool $allowed): void
    {
        try {
            app(ProxyMonitorService::class)->updateUserDevice($this->user->id, $deviceId, $allowed);
            $message = $allowed
                ? 'Extension device allowed — user can log in from this browser.'
                : 'Extension device blocked — browser will be logged out and cannot sign in.';
            $this->dispatch('sweetAlert', title: $allowed ? 'Allowed' : 'Blocked', message: $message, type: 'success');
            $this->refreshDeviceRelations();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    public function deleteUser()
    {
        if ($this->user->hasProxyAccess()) {
            try {
                app(ProxyMonitorService::class)->userAction($this->user->id, 'delete');
            } catch (\Throwable $e) {
                $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');

                return null;
            }
        } else {
            $this->user->delete();
        }

        $this->dispatch('sweetAlert', title: 'Success', message: 'User deleted successfully.', type: 'success');

        return redirect()->route('users.all');
    }

    public function updatedSessionsPerPage(): void
    {
        $this->resetPage();
    }

    public function getMemberIdProperty(): string
    {
        return sprintf('KV%06d', $this->user->id);
    }

    public function approveProxy(): void
    {
        $this->runProxyAction('approve', null, 'User approved and provisioned.');
    }

    public function banProxy(?string $notes = null): void
    {
        $this->runProxyAction('ban', $notes, 'Proxy access banned.');
    }

    public function unbanProxy(): void
    {
        $this->runProxyAction('unban', null, 'Proxy access restored.');
    }

    public function resetProxyPassword(): void
    {
        try {
            $result = app(ProxyMonitorService::class)->userAction($this->user->id, 'reset_password');
            $this->generatedPassword = $result['new_password'] ?? null;
            $this->dispatch('newPasswordGenerated');
            $this->refreshDeviceRelations();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    protected function runProxyAction(string $action, ?string $notes, string $successMessage): void
    {
        try {
            app(ProxyMonitorService::class)->userAction($this->user->id, $action, $notes);
            $this->dispatch('sweetAlert', title: 'Success', message: $successMessage, type: 'success');
            $this->refreshDeviceRelations();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    public function grantTrial($days = 7)
    {
        if ($this->user->hasUsedTrial()) {
            $this->dispatch('sweetAlert', title: 'Info', message: 'User has already used their trial. Use "Reset Trial" to allow another trial.', type: 'info');

            return;
        }

        $success = $this->user->startTrial($days);

        if ($success) {
            $this->dispatch('sweetAlert', title: 'Success', message: "Trial granted for {$days} days successfully!", type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Failed to grant trial.', type: 'error');
        }

        $this->user->refresh();
    }

    public function resetTrial()
    {
        $success = $this->user->resetTrial();

        if ($success) {
            $this->dispatch('sweetAlert', title: 'Success', message: 'Trial reset successfully! User can now use trial again.', type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Failed to reset trial.', type: 'error');
        }

        $this->user->refresh();
    }

    public function extendTrial($days = 7)
    {
        if (! $this->user->hasUsedTrial()) {
            $this->dispatch('sweetAlert', title: 'Info', message: 'User has not started trial yet.', type: 'info');

            return;
        }

        $success = $this->user->extendTrial($days);

        if ($success) {
            $this->dispatch('sweetAlert', title: 'Success', message: "Trial extended by {$days} days successfully!", type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Failed to extend trial.', type: 'error');
        }

        $this->user->refresh();
    }

    public function render()
    {
        $blockedIpSet = BlockedIp::active()
            ->whereIn('ip_address', $this->user->devices->pluck('ip_address')->filter()->unique()->values())
            ->pluck('ip_address')
            ->flip()
            ->toArray();

        $proxySession = $this->getActiveProxySession();

        $statusColors = ['pending' => 'warning', 'active' => 'success', 'banned' => 'error'];
        $proxyStatus = $this->user->hasProxyAccess() ? $this->user->proxyStatus() : null;

        $connectionHistory = $this->user->hasProxyAccess()
            ? $this->user->proxySessions()->with('server')->orderByDesc('started_at')->paginate($this->sessionsPerPage)
            : null;

        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.manage-user', compact('blockedIpSet', 'proxySession', 'statusColors', 'proxyStatus', 'connectionHistory'))
            ->extends('layouts.tailadmin')
            ->section('content');
    }

    private function getActiveProxySession(): ?array
    {
        $summary = Cache::get('proxy_users_summary');
        $sessions = $summary['sessions'] ?? [];

        foreach ($sessions as $session) {
            if (($session['is_active'] ?? false) && ($session['user_email'] ?? null) === $this->user->email) {
                return $session;
            }
        }

        return null;
    }

    private function calculateExpiry(Carbon $start, Plan $plan): Carbon
    {
        $maxDate = Carbon::create(2038, 1, 19, 3, 14, 7);
        $expiresAt = match ($plan->duration_unit) {
            'day' => $start->addDays($plan->duration),
            'week' => $start->addWeeks($plan->duration),
            'month' => $start->addMonths($plan->duration),
            'year' => $start->addYears($plan->duration),
            default => $start->addDays(7),
        };

        return $expiresAt->greaterThan($maxDate) ? $maxDate : $expiresAt;
    }
}
