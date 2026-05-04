<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Repositories\Mappers\Users;

use Patrikjak\Starter\Dto\Users\Invitation;

readonly class InvitationMapper
{
    public function map(object $row): Invitation
    {
        return new Invitation(
            (string) $row->email,
            (string) $row->role_id,
            $row->role_name !== null ? (string) $row->role_name : null,
            (string) $row->created_at,
        );
    }
}
