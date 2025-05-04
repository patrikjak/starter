<?php

namespace Patrikjak\Starter\Services\Articles;

use Carbon\CarbonImmutable;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
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
        private ArticleImagesService $articleImagesService,
    ) {
    }

    public function createArticle(ArticleInputData $articleInputData): void
    {
        $this->articleRepository->create($articleInputData, $this->getProcessedData($articleInputData));
    }

    public function updateArticle(
        Article $article,
        ArticleInputData $articleInputData,
        Collection $filesToDelete,
    ): void {
        $this->articleRepository->update(
            $article->id,
            $articleInputData,
            $this->getProcessedData($articleInputData, $article, $filesToDelete),
        );

        $this->articleImagesService->deleteUnusedImages();
    }

    public function deleteArticle(Article $article): void
    {
        if ($article->featured_image !== null) {
            $this->filesystemManager->disk('public')->delete($article->featured_image);
        }

        $this->articleRepository->destroy($article->id);

        $this->articleImagesService->deleteUnusedImages();
    }

    public function getProcessedData(
        ArticleInputData $articleInputData,
        ?Article $article = null,
        ?Collection $filesToDelete = null,
    ): ArticleProcessedData {
        $publishedAt = null;
        $featuredImagePath = null;
        $removeFeaturedImage = $filesToDelete?->isNotEmpty();
        $newFeaturedImage = $articleInputData->featuredImage;

        if ($articleInputData->status === ArticleStatus::PUBLISHED) {
            $publishedAt = CarbonImmutable::now();

            if ($article->published_at !== null) {
                $publishedAt = $article->published_at;
            }
        }

        if ($newFeaturedImage instanceof UploadedFile) {
            if ($article?->featured_image !== null) {
                $this->filesystemManager->disk('public')->delete($article->featured_image);
            }

            $featuredImagePath = $this->saveFeaturedImage($newFeaturedImage);
        }

        if ($removeFeaturedImage) {
            $removeCurrentFeaturedImage = $filesToDelete->contains(basename($article?->featured_image));

            if ($removeCurrentFeaturedImage) {
                $this->filesystemManager->disk('public')->delete($article?->featured_image);
            }
        }

        if (!$removeFeaturedImage && $newFeaturedImage === null) {
            $featuredImagePath = $article?->featured_image;
        }

        return new ArticleProcessedData(
            $this->authorRepository->getById($articleInputData->authorId),
            $this->articleCategoryRepository->getById($articleInputData->articleCategoryId),
            $featuredImagePath,
            $publishedAt,
        );
    }

    public function saveArticleImage(UploadedFile $file): string
    {
        return asset(sprintf('storage/%s', $file->store('articles/images', 'public')));
    }

    public function saveArticleImageFromUrl(string $url): string
    {
        $imageName = Str::random(40);
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $path = sprintf('articles/images/%s.%s', $imageName, $extension);

        $this->filesystemManager->disk('public')->put($path, file_get_contents($url));

        return asset(sprintf('storage/%s',$path));
    }

    private function saveFeaturedImage(UploadedFile $featuredImage): string
    {
        return $featuredImage->store('articles/featured-images', 'public');
    }
}