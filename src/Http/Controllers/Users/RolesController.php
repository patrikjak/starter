<?php

namespace Patrikjak\Starter\Http\Controllers\Users;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Services\Users\RolesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class RolesController
{
    public function index(TableParametersRequest $request, RolesTableProvider $rolesTableProvider): View
    {
        return view('pjstarter::pages.users.roles.index', [
            'rolesTable' => $rolesTableProvider->getTable(
                $request->getTableParameters($rolesTableProvider->getTableId()),
            ),
        ]);
    }
}
