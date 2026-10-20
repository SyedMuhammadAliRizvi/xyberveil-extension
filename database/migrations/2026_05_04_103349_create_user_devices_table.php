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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('token_id')->nullable()->constrained('personal_access_tokens')->nullOnDelete();

            // Device identity
            $table->string('name')->nullable();
            $table->string('model')->nullable();
            $table->string('platform', 50)->nullable();
            $table->string('os')->nullable();
            $table->enum('device_type', ['mobile', 'tablet', 'desktop', 'laptop'])->nullable();
            $table->string('app_version')->nullable();
            $table->string('browser', 100)->nullable();

            // Fingerprint to recognise the same physical device across logins
            $table->string('device_fingerprint')->nullable()->index();

            // Network & location (updated on each active session)
            $table->string('ip_address', 45)->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 5)->nullable();

            // Status
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('blocked_at')->nullable();
            $table->text('blocked_reason')->nullable();
            $table->timestamp('revoked_at')->nullable();

            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
