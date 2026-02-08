<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Eloquent\Articles;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Articles\ArticleInputData;
use Patrikjak\Starter\Dto\Articles\ArticleProcessedData;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleRepository;

class EloquentArticleRepository implements ArticleRepository
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return Article::with('author', 'articleCategory')
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize, page: $page)
            ->withPath($refreshUrl);
    }

    public function getAllContents(): Collection
    {
        return Article::get(['content']);
    }

    public function create(ArticleInputData $articleInputData, ArticleProcessedData $articleProcessedData): void
    {
        $this->saveArticle(new Article(), $articleInputData, $articleProcessedData);
    }

    public function update(
        string $id,
        ArticleInputData $articleInputData,
        ArticleProcessedData $articleProcessedData,
    ): void {
        $article = Article::findOrFail($id);

        $this->saveArticle($article, $articleInputData, $articleProcessedData);
    }

    public function destroy(string $id): void
    {
        $article = Article::findOrFail($id);

        $article->delete();
    }

    protected function saveArticle(
        Article $articleModel,
        ArticleInputData $articleData,
        ArticleProcessedData $articleProcessedData,
    ): void {
        $articleModel->title = $articleData->title;
        $articleModel->excerpt = $articleData->excerpt;
        $articleModel->content = $articleData->content;
        $articleModel->featured_image = $articleProcessedData->featuredImagePath;
        $articleModel->status = $articleData->status;
        $articleModel->visibility = $articleData->visibility;
        $articleModel->read_time = $articleData->readTime === 0 ? null : $articleData->readTime;
        $articleModel->published_at = $articleProcessedData->publishedAt;

        $articleModel->author()->associate($articleProcessedData->author);
        $articleModel->articleCategory()->associate($articleProcessedData->articleCategory);

        $articleModel->save();
    }
}
