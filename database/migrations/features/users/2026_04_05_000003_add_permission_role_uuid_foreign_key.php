<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('permission_role') || !Schema::hasColumn('permission_role', 'role_id')) {
            return;
        }

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE permission_role MODIFY COLUMN role_id VARCHAR(36) NOT NULL');
        }

        Schema::table('permission_role', function (Blueprint $table): void {
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('permission_role', function (Blueprint $table): void {
            $table->dropForeign(['role_id']);
        });
    }
};
