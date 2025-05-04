<?php

namespace Patrikjak\Starter\Repositories\Contracts\Articles;

use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Articles\ArticleInputData;
use Patrikjak\Starter\Dto\Articles\ArticleProcessedData;
use Patrikjak\Starter\Repositories\Contracts\SupportsPagination;

interface ArticleRepository extends SupportsPagination
{
    public function getAllContents(): Collection;

    public function create(ArticleInputData $articleInputData, ArticleProcessedData $articleProcessedData): void;

    public function update(string $id, ArticleInputData $articleInputData, ArticleProcessedData $articleProcessedData): void;

    public function destroy(string $id): void;
}