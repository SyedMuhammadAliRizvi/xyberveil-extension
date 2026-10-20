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
        Schema::create('local_subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');

            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('grace_ends_at')->nullable();

            $table->decimal('amount_paid', 8, 2)->nullable();
            $table->string('currency', 3)->default('USD');

            $table->enum('status', [
                'trialing',
                'active',
                'grace',
                'expired',
                'cancelled',
                'failed',
            ])->default('trialing');

            $table->enum('cancelled_by', ['user', 'system'])->nullable();
            $table->string('cancelled_reason')->nullable();

            $table->boolean('is_recurring')->default(true);

            $table->string('provider')->default('local');

            $table->foreignId('previous_subscription_id')->nullable()->constrained('local_subscriptions')->nullOnDelete();
            $table->json('proration_meta')->nullable();

            $table->string('appstore_original_transaction_id')->nullable();
            $table->string('appstore_transaction_id')->nullable();
            $table->string('appstore_product_id')->nullable();

            $table->index('appstore_original_transaction_id');
            $table->index('appstore_transaction_id');
            $table->index('appstore_product_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_subscriptions');
    }
};
