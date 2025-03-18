<?php

namespace Patrikjak\Starter\Models\PageSlugs;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Sluggable
{
    public function getSlug(): string;

    public function getSluggableId(): string;

    public function getSluggableType(): string;

    public function slug(): MorphOne;
}