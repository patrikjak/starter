<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Articles;

use Illuminate\Support\Collection;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository;

readonly class ArticleCategoryService
{
    public function __construct(private ArticleCategoryRepository $articleCategoryRepository)
    {
    }

    public function getAllAsOptions(): Collection
    {
        return $this->articleCategoryRepository->getAll()
            ->mapwithkeys(static fn (ArticleCategory $item) => [$item->id => $item->name]);
    }
}