<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\Authors;

use Patrikjak\Starter\Repositories\Contracts\SupportsPagination;

interface AuthorRepository extends SupportsPagination
{
    public function create(string $name, ?string $profilePicture): void;

    public function update(string $id, string $name, ?string $profilePicture): void;

    public function delete(string $id): void;
}