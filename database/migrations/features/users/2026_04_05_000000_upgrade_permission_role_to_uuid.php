<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!$this->needsUpgrade()) {
            return;
        }

        $this->dropForeignKeyIfExists();

        if (DB::getDriverName() === 'sqlite') {
            $this->upgradeRoleIdSqlite();
        } else {
            DB::statement('ALTER TABLE permission_role MODIFY COLUMN role_id VARCHAR(36) NOT NULL');
        }
    }

    public function down(): void
    {
    }

    private function needsUpgrade(): bool
    {
        if (!Schema::hasTable('permission_role') || !Schema::hasColumn('permission_role', 'role_id')) {
            return false;
        }

        foreach (Schema::getColumns('permission_role') as $column) {
            if ($column['name'] === 'role_id' && str_contains(strtolower($column['type']), 'int')) {
                return true;
            }
        }

        return false;
    }

    private function dropForeignKeyIfExists(): void
    {
        $foreignKeys = Schema::getForeignKeys('permission_role');

        foreach ($foreignKeys as $foreignKey) {
            if (in_array('role_id', $foreignKey['columns'], true)) {
                Schema::table('permission_role', function (Blueprint $table) {
                    $table->dropForeign(['role_id']);
                });

                return;
            }
        }
    }

    private function upgradeRoleIdSqlite(): void
    {
        DB::statement('
            CREATE TABLE permission_role_new (
                permission_id BIGINT UNSIGNED NOT NULL,
                role_id VARCHAR(36) NOT NULL
            )
        ');

        DB::statement('INSERT INTO permission_role_new SELECT permission_id, CAST(role_id AS TEXT) FROM permission_role');

        DB::statement('DROP TABLE permission_role');
        DB::statement('ALTER TABLE permission_role_new RENAME TO permission_role');
    }
};
