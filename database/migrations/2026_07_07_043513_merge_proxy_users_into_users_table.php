<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'linux_username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('linux_username', 32)->nullable()->unique()->after('role');
                $table->string('proxy_status', 16)->nullable()->after('linux_username');
                $table->timestamp('proxy_approved_at')->nullable()->after('proxy_status');
                $table->text('proxy_notes')->nullable()->after('proxy_approved_at');
            });
        }

        if (! Schema::hasTable('proxy_users')) {
            return;
        }

        $idMap = [];

        foreach (DB::table('proxy_users')->orderBy('id')->get() as $proxyUser) {
            $email = mb_strtolower((string) $proxyUser->email);
            $existing = DB::table('users')->whereRaw('LOWER(email) = ?', [$email])->first();

            if ($existing) {
                DB::table('users')->where('id', $existing->id)->update([
                    'linux_username' => $proxyUser->linux_username,
                    'proxy_status' => $proxyUser->status,
                    'proxy_approved_at' => $proxyUser->approved_at,
                    'proxy_notes' => $proxyUser->notes,
                    'updated_at' => $proxyUser->updated_at ?? now(),
                ]);
                $idMap[(int) $proxyUser->id] = (int) $existing->id;
            } else {
                $userId = DB::table('users')->insertGetId([
                    'name' => $proxyUser->display_name ?: $proxyUser->email,
                    'email' => $proxyUser->email,
                    'slug' => null,
                    'email_verified_at' => $proxyUser->approved_at ?? $proxyUser->created_at,
                    'password' => $proxyUser->password_hash,
                    'role' => $proxyUser->role === 'admin' ? 'admin' : 'user',
                    'linux_username' => $proxyUser->linux_username,
                    'proxy_status' => $proxyUser->status,
                    'proxy_approved_at' => $proxyUser->approved_at,
                    'proxy_notes' => $proxyUser->notes,
                    'created_at' => $proxyUser->created_at ?? now(),
                    'updated_at' => $proxyUser->updated_at ?? now(),
                ]);
                $idMap[(int) $proxyUser->id] = $userId;
            }
        }

        foreach (['proxy_extension_devices', 'proxy_credentials', 'proxy_sessions', 'proxy_refresh_tokens'] as $table) {
            $this->migrateProxyUserForeignKey($table, $idMap);
        }

        Schema::dropIfExists('proxy_users');
    }

    /**
     * @param  array<int, int>  $idMap
     */
    private function migrateProxyUserForeignKey(string $table, array $idMap): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        if (! Schema::hasColumn($table, 'proxy_user_id') && Schema::hasColumn($table, 'user_id')) {
            return;
        }

        if (! Schema::hasColumn($table, 'user_id')) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }

        foreach ($idMap as $oldId => $newId) {
            DB::table($table)->where('proxy_user_id', $oldId)->update(['user_id' => $newId]);
        }

        if (Schema::hasColumn($table, 'proxy_user_id')) {
            DB::table($table)->whereNotNull('proxy_user_id')->whereNull('user_id')->delete();
            DB::table($table)->whereNull('user_id')->delete();
        }

        Schema::disableForeignKeyConstraints();

        if (Schema::hasColumn($table, 'proxy_user_id')) {
            try {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropForeign(['proxy_user_id']);
                });
            } catch (\Throwable) {
                // FK may already be dropped
            }

            if ($table === 'proxy_credentials') {
                try {
                    Schema::table($table, function (Blueprint $blueprint) {
                        $blueprint->dropUnique('proxy_credentials_proxy_user_id_proxy_server_id_unique');
                    });
                } catch (\Throwable) {
                    // Index may already be dropped
                }
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('proxy_user_id');
            });
        }

        if ($table === 'proxy_credentials') {
            $hasUnique = collect(DB::select(
                "SELECT INDEX_NAME FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'proxy_credentials'
                 AND INDEX_NAME = 'proxy_credentials_user_id_proxy_server_id_unique'"
            ))->isNotEmpty();

            if (! $hasUnique) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->unique(['user_id', 'proxy_server_id']);
                });
            }
        }

        if ($table !== 'proxy_sessions') {
            DB::statement("ALTER TABLE `{$table}` MODIFY `user_id` BIGINT UNSIGNED NOT NULL");
        }

        $hasUserForeign = collect(DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME = 'users'",
            [$table]
        ))->isNotEmpty();

        if (! $hasUserForeign) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                if ($table === 'proxy_sessions') {
                    $blueprint->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                } else {
                    $blueprint->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                }
            });
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'linux_username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['linux_username', 'proxy_status', 'proxy_approved_at', 'proxy_notes']);
            });
        }
    }
};
