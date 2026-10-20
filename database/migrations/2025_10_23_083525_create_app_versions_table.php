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
        Schema::create('app_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->json('platform_settings')->nullable(); // per-platform settings (platform/build/force_update/etc)
            $table->string('min_supported_version')->nullable();
            $table->timestamp('release_date')->nullable();
            $table->text('release_notes')->nullable();
            $table->text('update_message')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();

            // Constraints / Indexes
            $table->unique('version'); // one record per version
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_versions');
    }
};
