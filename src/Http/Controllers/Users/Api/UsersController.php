<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Controllers\Users\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\Users\InviteUserRequest;
use Patrikjak\Starter\Services\Users\InviteService;
use Patrikjak\Starter\Services\Users\UsersTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class UsersController
{
    use TableParts;

    public function tableParts(TableParametersRequest $request, UsersTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }

    public function invite(InviteUserRequest $request, InviteService $inviteService): JsonResponse
    {
        $inviteService->sendInvite($request->getEmail(), $request->getRoleId());

        return new JsonResponse([
            'message' => __('pjstarter::pages.users.invite_sent'),
        ]);
    }
}
