<?php

namespace Patrikjak\Starter\Repositories\PageSlugs;

use Patrikjak\Starter\Dto\PageSlugs\CreatePageSlug;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Repositories\Contracts\PageSlugs\PageSlugRepository as PageRepositoryContract;

class PageSlugRepository implements PageRepositoryContract
{
    public function create(CreatePageSlug $createPageSlug): void
    {
        $pageSlug = new PageSlug();

        $pageSlug->slug = $createPageSlug->slug;
        $pageSlug->sluggable_id = $createPageSlug->sluggableId;
        $pageSlug->sluggable_type = $createPageSlug->sluggableType;

        $pageSlug->save();
    }

    public function update(string $id, string $slug): void
    {
        $pageSlug = PageSlug::findOrFail($id);
        assert($pageSlug instanceof PageSlug);

        $pageSlug->slug = $slug;

        $pageSlug->save();
    }

    public function delete(string $id): void
    {
        PageSlug::destroy($id);
    }
}