<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Services\DashboardService;

readonly class DashboardController
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function index(): View
    {
        return view('pjstarter::pages.dashboard', ['stats' => $this->dashboardService->getStats()]);
    }
}
