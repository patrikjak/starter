<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Observers\PageSlugs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

        $newSlug = $sluggable->getNewSlug();

        if ($this->pageSlugRepository->existsSameSlug($newSlug, $sluggable->getPrefix()) !== null) {
            $newSlug .= sprintf('-%s', Str::random(5));
        }

        $this->pageSlugRepository->create(new CreatePageSlug(
            $newSlug,
            $sluggable->getSluggableId(),
            $sluggable->getMorphClass(),
            $sluggable->getPrefix(),
        ));
    }
    
    public function deleted(Sluggable $sluggable): void
    {
        $this->pageSlugRepository->delete($sluggable->getSlug()->id);
    }
}
