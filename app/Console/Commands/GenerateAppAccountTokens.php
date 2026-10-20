<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateAppAccountTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate-app-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate app account tokens for existing users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating app account tokens for existing users...');

        $usersWithoutToken = User::where('role', 'user')->whereNull('app_account_token')->get();

        if ($usersWithoutToken->count() === 0) {
            $this->info('All users already have app account tokens.');

            return 0;
        }

        $bar = $this->output->createProgressBar($usersWithoutToken->count());

        $usersWithoutToken->each(function ($user) use ($bar) {
            $user->app_account_token = $this->generateUniqueAppAccountToken();
            $user->save();
            $bar->advance();
        });

        $bar->finish();
        $this->newLine();
        $this->info('App account tokens generated successfully!');

        return 0;
    }

    private function generateUniqueAppAccountToken(): string
    {
        do {
            $token = (string) Str::uuid();
        } while (User::where('app_account_token', $token)->exists());

        return $token;
    }
}
