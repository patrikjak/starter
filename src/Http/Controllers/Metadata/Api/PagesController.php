<?php

namespace Patrikjak\Starter\Http\Controllers\Metadata\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Requests\Metadata\StorePageRequest;
use Patrikjak\Starter\Models\Metadata\Page;
use Patrikjak\Starter\Repositories\Contracts\PageRepository;
use Patrikjak\Starter\Services\Metadata\PagesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class PagesController
{
    public function store(StorePageRequest $request, PageRepository $pageRepository): void
    {
        $pageRepository->create($request->getPage());
    }

    public function update(Page $page, StorePageRequest $request, PageRepository $pageRepository): void
    {
        $pageRepository->update($request->getPage(), $page->id);
    }

    public function destroy(Page $page, PageRepository $pageRepository): void
    {
        $pageRepository->delete($page->id);
    }

    public function tableParts(TableParametersRequest $request, PagesTableProvider $pagesTableProvider): JsonResponse
    {
        return new JsonResponse($pagesTableProvider->getHtmlParts(
            $request->getTableParameters($pagesTableProvider->getTableId()),
        ));
    }
}