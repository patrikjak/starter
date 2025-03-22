<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\PageSlugs\Api;

use Patrikjak\Starter\Http\Requests\PageSlugs\StorePageSlugRequest;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Repositories\Contracts\PageSlugs\PageSlugRepository;

class PageSlugsController
{
    public function update(
        PageSlug $pageSlug,
        StorePageSlugRequest $request,
        PageSlugRepository $pageSlugRepository,
    ): void {
        $pageSlugRepository->update($pageSlug->id, $request->getSlug());
    }
}