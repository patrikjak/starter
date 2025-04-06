<?php

namespace Patrikjak\Starter\Http\Controllers\Users;

use Illuminate\Contracts\View\View;

class PermissionsController
{
    public function index(): View
    {
        return view('pjstarter::pages.users.permissions.index');
    }
}