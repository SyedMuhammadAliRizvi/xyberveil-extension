<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_downloads', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('version');
            $table->string('source_type')->default('url');
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('checksum')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('external_url')->nullable();
            $table->unsignedBigInteger('download_count')->default(0);
            $table->text('changelog')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('released_at')->nullable();
            $table->foreignId('app_version_id')->nullable()->constrained('app_versions')->nullOnDelete();
            $table->timestamps();
            $table->unique(['platform', 'version']);
            $table->index(['platform', 'is_active']);
        });

        Schema::create('download_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('download_id')->constrained('app_downloads')->cascadeOnDelete();
            $table->string('platform');
            $table->string('version')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->timestamp('downloaded_at')->useCurrent();
            $table->index(['download_id', 'downloaded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_logs');
        Schema::dropIfExists('app_downloads');
    }
};
