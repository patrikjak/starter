<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\PageSlugs;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Sluggable
{
    public function getNewSlug(): string;

    public function getSluggableId(): string;

    public function slug(): MorphOne;

    public function getSlug(): PageSlug;
}