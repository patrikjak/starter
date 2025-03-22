<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\PageSlugs;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait PageSlugRelationship
{
    public function slug(): MorphOne
    {
        return $this->morphOne(PageSlug::class, 'sluggable');
    }
}