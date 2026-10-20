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
        Schema::create('vps_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vps_server_id')->constrained()->cascadeOnDelete();

            // Health and Status
            $table->float('health_score')->nullable();
            $table->boolean('is_healthy')->default(false);
            $table->text('error_message')->nullable();
            $table->integer('response_time_ms')->nullable(); // API response time

            // Resource Usage
            $table->float('cpu_usage_percentage')->nullable();
            $table->float('ram_usage_percentage')->nullable();
            $table->float('disk_usage_percentage')->nullable();

            // Bandwidth Data
            $table->float('total_mbit_per_s')->nullable(); // Live bandwidth
            $table->float('download_rate_kbit')->nullable(); // Download rate in kbit/s
            $table->float('upload_rate_kbit')->nullable(); // Upload rate in kbit/s
            $table->float('monthly_bandwidth_mb')->nullable(); // Monthly total in MB
            $table->float('monthly_bandwidth_percentage')->nullable(); // % of monthly limit used

            // Detailed Scores (for granular analysis)
            $table->json('detailed_scores')->nullable(); // Store the detailed scores object
            $table->json('limits')->nullable(); // Store the limits for reference

            // Indexes for efficient querying
            $table->index(['vps_server_id', 'created_at']);
            $table->index(['is_healthy', 'created_at']);
            $table->index('health_score');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vps_metrics');
    }
};
