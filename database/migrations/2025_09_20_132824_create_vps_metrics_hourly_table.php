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
        Schema::create('vps_metrics_hourly', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vps_server_id')->constrained()->cascadeOnDelete();
            $table->datetime('hour_start'); // e.g., '2025-09-17 13:00:00'

            // Aggregated Health Metrics
            $table->float('avg_health_score')->nullable();
            $table->float('min_health_score')->nullable();
            $table->float('max_health_score')->nullable();
            $table->float('uptime_percentage'); // % of checks that were healthy this hour

            // Aggregated Resource Usage
            $table->float('avg_cpu_usage')->nullable();
            $table->float('max_cpu_usage')->nullable();
            $table->float('avg_ram_usage')->nullable();
            $table->float('max_ram_usage')->nullable();
            $table->float('avg_disk_usage')->nullable();
            $table->float('max_disk_usage')->nullable();

            // Aggregated Bandwidth
            $table->float('avg_bandwidth_mbit')->nullable();
            $table->float('max_bandwidth_mbit')->nullable();
            $table->float('total_bandwidth_mb')->nullable(); // Sum of bandwidth usage this hour

            // Performance Metrics
            $table->integer('avg_response_time_ms')->nullable();
            $table->integer('max_response_time_ms')->nullable();
            $table->integer('total_checks'); // Number of health checks this hour
            $table->integer('successful_checks'); // Number of successful checks

            // Unique constraint to prevent duplicate hour records
            $table->unique(['vps_server_id', 'hour_start']);
            $table->index(['vps_server_id', 'hour_start']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vps_metrics_hourly');
    }
};
