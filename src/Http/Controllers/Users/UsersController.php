<?php

namespace Patrikjak\Starter\Http\Controllers\Users;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Services\Users\UsersTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class UsersController
{
    public function index(TableParametersRequest $request, UsersTableProvider $usersTableProvider): View
    {
        return view('pjstarter::pages.users.index', [
            'usersTable' => $usersTableProvider->getTable(
                $request->getTableParameters($usersTableProvider->getTableId()),
            ),
        ]);
    }
}
