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
        Schema::create('dedicated_sub_servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dedicated_server_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vps_server_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dedicated_sub_servers');
    }
};
