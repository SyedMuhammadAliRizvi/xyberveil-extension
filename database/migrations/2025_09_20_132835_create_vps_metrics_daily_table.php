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
        Schema::create('vps_metrics_daily', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vps_server_id')->constrained()->cascadeOnDelete();
            $table->date('date'); // e.g., '2025-09-17'

            // Aggregated Health Metrics
            $table->float('avg_health_score')->nullable();
            $table->float('min_health_score')->nullable();
            $table->float('max_health_score')->nullable();
            $table->float('uptime_percentage'); // % of checks that were healthy this day

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
            $table->float('total_bandwidth_mb')->nullable(); // Sum of bandwidth usage this day

            // Performance Metrics
            $table->integer('avg_response_time_ms')->nullable();
            $table->integer('max_response_time_ms')->nullable();
            $table->integer('total_checks'); // Number of health checks this day
            $table->integer('successful_checks'); // Number of successful checks

            // Daily summary fields
            $table->integer('hours_with_data'); // How many hours had data (max 24)
            $table->text('incidents')->nullable(); // JSON of any major incidents this day

            // Unique constraint to prevent duplicate day records
            $table->unique(['vps_server_id', 'date']);
            $table->index(['vps_server_id', 'date']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vps_metrics_daily');
    }
};
