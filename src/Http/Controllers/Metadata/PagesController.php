<?php

namespace Patrikjak\Starter\Http\Controllers\Metadata;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Services\Metadata\PagesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class PagesController
{
    public function index(TableParametersRequest $request, PagesTableProvider $pagesTableProvider): View
    {
        return view('pjstarter::pages.metadata.pages.index', [
            'pagesTable' => $pagesTableProvider->getTable(
                $request->getTableParameters($pagesTableProvider->getTableId()),
            ),
        ]);
    }
}