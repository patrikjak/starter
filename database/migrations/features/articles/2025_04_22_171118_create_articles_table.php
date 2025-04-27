<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Enums\Articles\Visibility;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('author_id')
                ->constrained('authors', 'id')
                ->cascadeOnDelete();
            $table->foreignUuid('article_category_id')
                ->constrained('article_categories', 'id')
                ->cascadeOnDelete();
            $table->string('title');
            $table->string('excerpt')->nullable();
            $table->text('content');
            $table->string('featured_image')->nullable();
            $table->string('status')->default(ArticleStatus::DRAFT->value);
            $table->string('visibility')->default(Visibility::PUBLIC->value);
            $table->integer('read_time')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
