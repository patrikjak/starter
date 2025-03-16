<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('title');
            $table->string('description');
            $table->string('keywords');
            $table->uuid('page_id');
            $table->string('page_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata');
    }
};
