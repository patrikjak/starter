<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController
{
    public function index(): View
    {
        return view('pjstarter::pages.dashboard');
    }
}