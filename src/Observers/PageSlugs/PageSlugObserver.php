<?php

namespace Patrikjak\Starter\Observers\PageSlugs;

use Patrikjak\Starter\Dto\PageSlugs\CreatePageSlug;
use Patrikjak\Starter\Models\PageSlugs\Sluggable;
use Patrikjak\Starter\Repositories\Contracts\PageSlugRepository;

class PageSlugObserver
{
    public function __construct(private readonly PageSlugRepository $pageSlugRepository)
    {
    }

    public function saved(Sluggable $sluggable): void
    {
        $this->pageSlugRepository->create(new CreatePageSlug(
            $sluggable->getSlug(),
            $sluggable->getSluggableId(),
            $sluggable->getMorphClass(),
        ));
    }
    
    public function deleted(Sluggable $sluggable): void
    {

    }
}
