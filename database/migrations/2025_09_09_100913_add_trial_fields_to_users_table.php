<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('trial_started_at')->nullable()->after('remember_token');
            $table->timestamp('trial_ends_at')->nullable()->after('trial_started_at');
            $table->boolean('trial_used')->default(false)->after('trial_ends_at');
            $table->boolean('trial_expired_notification_sent')->default(false)->after('trial_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['trial_started_at', 'trial_ends_at', 'trial_used', 'trial_expired_notification_sent']);
        });
    }
};
