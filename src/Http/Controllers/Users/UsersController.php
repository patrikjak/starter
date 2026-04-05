<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Controllers\Users;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Services\Users\InviteService;
use Patrikjak\Starter\Services\Users\UsersTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class UsersController
{
    public function index(
        TableParametersRequest $request,
        UsersTableProvider $usersTableProvider,
        InviteService $inviteService,
    ): View {
        return view('pjstarter::pages.users.index', [
            'usersTable' => $usersTableProvider->getTable(
                $request->getTableParameters($usersTableProvider->getTableId()),
            ),
            'roles' => Gate::allows(BasePolicy::CREATE, User::class)
                ? $inviteService->getAvailableRoles()
                : collect(),
        ]);
    }
}
