<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Repositories\Contracts\Users;

use Illuminate\Pagination\LengthAwarePaginator;

interface InvitationRepository
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function delete(string $email): void;

    public function updateRole(string $email, string $roleId): void;
}
