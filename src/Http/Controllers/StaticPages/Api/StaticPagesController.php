<?php

namespace Patrikjak\Starter\Http\Controllers\StaticPages\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\StaticPages\StoreStaticPageRequest;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Repositories\StaticPages\StaticPageRepository;
use Patrikjak\Starter\Services\StaticPages\StaticPagesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class StaticPagesController
{
    use TableParts;

    public function store(StoreStaticPageRequest $request, StaticPageRepository $staticPageRepository): void
    {
        $staticPageRepository->create($request->getName());
    }

    public function update(
        StaticPage $pageSlug,
        StoreStaticPageRequest $request,
        StaticPageRepository $staticPageRepository,
    ): void {
        $staticPageRepository->update($pageSlug->id, $request->getName());
    }

    public function destroy(StaticPage $pageSlug, StaticPageRepository $pageSlugRepository): void
    {
        $pageSlugRepository->delete($pageSlug->id);
    }

    public function tableParts(TableParametersRequest $request, StaticPagesTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }
}