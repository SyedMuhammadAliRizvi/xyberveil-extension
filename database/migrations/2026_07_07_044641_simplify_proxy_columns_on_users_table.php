<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'proxy_approved_at')) {
            return;
        }

        $trialDays = (int) config('proxy.trial_days', 30);

        foreach (DB::table('users')->whereNotNull('linux_username')->get() as $user) {
            $updates = [];

            if ($user->proxy_approved_at && ! $user->email_verified_at) {
                $updates['email_verified_at'] = $user->proxy_approved_at;
            }

            if ($user->proxy_status === 'banned' && ! $user->banned_at) {
                $updates['banned_at'] = $user->proxy_approved_at ?? now();
                if ($user->proxy_notes) {
                    $updates['ban_reason'] = $user->proxy_notes;
                }
            }

            $approvedAt = $user->proxy_approved_at ?? $user->email_verified_at;

            if ($user->proxy_status === 'active' && $approvedAt && ! $user->trial_used) {
                $updates['trial_started_at'] = $approvedAt;
                $updates['trial_ends_at'] = \Illuminate\Support\Carbon::parse($approvedAt)->addDays($trialDays);
                $updates['trial_used'] = true;
            }

            if ($updates !== []) {
                DB::table('users')->where('id', $user->id)->update($updates);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['proxy_approved_at', 'proxy_status', 'proxy_notes']);
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'proxy_status')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('proxy_status', 16)->nullable()->after('linux_username');
            $table->timestamp('proxy_approved_at')->nullable()->after('proxy_status');
            $table->text('proxy_notes')->nullable()->after('proxy_approved_at');
        });

        foreach (DB::table('users')->whereNotNull('linux_username')->get() as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'proxy_status' => $user->banned_at ? 'banned' : ($user->email_verified_at ? 'active' : 'pending'),
                'proxy_approved_at' => $user->email_verified_at,
                'proxy_notes' => $user->ban_reason,
            ]);
        }
    }
};
