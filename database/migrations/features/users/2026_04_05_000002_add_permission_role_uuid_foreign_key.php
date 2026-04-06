<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('permission_role') || !Schema::hasColumn('permission_role', 'role_id')) {
            return;
        }

        Schema::table('permission_role', function (Blueprint $table) {
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
    }
};
