<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\Authors;

use Illuminate\Support\Collection;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Repositories\Contracts\SupportsPagination;

interface AuthorRepository extends SupportsPagination
{
    /**
     * @return Collection<Author>
     */
    public function getAll(): Collection;

    public function getById(string $id): Author;

    public function create(string $name, ?string $profilePicture): void;

    public function update(string $id, string $name, ?string $profilePicture): void;

    public function delete(string $id): void;
}