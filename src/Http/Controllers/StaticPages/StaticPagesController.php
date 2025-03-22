<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\StaticPages;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Services\StaticPages\StaticPagesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class StaticPagesController
{
    public function index(TableParametersRequest $request, StaticPagesTableProvider $staticPagesTableProvider): View
    {
        return view('pjstarter::pages.static-pages.index', [
            'table' => $staticPagesTableProvider->getTable(
                $request->getTableParameters($staticPagesTableProvider->getTableId()),
            ),
        ]);
    }

    public function create(): View
    {
        return view('pjstarter::pages.static-pages.create');
    }

    public function edit(StaticPage $staticPage): View
    {
        return view('pjstarter::pages.static-pages.edit', [
            'staticPage' => $staticPage,
        ]);
    }
}
