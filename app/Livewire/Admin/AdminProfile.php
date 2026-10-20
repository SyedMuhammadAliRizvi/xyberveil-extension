<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class AdminProfile extends Component
{
    public string $name = '';

    public string $email = '';

    public string $currentPassword = '';

    public string $newPassword = '';

    public string $newPasswordConfirmation = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function saveProfile(): void
    {
        $user = Auth::user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $emailChanged = $user->email !== $this->email;

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
        ]);

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
            $this->dispatch('sweetAlert', title: 'Updated', message: 'Profile saved. A verification email has been sent to your new address.', type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Updated', message: 'Profile updated successfully.', type: 'success');
        }
    }

    public function changePassword(): void
    {
        $this->validate([
            'currentPassword' => ['required', 'string'],
            'newPassword' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = Auth::user();

        if (! Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'The current password is incorrect.');

            return;
        }

        $user->update(['password' => Hash::make($this->newPassword)]);

        $this->reset('currentPassword', 'newPassword', 'newPasswordConfirmation');

        $this->dispatch('sweetAlert', title: 'Done', message: 'Password changed successfully.', type: 'success');
    }

    public function render()
    {
        /** @disregard @phpstan-ignore-line */
        return view('livewire.admin.admin-profile')
            ->extends('layouts.tailadmin')
            ->section('content');
    }
}
