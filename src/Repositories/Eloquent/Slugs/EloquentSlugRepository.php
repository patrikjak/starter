<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Eloquent\Slugs;

use Patrikjak\Starter\Dto\Slugs\CreateSlug;
use Patrikjak\Starter\Dto\Slugs\UpdateSlug;
use Patrikjak\Starter\Models\Slugs\Slug;
use Patrikjak\Starter\Repositories\Contracts\Slugs\SlugRepository;

class EloquentSlugRepository implements SlugRepository
{
    public function existsSameSlug(string $slug, ?string $prefix = null, ?string $ignoredId = null): ?Slug
    {
        $query = Slug::where('slug', '=', $slug)
            ->where('prefix', '=', $prefix);

        if ($ignoredId !== null) {
            $query->where('id', '!=', $ignoredId);
        }

        return $query->first();
    }

    public function getBySlug(string $slug, ?string $prefix = null): ?Slug
    {
        return Slug::where('slug', '=', $slug)
            ->where('prefix', '=', $prefix)
            ->first();
    }

    public function getByUri(string $uri): ?Slug
    {
        $uri = trim($uri, '/');
        $uriParts = explode('/', $uri);

        if (count($uriParts) > 1) {
            $prefix = implode('/', array_slice($uriParts, 0, -1));
            $slug = $uriParts[array_key_last($uriParts)];

            return $this->getBySlug($slug, $prefix);
        }

        return $this->getBySlug($uri);
    }

    public function create(CreateSlug $createSlug): void
    {
        $slug = new Slug();

        $slug->prefix = $createSlug->prefix;
        $slug->slug = $createSlug->slug;
        $slug->sluggable_id = $createSlug->sluggableId;
        $slug->sluggable_type = $createSlug->sluggableType;

        $slug->save();
    }

    public function update(string $id, UpdateSlug $updateSlug): void
    {
        $slug = Slug::findOrFail($id);

        $slug->prefix = $updateSlug->prefix;
        $slug->slug = $updateSlug->slug;

        $slug->save();
    }

    public function delete(string $id): void
    {
        Slug::destroy($id);
    }
}
