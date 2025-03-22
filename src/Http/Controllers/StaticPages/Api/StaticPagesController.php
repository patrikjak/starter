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
        StaticPage $staticPage,
        StoreStaticPageRequest $request,
        StaticPageRepository $staticPageRepository,
    ): void {
        $staticPageRepository->update($staticPage->id, $request->getName());
    }

    public function destroy(StaticPage $staticPage, StaticPageRepository $pageSlugRepository): void
    {
        $pageSlugRepository->delete($staticPage->id);
    }

    public function tableParts(TableParametersRequest $request, StaticPagesTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }
}