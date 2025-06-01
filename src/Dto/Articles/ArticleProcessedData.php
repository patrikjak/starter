<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Articles;

use Carbon\CarbonInterface;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;

final readonly class ArticleProcessedData
{
    public function __construct(
        public Author $author,
        public ArticleCategory $articleCategory,
        public ?string $featuredImagePath = null,
        public ?CarbonInterface $publishedAt = null,
    ) {
    }
}