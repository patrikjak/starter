<?php

namespace Patrikjak\Starter\Dto\PageSlugs;

class CreatePageSlug
{
    public function __construct(
        public readonly string $slug,
        public readonly string $sluggableId,
        public readonly string $sluggableType,
    ) {
    }
}