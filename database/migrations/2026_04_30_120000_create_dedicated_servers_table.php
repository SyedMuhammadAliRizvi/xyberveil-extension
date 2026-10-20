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
        Schema::create('dedicated_servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('flag_url')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->boolean('android')->default(false);
            $table->boolean('ios')->default(false);
            $table->boolean('macos')->default(false);
            $table->boolean('windows')->default(false);
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dedicated_servers');
    }
};
