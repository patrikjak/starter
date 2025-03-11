<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Layout;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class App extends Component
{
    public function __construct(public string $title = '')
    {
    }

    public function render(): View
    {
        return $this->view('pjstarter::layout.app', [
            'appName' => config('pjstarter.app_name'),
            'icon' => config('pjstarter.icon')['path'],
            'iconType' => config('pjstarter.icon')['type'],
        ]);
    }
}