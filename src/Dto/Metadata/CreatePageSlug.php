<?php

namespace Patrikjak\Starter\Dto\Metadata;

class CreatePageSlug
{
    public function __construct(public readonly string $name, public readonly string $slug)
    {
    }
}