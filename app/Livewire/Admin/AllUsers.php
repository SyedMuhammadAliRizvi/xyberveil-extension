<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\ProxyMonitorService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AllUsers extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 5;

    #[Url]
    public ?string $emailVerified = null;

    #[Url]
    public ?string $lastLoginStart = null;

    #[Url]
    public ?string $lastLoginEnd = null;

    #[Url]
    public ?string $registeredStart = null;

    #[Url]
    public ?string $registeredEnd = null;

    #[Url]
    public ?string $proxyStatus = null;

    public ?string $generatedPassword = null;

    public $userId;

    public $name;

    public $email;

    public $role;

    public $password;

    public $password_confirmation;

    public $isEdit = false;

    public function approveProxyUser(int $id): void
    {
        $this->runProxyAction($id, 'approve', null, 'User approved and provisioned.');
    }

    public function banProxyUser(int $id, ?string $notes = null): void
    {
        $this->runProxyAction($id, 'ban', $notes, 'User banned.');
    }

    public function unbanProxyUser(int $id): void
    {
        $this->runProxyAction($id, 'unban', null, 'User unbanned and re-provisioned.');
    }

    public function deleteProxyUser(int $id): void
    {
        try {
            app(ProxyMonitorService::class)->userAction($id, 'delete');
            $this->dispatch('sweetAlert', title: 'Success', message: 'Proxy access removed.', type: 'success');
            $this->resetPage();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    public function resetProxyPassword(int $id): void
    {
        try {
            $result = app(ProxyMonitorService::class)->userAction($id, 'reset_password');
            $this->generatedPassword = $result['new_password'] ?? null;
            $this->dispatch('newPasswordGenerated');
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    protected function runProxyAction(int $id, string $action, ?string $notes, string $successMessage): void
    {
        try {
            app(ProxyMonitorService::class)->userAction($id, $action, $notes);
            $this->dispatch('sweetAlert', title: 'Success', message: $successMessage, type: 'success');
            $this->resetPage();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    public function resetFilters(): void
    {
        $this->reset([
            'emailVerified',
            'lastLoginStart',
            'lastLoginEnd',
            'registeredStart',
            'registeredEnd',
            'proxyStatus',
        ]);
    }

    /**
     * Backwards compatibility for stale Livewire browser sessions
     * that still call the old proxy-users tab loader.
     */
    public function loadProxyUsers(): void
    {
        $this->resetPage();
    }

    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $this->isEdit
                ? 'unique:users,email,'.$this->userId
                : 'unique:users,email'],
            'role' => $this->isEdit ? ['required', 'in:admin,user'] : ['nullable', 'in:admin,user'],
        ];

        if (! $this->isEdit) {
            $rules['password'] = [
                'required',
                'confirmed',
                Password::min(6)->mixedCase()->numbers()->symbols(),
            ];
        } elseif ($this->password) {
            $rules['password'] = ['nullable', 'confirmed', Password::min(6)->mixedCase()->numbers()->symbols()];
        }

        return $rules;
    }

    public function resetForm(): void
    {
        $this->reset([
            'userId',
            'name',
            'email',
            'role',
            'password',
            'password_confirmation',
        ]);
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function editUser($userId): void
    {
        $this->resetForm();
        $this->isEdit = true;

        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
    }

    public function saveUser(): void
    {
        $this->validate();

        if ($this->isEdit) {
            $user = User::findOrFail($this->userId);
            $updates = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ];

            if ($this->password) {
                $updates['password'] = Hash::make($this->password);
            }

            $user->update($updates);
            $message = 'User updated successfully.';
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'role' => 'user',
                'linux_username' => 'pxtmp'.Str::random(8),
                'password' => Hash::make($this->password),
                'email_verified_at' => now(),
            ]);
            $user->update([
                'linux_username' => User::generateLinuxUsername($user->id),
            ]);
            $message = 'User created successfully.';
        }

        $this->dispatch('closeModel');
        $this->dispatch('sweetAlert', title: 'Success!', message: $message, type: 'success');
        $this->resetPage();
        $this->resetForm();
    }

    public function deleteUser($userid): void
    {
        $user = User::findOrFail($userid);
        $user->delete();

        $this->dispatch('sweetAlert', title: 'Success!', message: 'User deleted successfully.', type: 'success');
        $this->resetPage();
    }

    public function render()
    {
        $activeProxyEmails = $this->getActiveProxyEmails();

        $users = User::query()
            ->with('activeSubscription.plan')
            ->withCount(['proxySessions', 'devices', 'proxyExtensionDevices'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('linux_username', 'like', '%'.$this->search.'%')
                        ->orWhereDate('created_at', $this->search);
                });
            })
            ->when($this->emailVerified === '1', fn ($q) => $q->whereNotNull('email_verified_at'))
            ->when($this->emailVerified === '0', fn ($q) => $q->whereNull('email_verified_at'))
            ->when($this->registeredStart && $this->registeredEnd, function ($query) {
                $query->whereBetween('created_at', [$this->registeredStart, $this->registeredEnd]);
            })
            ->when($this->lastLoginStart && $this->lastLoginEnd, function ($query) {
                $query->whereBetween('last_login', [$this->lastLoginStart, $this->lastLoginEnd]);
            })
            ->when($this->proxyStatus === 'none', fn ($q) => $q->whereNull('linux_username'))
            ->when($this->proxyStatus === 'active', fn ($q) => $q->whereNotNull('linux_username')->whereNotNull('email_verified_at')->whereNull('banned_at'))
            ->when($this->proxyStatus === 'pending', fn ($q) => $q->whereNotNull('linux_username')->whereNull('email_verified_at'))
            ->when($this->proxyStatus === 'banned', fn ($q) => $q->whereNotNull('linux_username')->whereNotNull('banned_at'))
            ->when($this->proxyStatus === 'connected', function ($query) use ($activeProxyEmails) {
                $emails = array_keys($activeProxyEmails);
                $query->whereIn('email', $emails);
            })
            ->where('role', '!=', 'admin')
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.all-users', [
            'users' => $users,
            'activeProxyEmails' => $activeProxyEmails,
        ])
            ->extends('layouts.tailadmin')
            ->section('content');
    }

    /**
     * @return array<string, bool>
     */
    private function getActiveProxyEmails(): array
    {
        $sessions = Cache::get('proxy_users_summary')['sessions'] ?? [];

        $emails = [];
        foreach ($sessions as $session) {
            if ($session['is_active'] ?? false) {
                $emails[$session['user_email']] = true;
            }
        }

        return $emails;
    }

    public function updated($value): void
    {
        if (in_array($value, ['search', 'emailVerified', 'lastLoginStart', 'lastLoginEnd', 'registeredStart', 'registeredEnd', 'perPage', 'proxyStatus'])) {
            $this->resetPage();
        }
    }
}
