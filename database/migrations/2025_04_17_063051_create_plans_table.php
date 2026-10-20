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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->string('name', 60);
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();

            $table->decimal('original_price', 8, 2)->nullable();
            $table->decimal('discount_price', 8, 2)->nullable();
            $table->string('currency', 3)->default('USD');

            $table->string('stripe_price_id')->nullable();
            $table->string('appstore_product_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_best_deal')->default(false);

            $table->unsignedSmallInteger('trial_period')->default(0);
            $table->enum('trial_interval', ['day', 'week', 'month', 'year'])->default('day');

            $table->unsignedSmallInteger('invoice_period')->default(1);
            $table->enum('invoice_interval', ['day', 'week', 'month', 'year'])->default('month');

            $table->unsignedSmallInteger('grace_period')->default(0);
            $table->enum('grace_interval', ['day', 'week', 'month', 'year'])->default('day');

            $table->index('stripe_price_id');
            $table->index('appstore_product_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
