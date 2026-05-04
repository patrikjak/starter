<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Controllers\Users;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Services\Users\InvitationsTableProvider;
use Patrikjak\Starter\Services\Users\InviteService;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

readonly class InvitationsController
{
    /**
     * @throws BindingResolutionException
     */
    public function index(
        TableParametersRequest $request,
        InvitationsTableProvider $invitationsTableProvider,
        InviteService $inviteService,
        Gate $gate
    ): View {
        $availableRoles = $gate->allows(BasePolicy::CREATE, User::class)
            ? $inviteService->getAvailableRoles()
            : new Collection();

        return view('pjstarter::pages.users.invitations.index', [
            'invitationsTable' => $invitationsTableProvider->getTable(
                $request->getTableParameters($invitationsTableProvider->getTableId()),
            ),
            'roles' => $availableRoles,
        ]);
    }
}
