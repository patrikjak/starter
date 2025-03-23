<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Observers\Slugs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Patrikjak\Starter\Dto\Slugs\CreateSlug;
use Patrikjak\Starter\Models\Slugs\Sluggable;
use Patrikjak\Starter\Repositories\Contracts\Slugs\SlugRepository;

class SlugObserver
{
    public function __construct(private readonly SlugRepository $slugRepository)
    {
    }

    public function created(Sluggable $sluggable): void
    {
        assert($sluggable instanceof Model);

        $newSlug = $sluggable->getNewSlug();

        if ($this->slugRepository->existsSameSlug($newSlug, $sluggable->getPrefix()) !== null) {
            $newSlug .= sprintf('-%s', Str::random(5));
        }

        $this->slugRepository->create(new CreateSlug(
            $newSlug,
            $sluggable->getSluggableId(),
            $sluggable->getMorphClass(),
            $sluggable->getPrefix(),
        ));
    }
    
    public function deleted(Sluggable $sluggable): void
    {
        $this->slugRepository->delete($sluggable->getSlug()->id);
    }
}
