<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\StaticPages\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\StaticPages\StoreStaticPageRequest;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Repositories\Contracts\StaticPages\StaticPageRepository;
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

    public function destroy(StaticPage $staticPage, StaticPageRepository $slugRepository): JsonResponse
    {
        $slugRepository->delete($staticPage->id);

        return new JsonResponse([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.static_pages.static_page_deleted'),
            'level' => 'success',
        ]);
    }

    public function tableParts(TableParametersRequest $request, StaticPagesTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }
}