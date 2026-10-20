<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proxy_extension_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_key', 64)->unique();
            $table->string('device_type', 16)->default('extension');
            $table->string('browser_name', 80);
            $table->string('browser_version', 40)->default('');
            $table->string('os_name', 80)->default('');
            $table->string('os_version', 40)->default('');
            $table->string('extension_version', 20)->default('');
            $table->boolean('is_allowed')->default(true);
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });

        Schema::create('proxy_servers', function (Blueprint $table) {
            $table->id();
            $table->uuid('infra_server_id')->nullable()->unique();
            $table->string('name', 80);
            $table->string('country', 80)->default('Unknown');
            $table->string('flag', 8)->default('🌐');
            $table->string('ip', 45);
            $table->unsignedSmallInteger('proxy_port')->default(10080);
            $table->unsignedSmallInteger('socks_port')->default(10081);
            $table->unsignedSmallInteger('agent_port')->default(9191);
            $table->string('agent_key', 128)->nullable();
            $table->string('ssh_user', 64)->default('root');
            $table->text('ssh_password')->nullable();
            $table->unsignedSmallInteger('ssh_port')->default(22);
            $table->boolean('is_active')->default(true);
            $table->string('setup_status', 32)->default('pending');
            $table->longText('setup_log')->nullable();
            $table->timestamps();
        });

        Schema::create('proxy_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('proxy_server_id')->constrained('proxy_servers')->cascadeOnDelete();
            $table->string('proxy_password', 64);
            $table->boolean('synced')->default(false);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'proxy_server_id']);
        });

        Schema::create('proxy_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('proxy_server_id')->nullable()->constrained('proxy_servers')->nullOnDelete();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->string('client_ip', 45)->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'started_at']);
            $table->index(['ended_at']);
        });

        Schema::create('proxy_refresh_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token', 128)->unique();
            $table->string('device_key', 64)->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['user_id', 'device_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proxy_refresh_tokens');
        Schema::dropIfExists('proxy_sessions');
        Schema::dropIfExists('proxy_credentials');
        Schema::dropIfExists('proxy_servers');
        Schema::dropIfExists('proxy_extension_devices');
    }
};
