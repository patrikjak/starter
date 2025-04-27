<?php

namespace Patrikjak\Starter\Repositories\Contracts\Articles;

use Patrikjak\Starter\Dto\Articles\ArticleInputData;
use Patrikjak\Starter\Dto\Articles\ArticleProcessedData;

interface ArticleRepository
{
    public function create(ArticleInputData $articleInputData, ArticleProcessedData $articleProcessedData): void;
}