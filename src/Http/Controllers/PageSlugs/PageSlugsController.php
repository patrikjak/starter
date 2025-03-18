<?php

namespace Patrikjak\Starter\Http\Controllers\PageSlugs;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Services\Metadata\PageSlugsTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class PageSlugsController
{
    public function index(TableParametersRequest $request, PageSlugsTableProvider $pagesTableProvider): View
    {
        return view('pjstarter::pages.page-slugs.index', [
            'pagesTable' => $pagesTableProvider->getTable(
                $request->getTableParameters($pagesTableProvider->getTableId()),
            ),
        ]);
    }

    public function create(): View
    {
        return view('pjstarter::pages.page-slugs.create');
    }

    public function edit(PageSlug $page): View
    {
        return view('pjstarter::pages.page-slugs.edit', [
            'page' => $page,
        ]);
    }
}