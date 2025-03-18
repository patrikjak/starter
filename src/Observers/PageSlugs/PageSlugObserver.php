<?php

namespace Patrikjak\Starter\Observers\PageSlugs;

use Patrikjak\Starter\Models\PageSlugs\Sluggable;

class PageSlugObserver
{
    public function saved(Sluggable $sluggable): void
    {
        
    }

    public function deleted(Sluggable $sluggable): void
    {

    }
}
