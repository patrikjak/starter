<?php

namespace Patrikjak\Starter\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Dto\Metadata\CreatePageSlug;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Repositories\Contracts\PageSlugRepository as PageRepositoryContract;

class PageSlugRepository implements PageRepositoryContract
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return PageSlug::paginate($pageSize, page: $page)->withPath($refreshUrl);
    }

    public function create(CreatePageSlug $createPageSlug): void
    {
        $pageSlug = new PageSlug();

        $pageSlug->name = $createPageSlug->name;
        $pageSlug->slug = $createPageSlug->slug;

        $pageSlug->save();
    }

    public function update(CreatePageSlug $createPageSlug, string $id): void
    {
        $pageSlug = PageSlug::findOrFail($id);
        assert($pageSlug instanceof PageSlug);

        $pageSlug->name = $createPageSlug->name;
        $pageSlug->slug = $createPageSlug->slug;

        $pageSlug->save();
    }

    public function delete(string $id): void
    {
        PageSlug::destroy($id);
    }
}