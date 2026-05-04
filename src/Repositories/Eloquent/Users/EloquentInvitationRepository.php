<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Repositories\Eloquent\Users;

use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Dto\Users\Invitation;
use Patrikjak\Starter\Exceptions\Users\InvitationDeleteFailedException;
use Patrikjak\Starter\Exceptions\Users\InvitationUpdateFailedException;
use Patrikjak\Starter\Repositories\Contracts\Users\InvitationRepository;
use Patrikjak\Starter\Repositories\Mappers\Users\InvitationMapper;

readonly class EloquentInvitationRepository implements InvitationRepository
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private InvitationMapper $invitationMapper,
    ) {
    }

    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        $columns = [
            'register_invites.email',
            'register_invites.role_id',
            'register_invites.created_at',
            'roles.name as role_name',
        ];

        $paginator = $this->databaseManager->table('register_invites')
            ->select($columns)
            ->leftJoin('roles', 'roles.id', '=', 'register_invites.role_id')
            ->orderBy('register_invites.created_at', 'desc')
            ->paginate($pageSize, page: $page);

        $paginator->setPath($refreshUrl);

        return $paginator->through(fn (object $row): Invitation => $this->invitationMapper->map($row));
    }

    public function delete(string $email): void
    {
        $deleted = $this->databaseManager->table('register_invites')
            ->where('email', $email)
            ->delete();

        if ($deleted === 0) {
            throw new InvitationDeleteFailedException($email);
        }
    }

    public function updateRole(string $email, string $roleId): void
    {
        $exists = $this->databaseManager->table('register_invites')
            ->where('email', $email)
            ->exists();

        if (!$exists) {
            throw new InvitationUpdateFailedException($email);
        }

        $this->databaseManager->table('register_invites')
            ->where('email', $email)
            ->update(['role_id' => $roleId]);
    }
}
