<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Removes the legacy VPS-provisioned server fleet (and the Dedicated
     * Servers feature that hard-depended on it) now that the product is
     * fully proxy-server based. Order matters: children with a foreign key
     * are dropped before the parent table they point to.
     */
    public function up(): void
    {
        Schema::dropIfExists('dedicated_sub_servers');
        Schema::dropIfExists('dedicated_servers');

        // sub_servers itself stays (still backs the separate, unrelated
        // VPN "Server" topology) — only its now-dangling link to vps_groups goes.
        Schema::table('sub_servers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vps_group_id');
        });

        Schema::dropIfExists('vps_group_server');
        Schema::dropIfExists('vps_metrics');
        Schema::dropIfExists('vps_metrics_hourly');
        Schema::dropIfExists('vps_metrics_daily');
        Schema::dropIfExists('vps_groups');
        Schema::dropIfExists('vps_servers');
    }

    /**
     * Not reversible — recreating these tables would only restore empty
     * schema, not the fleet data or the app code that used to populate it.
     */
    public function down(): void
    {
        //
    }
};
