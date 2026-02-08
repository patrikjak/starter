<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Slugs;

readonly class UpdateSlug
{
    public function __construct(public string $slug, public ?string $prefix = null)
    {
    }
}