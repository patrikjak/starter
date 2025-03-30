<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Common;

interface Visitable
{
    public function getUrl(): string;
}