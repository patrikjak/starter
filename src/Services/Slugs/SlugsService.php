<?php

namespace Patrikjak\Starter\Services\Slugs;

use Patrikjak\Starter\Models\Slugs\Slug;
use Patrikjak\Starter\Models\Slugs\Sluggable;
use Patrikjak\Starter\Repositories\Contracts\Slugs\SlugRepository;

readonly class SlugsService
{
    public function __construct(private SlugRepository $slugRepository)
    {
    }

    public function getSlugFromUrl(string $url): ?Slug
    {
        $path = trim(str_replace(config('app.url'), '', $url), '/');
        $parts = explode('/', $path);

        if (count($parts) < 1) {
            return null;
        }

        $slug = array_pop($parts);
        $prefix = null;

        if ($parts !== null && count($parts) > 0) {
            $prefix = implode('/', $parts);
        }

        return $this->slugRepository->getBySlug($slug, $prefix);
    }

    public function getSluggableFromUrl(string $url): Sluggable
    {
        return $this->getSlugFromUrl($url)->sluggable;
    }
}