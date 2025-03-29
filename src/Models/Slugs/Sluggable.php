<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Slugs;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Sluggable
{
    public function getNewSlug(): string;

    public function getSluggableId(): string;

    public function getPrefix(): ?string;

    public function slug(): MorphOne;

    public function getSlug(): Slug;
}