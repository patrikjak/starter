<?php

namespace Patrikjak\Starter\Repositories\Contracts\StaticPages;

use Patrikjak\Starter\Repositories\Contracts\SupportsPagination;

interface StaticPageRepository extends SupportsPagination
{
    public function create(string $name): void;

    public function update(string $id, string $name): void;
}