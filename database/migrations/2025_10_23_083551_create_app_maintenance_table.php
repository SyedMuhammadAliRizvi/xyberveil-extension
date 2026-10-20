<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_maintenance', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(false);
            $table->string('title')->default('Under Maintenance');
            $table->text('message')->nullable();
            $table->timestamp('scheduled_start')->nullable();
            $table->timestamp('scheduled_end')->nullable();
            $table->json('affected_platforms')->nullable(); // ['ios', 'android', 'windows', 'macos']
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('allowed_ips')->nullable(); // JSON array of IPs that can bypass maintenance
            $table->integer('estimated_duration_minutes')->nullable();
            $table->boolean('allow_existing_connections')->default(false); // Allow already connected VPN sessions
            $table->timestamps();
        });

        // Insert default record
        DB::table('app_maintenance')->insert([
            'id' => 1,
            'is_enabled' => false,
            'title' => 'Under Maintenance',
            'message' => 'We are currently performing scheduled maintenance. Please check back soon.',
            'allow_existing_connections' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_maintenance');
    }
};
