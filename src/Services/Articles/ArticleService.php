<?php

namespace Patrikjak\Starter\Services\Articles;

use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;
use Patrikjak\Starter\Dto\Articles\ArticleInputData;
use Patrikjak\Starter\Dto\Articles\ArticleProcessedData;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleRepository;
use Patrikjak\Starter\Repositories\Contracts\Authors\AuthorRepository;

readonly class ArticleService
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ArticleCategoryRepository $articleCategoryRepository,
        private AuthorRepository $authorRepository,
    ) {
    }

    public function createArticle(ArticleInputData $articleInputData): void
    {
        $this->articleRepository->create($articleInputData, $this->getProcessedData($articleInputData));
    }

    public function getProcessedData(ArticleInputData $articleInputData): ArticleProcessedData
    {
        return new ArticleProcessedData(
            $this->authorRepository->getById($articleInputData->authorId),
            $this->articleCategoryRepository->getById($articleInputData->articleCategoryId),
            $articleInputData->featuredImage instanceof UploadedFile
                ? $this->saveFeaturedImage($articleInputData->featuredImage)
                : null,
            $articleInputData->status === ArticleStatus::PUBLISHED ? CarbonImmutable::now() : null,
        );
    }

    private function saveFeaturedImage(UploadedFile $featuredImage): string
    {
        return $featuredImage->store('articles/featured-images', 'public');
    }
}