<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('slugs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('prefix')->nullable();
            $table->string('slug')->index();
            $table->uuid('sluggable_id');
            $table->string('sluggable_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slugs');
    }
};
