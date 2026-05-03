<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('permission_role')) {
            return;
        }

        $roles = DB::table('roles')->pluck('id', 'name');

        if (Schema::hasColumn('permission_role', 'role_name')) {
            foreach ($roles as $name => $uuid) {
                DB::table('permission_role')
                    ->where('role_name', $name)
                    ->update(['role_id' => $uuid]);
            }

            Schema::table('permission_role', function (Blueprint $table): void {
                $table->dropColumn('role_name');
            });
        }

        $validUuids = $roles->values()->all();

        DB::table('permission_role')
            ->whereNotIn('role_id', $validUuids)
            ->delete();
    }

    public function down(): void
    {
        // Intentionally irreversible — data migration
    }
};
