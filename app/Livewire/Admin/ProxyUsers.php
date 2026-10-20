<?php

namespace App\Livewire\Admin;

use App\Services\ProxyMonitorService;
use Livewire\Component;

class ProxyUsers extends Component
{
    public string $search = '';

    public string $statusFilter = '';

    public array $users = [];

    public ?string $loadError = null;

    public bool $isLoading = false;

    public ?string $generatedPassword = null;

    public function mount(): void
    {
        $this->loadUsers();
    }

    public function loadUsers(): void
    {
        $this->isLoading = true;
        $this->loadError = null;

        try {
            $this->users = app(ProxyMonitorService::class)->users();
        } catch (\Throwable $e) {
            $this->users = [];
            $this->loadError = 'Could not reach the proxy monitor service: '.$e->getMessage();
        } finally {
            $this->isLoading = false;
        }
    }

    public function approve(int $id): void
    {
        $this->runAction($id, 'approve', null, 'User approved and provisioned.');
    }

    public function ban(int $id, ?string $notes = null): void
    {
        $this->runAction($id, 'ban', $notes, 'User banned.');
    }

    public function unban(int $id): void
    {
        $this->runAction($id, 'unban', null, 'User unbanned and re-provisioned.');
    }

    public function delete(int $id): void
    {
        $this->runAction($id, 'delete', null, 'User deleted.');
    }

    public function resetPassword(int $id): void
    {
        try {
            $result = app(ProxyMonitorService::class)->userAction($id, 'reset_password');
            $this->generatedPassword = $result['new_password'] ?? null;
            $this->dispatch('newPasswordGenerated');
            $this->loadUsers();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    protected function runAction(int $id, string $action, ?string $notes, string $successMessage): void
    {
        try {
            app(ProxyMonitorService::class)->userAction($id, $action, $notes);
            $this->dispatch('sweetAlert', title: 'Success', message: $successMessage, type: 'success');
            $this->loadUsers();
        } catch (\Throwable $e) {
            $this->dispatch('sweetAlert', title: 'Error', message: $e->getMessage(), type: 'error');
        }
    }

    /**
     * @return array{total: int, active: int, pending: int, banned: int}
     */
    public function getStatsProperty(): array
    {
        return [
            'total' => count($this->users),
            'active' => collect($this->users)->where('status', 'active')->count(),
            'pending' => collect($this->users)->where('status', 'pending')->count(),
            'banned' => collect($this->users)->where('status', 'banned')->count(),
        ];
    }

    public function getFilteredUsersProperty(): array
    {
        return array_values(array_filter($this->users, function (array $user) {
            if ($this->statusFilter !== '' && $user['status'] !== $this->statusFilter) {
                return false;
            }

            if ($this->search !== '') {
                $needle = mb_strtolower($this->search);
                $haystack = mb_strtolower($user['email'].' '.$user['display_name'].' '.$user['linux_username']);

                if (! str_contains($haystack, $needle)) {
                    return false;
                }
            }

            return true;
        }));
    }

    public function render()
    {
        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.proxy-users', [
            'filteredUsers' => $this->filteredUsers,
        ])
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}
