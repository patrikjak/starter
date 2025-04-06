<?php

namespace Patrikjak\Starter\Dto\Users;

class NewPermission
{
    public function __construct(public string $name, public array $description)
    {
    }
}