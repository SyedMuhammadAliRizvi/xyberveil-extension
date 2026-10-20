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
        Schema::create('vps_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            $table->string('city')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('country_name')->nullable();

            // optional: ensure no duplicates per city+country
            $table->unique(['city', 'country_code'], 'city_country_unique');
            $table->timestamps();
        });

        Schema::create('vps_group_server', function (Blueprint $table) {
            $table->foreignId('vps_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('vps_server_id')->constrained()->onDelete('cascade');
            $table->string('role')->nullable();
            $table->primary(['vps_group_id', 'vps_server_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vps_groups');
        Schema::dropIfExists('vps_group_server');
    }
};
