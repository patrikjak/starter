<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Slugs;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait SlugRelationship
{
    public function slug(): MorphOne
    {
        return $this->morphOne(Slug::class, 'sluggable');
    }
}