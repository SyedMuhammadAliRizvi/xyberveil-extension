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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('googleplay_product_id')->nullable()->after('appstore_product_id');
            $table->index('googleplay_product_id');
        });

        Schema::table('local_subscriptions', function (Blueprint $table) {
            $table->string('googleplay_order_id')->nullable()->after('appstore_product_id');
            $table->string('googleplay_purchase_token', 500)->nullable()->after('googleplay_order_id');
            $table->string('googleplay_product_id')->nullable()->after('googleplay_purchase_token');

            $table->index('googleplay_order_id');
            $table->index('googleplay_purchase_token');
            $table->index('googleplay_product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('local_subscriptions', function (Blueprint $table) {
            $table->dropIndex(['googleplay_order_id']);
            $table->dropIndex(['googleplay_purchase_token']);
            $table->dropIndex(['googleplay_product_id']);

            $table->dropColumn('googleplay_order_id');
            $table->dropColumn('googleplay_purchase_token');
            $table->dropColumn('googleplay_product_id');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropIndex(['googleplay_product_id']);
            $table->dropColumn('googleplay_product_id');
        });
    }
};
