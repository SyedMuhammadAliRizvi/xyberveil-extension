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
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedTinyInteger('max_devices')->nullable()->after('is_best_deal')->comment('null = unlimited');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('custom_device_limit')->nullable()->after('trial_expired_notification_sent')->comment('null = use plan/default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('max_devices');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('custom_device_limit');
        });
    }
};
