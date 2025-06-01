<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Articles;

final readonly class ArticleCategoryData
{
    public function __construct(public string $name, public ?string $description = null)
    {
    }
}