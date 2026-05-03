<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Controllers\Users\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\Users\UpdateInvitationRequest;
use Patrikjak\Starter\Services\Users\InvitationsTableProvider;
use Patrikjak\Starter\Services\Users\InviteService;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class InvitationsController
{
    use TableParts;

    public function tableParts(TableParametersRequest $request, InvitationsTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }

    public function update(UpdateInvitationRequest $request, InviteService $inviteService, string $email): JsonResponse
    {
        $inviteService->updateRole($email, $request->getRoleId());

        return new JsonResponse([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.invitations.role_updated'),
            'level' => 'success',
        ]);
    }

    public function destroy(InviteService $inviteService, string $email): JsonResponse
    {
        $inviteService->delete($email);

        return new JsonResponse([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.invitations.deleted'),
            'level' => 'success',
        ]);
    }
}
