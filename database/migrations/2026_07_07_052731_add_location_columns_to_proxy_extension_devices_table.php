<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proxy_extension_devices', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('extension_version');
            $table->string('city', 100)->nullable()->after('ip_address');
            $table->string('country', 100)->nullable()->after('city');
            $table->string('country_code', 4)->nullable()->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('proxy_extension_devices', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'city', 'country', 'country_code']);
        });
    }
};
