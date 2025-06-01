<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Users;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Services\Users\PermissionsTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class PermissionsController
{
    public function index(TableParametersRequest $request, PermissionsTableProvider $permissionsTableProvider): View
    {
        return view('pjstarter::pages.users.permissions.index', [
            'permissionsTable' => $permissionsTableProvider->getTable(
                $request->getTableParameters($permissionsTableProvider->getTableId()),
            ),
        ]);
    }
}