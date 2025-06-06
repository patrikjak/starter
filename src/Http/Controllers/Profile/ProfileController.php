<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Profile;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;

class ProfileController
{
    public function index(AuthManager $authManager): View
    {
        return view('pjstarter::pages.profile.index', [
            'user' => $authManager->user(),
        ]);
    }

    public function changePassword(): View
    {
        return view('pjstarter::pages.profile.change-password');
    }
}