<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Removes the legacy native-app version-check / maintenance-mode /
     * downloads subsystem — built for the old mobile/desktop clients'
     * X-App-Version/X-App-Platform header protocol, which the current
     * browser-extension client never sends. Children with a foreign key
     * are dropped before the parent table they point to.
     */
    public function up(): void
    {
        Schema::dropIfExists('download_logs');
        Schema::dropIfExists('app_downloads');
        Schema::dropIfExists('app_versions');
        Schema::dropIfExists('app_maintenance');
    }

    /**
     * Not reversible — recreating these tables would only restore empty
     * schema, not the data or the app code that used to populate it.
     */
    public function down(): void
    {
        //
    }
};
