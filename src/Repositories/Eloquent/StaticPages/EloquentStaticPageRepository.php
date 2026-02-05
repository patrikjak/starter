<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Eloquent\StaticPages;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Repositories\Contracts\StaticPages\StaticPageRepository as StaticPageRepositoryContract;
use Patrikjak\Starter\Repositories\Eloquent\EloquentSupportsPagination;

class EloquentStaticPageRepository implements StaticPageRepositoryContract
{
    use EloquentSupportsPagination;

    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return $this->getAllPaginatedByModel(StaticPage::class, $pageSize, $page, $refreshUrl);
    }

    public function create(string $name): void
    {
        $staticPage = new StaticPage();

        $staticPage->name = $name;

        $staticPage->save();
    }

    public function update(string $id, string $name): void
    {
        $staticPage = StaticPage::findOrFail($id);

        $staticPage->name = $name;

        $staticPage->save();
    }

    public function delete(string $id): void
    {
        StaticPage::destroy($id);
    }
}
