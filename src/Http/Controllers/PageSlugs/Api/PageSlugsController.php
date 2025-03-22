<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\PageSlugs\Api;

use Patrikjak\Starter\Http\Requests\PageSlugs\UpdatePageSlugRequest;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Repositories\Contracts\PageSlugs\PageSlugRepository;

class PageSlugsController
{
    public function update(
        PageSlug $pageSlug,
        UpdatePageSlugRequest $request,
        PageSlugRepository $pageSlugRepository,
    ): void {
        $pageSlugRepository->update($pageSlug->id, $request->getUpdatePageSlug());
    }
}