<?php

namespace Patrikjak\Starter\Repositories\Articles;

use Patrikjak\Starter\Dto\Articles\ArticleInputData;
use Patrikjak\Starter\Dto\Articles\ArticleProcessedData;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleRepository as ArticleRepositoryContract;

class ArticleRepository implements ArticleRepositoryContract
{
    public function create(ArticleInputData $articleInputData, ArticleProcessedData $articleProcessedData): void
    {
        $this->saveArticle(new Article(), $articleInputData, $articleProcessedData);
    }

    private function saveArticle(
        Article $articleModel,
        ArticleInputData $articleData,
        ArticleProcessedData $articleProcessedData,
    ): void {
        $articleModel->title = $articleData->title;
        $articleModel->excerpt = $articleData->excerpt;
        $articleModel->content = $articleData->content->rawData;
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