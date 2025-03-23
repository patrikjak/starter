<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Slugs;

class CreateSlug
{
    public function __construct(
        public readonly string $slug,
        public readonly string $sluggableId,
        public readonly string $sluggableType,
        public ?string $prefix = null,
    ) {
    }
}