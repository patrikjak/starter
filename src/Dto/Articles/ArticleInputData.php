<?php

namespace Patrikjak\Starter\Dto\Articles;

use Illuminate\Http\UploadedFile;
use Patrikjak\Starter\Dto\Editorjs\EditorData;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Enums\Articles\Visibility;

final readonly class ArticleInputData
{
    public function __construct(
        public string $authorId,
        public string $articleCategoryId,
        public string $title,
        public ?string $excerpt,
        public EditorData $content,
        public ?UploadedFile $featuredImage,
        public ArticleStatus $status,
        public Visibility $visibility,
        public ?int $readTime,
    ) {
    }
}