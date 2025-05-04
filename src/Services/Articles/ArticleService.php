<?php

namespace Patrikjak\Starter\Services\Articles;

use Carbon\CarbonImmutable;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Patrikjak\Starter\Dto\Articles\ArticleInputData;
use Patrikjak\Starter\Dto\Articles\ArticleProcessedData;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleRepository;
use Patrikjak\Starter\Repositories\Contracts\Authors\AuthorRepository;

readonly class ArticleService
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ArticleCategoryRepository $articleCategoryRepository,
        private AuthorRepository $authorRepository,
        private FilesystemManager $filesystemManager,
    ) {
    }

    public function createArticle(ArticleInputData $articleInputData): void
    {
        $this->articleRepository->create($articleInputData, $this->getProcessedData($articleInputData));
    }

    public function deleteArticle(Article $article): void
    {
        if ($article->featured_image !== null) {
            $this->filesystemManager->disk('public')->delete($article->featured_image);
        }

        $this->articleRepository->destroy($article->id);
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

    public function saveArticleImage(UploadedFile $file): string
    {
        return $file->store('articles/images', 'public');
    }

    public function saveArticleImageFromUrl(string $url): string
    {
        $imageName = Str::random(40);
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $path = sprintf('articles/images/%s.%s', $imageName, $extension);

        $this->filesystemManager->disk('public')->put($path, file_get_contents($url));

        return $path;
    }

    private function saveFeaturedImage(UploadedFile $featuredImage): string
    {
        return $featuredImage->store('articles/featured-images', 'public');
    }
}