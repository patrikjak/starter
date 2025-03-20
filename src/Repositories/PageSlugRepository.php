<?php

namespace Patrikjak\Starter\Repositories;

use Patrikjak\Starter\Dto\PageSlugs\CreatePageSlug;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Repositories\Contracts\PageSlugRepository as PageRepositoryContract;

class PageSlugRepository implements PageRepositoryContract
{
    public function create(CreatePageSlug $createPageSlug): void
    {
        $this->saveSlug($createPageSlug, new PageSlug());
    }

    public function update(CreatePageSlug $createPageSlug, string $id): void
    {
        $pageSlug = PageSlug::findOrFail($id);
        assert($pageSlug instanceof PageSlug);

        $this->saveSlug($createPageSlug, $pageSlug);
    }

    public function delete(string $id): void
    {
        PageSlug::destroy($id);
    }

    private function saveSlug(CreatePageSlug $createPageSlug, PageSlug $pageSlug): void
    {
        $pageSlug->slug = $createPageSlug->slug;
        $pageSlug->sluggable_id = $createPageSlug->sluggableId;
        $pageSlug->sluggable_type = $createPageSlug->sluggableType;

        $pageSlug->save();
    }
}