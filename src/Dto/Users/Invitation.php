<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Dto\Users;

readonly class Invitation
{
    public function __construct(
        public string $email,
        public string $roleId,
        public ?string $roleName,
        public string $invitedAt,
    ) {
    }
}
