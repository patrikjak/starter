<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if ($this->needsUpgrade()) {
            $this->dropForeignKeyIfExists();

            if (DB::getDriverName() === 'sqlite') {
                $this->upgradeRoleIdSqlite();
            } else {
                DB::statement('ALTER TABLE permission_role MODIFY COLUMN role_id VARCHAR(36) NOT NULL');
            }
        }

        $this->addRoleNameBridge();
    }

    public function down(): void
    {
        // Intentionally irreversible — data migration
    }

    private function needsUpgrade(): bool
    {
        if (!Schema::hasTable('permission_role') || !Schema::hasColumn('permission_role', 'role_id')) {
            return false;
        }

        return array_any(
            Schema::getColumns('permission_role'),
            static fn ($column) => $column['name'] === 'role_id' && str_contains(strtolower($column['type']), 'int')
        );
    }

    private function dropForeignKeyIfExists(): void
    {
        $foreignKeys = Schema::getForeignKeys('permission_role');

        foreach ($foreignKeys as $foreignKey) {
            if (in_array('role_id', $foreignKey['columns'], true)) {
                Schema::table('permission_role', function (Blueprint $table): void {
                    $table->dropForeign(['role_id']);
                });

                return;
            }
        }
    }

    private function addRoleNameBridge(): void
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('permission_role')) {
            return;
        }

        if (Schema::hasColumn('permission_role', 'role_name')) {
            return;
        }

        if (!$this->rolesHaveIntegerIds()) {
            return;
        }

        Schema::table('permission_role', function (Blueprint $table): void {
            $table->string('role_name', 191)->nullable()->after('role_id');
        });

        $roles = DB::table('roles')->pluck('name', 'id');

        foreach ($roles as $id => $name) {
            DB::table('permission_role')
                ->where('role_id', (string) $id)
                ->update(['role_name' => $name]);
        }
    }

    private function rolesHaveIntegerIds(): bool
    {
        return array_any(
            Schema::getColumns('roles'),
            static fn ($column) => $column['name'] === 'id' && str_contains(strtolower($column['type']), 'int')
        );
    }

    private function upgradeRoleIdSqlite(): void
    {
        DB::statement('
            CREATE TABLE permission_role_new (
                permission_id BIGINT UNSIGNED NOT NULL,
                role_id VARCHAR(36) NOT NULL
            )
        ');

        DB::statement(
            'INSERT INTO permission_role_new SELECT permission_id, CAST(role_id AS TEXT) FROM permission_role',
        );

        DB::statement('DROP TABLE permission_role');
        DB::statement('ALTER TABLE permission_role_new RENAME TO permission_role');
    }
};
