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
        Schema::create('google_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('notification_type');
            $table->string('package_name');
            $table->bigInteger('event_time_millis')->nullable();
            $table->string('subscription_id');
            $table->string('purchase_token', 500);
            $table->json('payload');
            $table->json('subscription_details')->nullable();
            $table->string('obfuscated_external_account_id')->nullable();
            $table->string('order_id')->nullable();
            $table->boolean('processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('purchase_token');
            $table->index('notification_type');
            $table->index('obfuscated_external_account_id');
            $table->index('order_id');
            $table->index(['purchase_token', 'notification_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_notifications');
    }
};
