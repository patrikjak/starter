<?php

namespace Patrikjak\Starter\View\Layout;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Action extends Component
{
    public function render(): View
    {
        return view('pjstarter::components.action');
    }
}
