<?php

namespace Patrikjak\Starter\Http\Controllers\PageSlugs\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Requests\Metadata\StorePageSlugRequest;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Repositories\Contracts\PageSlugRepository;
use Patrikjak\Starter\Services\Metadata\PageSlugsTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class PageSlugsController
{
    public function store(StorePageSlugRequest $request, PageSlugRepository $pageSlugRepository): void
    {
        $pageSlugRepository->create($request->getPage());
    }

    public function update(
        PageSlug $pageSlug,
        StorePageSlugRequest $request,
        PageSlugRepository $pageSlugRepository,
    ): void {
        $pageSlugRepository->update($request->getPage(), $pageSlug->id);
    }

    public function destroy(PageSlug $pageSlug, PageSlugRepository $pageSlugRepository): void
    {
        $pageSlugRepository->delete($pageSlug->id);
    }

    public function tableParts(
        TableParametersRequest $request,
        PageSlugsTableProvider $pageSlugsTableProvider,
    ): JsonResponse {
        return new JsonResponse($pageSlugsTableProvider->getHtmlParts(
            $request->getTableParameters($pageSlugsTableProvider->getTableId()),
        ));
    }
}