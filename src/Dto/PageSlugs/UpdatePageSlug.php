<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\PageSlugs;

class UpdatePageSlug
{
    public function __construct(public readonly string $slug, public readonly ?string $prefix = null)
    {
    }
}