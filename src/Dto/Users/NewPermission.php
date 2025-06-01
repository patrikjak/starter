<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Users;

class NewPermission
{
    /**
     * @param array<string> $description
     */
    public function __construct(public string $name, public array $description, public bool $protected = false)
    {
    }
}