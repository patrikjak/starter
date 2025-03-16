<?php

namespace Patrikjak\Starter\Http\Controllers\Metadata;

use Illuminate\Contracts\View\View;

class MetadataController
{
    public function index(): View
    {
        return view('pjstarter::pages.metadata.index');
    }
}