<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Articles;

use Illuminate\Contracts\View\View;

class ArticlesController
{
    public function index(): View
    {
        return view('pjstarter::pages.articles.index');
    }
}
