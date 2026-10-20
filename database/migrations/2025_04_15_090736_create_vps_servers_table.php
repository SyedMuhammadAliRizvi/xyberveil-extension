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
        Schema::create('vps_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username');
            $table->string('ip_address')->unique();
            $table->longtext('private_key')->nullable();
            $table->string('password')->nullable();
            $table->integer('port')->default(22);
            $table->string('domain')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();

            $table->boolean('status')->default(false);

            $table->bigInteger('bandwidth')->nullable();
            $table->bigInteger('ram_limit')->nullable();
            $table->bigInteger('cpu_limit')->nullable();
            $table->bigInteger('disk_limit')->nullable();
            $table->bigInteger('bandwidth_limit_per_second')->nullable();

            $table->float('health_score')->nullable();
            $table->float('cpu_usage')->nullable();
            $table->float('ram_usage')->nullable();
            $table->float('disk_usage')->nullable();
            $table->float('total_mbit_per_s')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vps_servers');
    }
};
