<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Log::info("New user created: {$user->email} (ID: {$user->id}) (Role:{$user->role})");
        if ($user->role === 'user') {
            if (empty($user->app_account_token)) {
                $user->update([
                    'app_account_token' => $this->generateUniqueAppAccountToken(),
                ]);
            }
            $this->grantTrialToNewUser($user);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    private function generateUniqueAppAccountToken(): string
    {
        do {
            $token = (string) Str::uuid();
        } while (User::where('app_account_token', $token)->exists());

        return $token;
    }

    /**
     * Grant trial to newly created user
     */
    private function grantTrialToNewUser(User $user): void
    {
        try {
            // Get trial duration from config (default 7 days)
            $trialDays = config('app.trial_days', 7);

            // Only grant trial if user hasn't used one before
            if (! $user->hasUsedTrial()) {
                $success = $user->startTrial($trialDays);

                if ($success) {
                    Log::info("Trial granted to new user: {$user->email} for {$trialDays} days");
                } else {
                    Log::warning("Failed to grant trial to new user: {$user->email}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error granting trial to new user {$user->email}: ".$e->getMessage());
        }
    }
}
