<?php

namespace Patrikjak\Starter\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController
{
    public function index(): View
    {
        return view('pjstarter::pages.dashboard');
    }
}