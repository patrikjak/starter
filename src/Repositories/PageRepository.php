<?php

namespace Patrikjak\Starter\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Dto\Metadata\CreatePage;
use Patrikjak\Starter\Models\Metadata\Page;
use Patrikjak\Starter\Repositories\Contracts\PageRepository as PageRepositoryContract;

class PageRepository implements PageRepositoryContract
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return Page::paginate($pageSize, page: $page)->withPath($refreshUrl);
    }

    public function create(CreatePage $newPage): void
    {
        $page = new Page();

        $page->name = $newPage->name;
        $page->slug = $newPage->slug;

        $page->save();
    }

    public function update(CreatePage $createPage, string $id): void
    {
        $page = Page::findOrFail($id);
        assert($page instanceof Page);

        $page->name = $createPage->name;
        $page->slug = $createPage->slug;

        $page->save();
    }

    public function delete(string $id): void
    {
        Page::destroy($id);
    }
}