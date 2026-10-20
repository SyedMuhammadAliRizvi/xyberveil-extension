<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:user-roles';

    /**
     * The console command description.
     */
    protected $description = 'Convert old user roles from "normal" to "user"';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Updating user roles...');

        $count = DB::table('users')
            ->where('role', 'normal')
            ->update(['role' => 'user']);

        $this->info("✅ Updated {$count} users from 'normal' to 'user'.");

        return self::SUCCESS;
    }
}
