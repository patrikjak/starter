<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Metadata;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Services\Metadata\MetadataTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class MetadataController
{
    public function index(TableParametersRequest $request, MetadataTableProvider $tableProvider): View
    {
        return view('pjstarter::pages.metadata.index', [
            'table' => $tableProvider->getTable($request->getTableParameters($tableProvider->getTableId())),
        ]);
    }

    public function show(Metadata $metadata): View
    {
        return view('pjstarter::pages.metadata.show', [
            'metadata' => $metadata,
        ]);
    }

    public function edit(Metadata $metadata): View
    {
        return view('pjstarter::pages.metadata.edit', [
            'metadata' => $metadata,
        ]);
    }
}