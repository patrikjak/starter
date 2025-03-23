<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Slugs\Api;

use Patrikjak\Starter\Http\Requests\Slugs\UpdateSlugRequest;
use Patrikjak\Starter\Models\Slugs\Slug;
use Patrikjak\Starter\Repositories\Contracts\Slugs\SlugRepository;

class SlugsController
{
    public function update(
        Slug $slug,
        UpdateSlugRequest $request,
        SlugRepository $slugRepository,
    ): void {
        $slugRepository->update($slug->id, $request->getUpdateSlug());
    }
}