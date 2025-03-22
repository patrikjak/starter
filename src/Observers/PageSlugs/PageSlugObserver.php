<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Observers\PageSlugs;

use Illuminate\Database\Eloquent\Model;
use Patrikjak\Starter\Dto\PageSlugs\CreatePageSlug;
use Patrikjak\Starter\Models\PageSlugs\Sluggable;
use Patrikjak\Starter\Repositories\Contracts\PageSlugs\PageSlugRepository;

class PageSlugObserver
{
    public function __construct(private readonly PageSlugRepository $pageSlugRepository)
    {
    }

    public function created(Sluggable $sluggable): void
    {
        assert($sluggable instanceof Model);

        $this->pageSlugRepository->create(new CreatePageSlug(
            $sluggable->getNewSlug(),
            $sluggable->getSluggableId(),
            $sluggable->getMorphClass(),
        ));
    }
    
    public function deleted(Sluggable $sluggable): void
    {
        $this->pageSlugRepository->delete($sluggable->getSlug()->id);
    }
}
