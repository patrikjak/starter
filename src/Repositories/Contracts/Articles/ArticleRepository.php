<?php

namespace Patrikjak\Starter\Repositories\Contracts\Articles;

use Patrikjak\Starter\Dto\Articles\ArticleInputData;
use Patrikjak\Starter\Dto\Articles\ArticleProcessedData;
use Patrikjak\Starter\Repositories\Contracts\SupportsPagination;

interface ArticleRepository extends SupportsPagination
{
    public function create(ArticleInputData $articleInputData, ArticleProcessedData $articleProcessedData): void;

    public function destroy(string $id): void;
}