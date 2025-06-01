<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Factories;

use Patrikjak\Starter\Database\Factories\Authors\AuthorFactory as DatabaseAuthorFactory;
use Patrikjak\Starter\Models\Authors\Author;

class AuthorFactory
{
    public static function createDefaultWithoutEvents(): Author
    {
        return Author::withoutEvents(static function () {
            $authorFactory = Author::factory();
            assert($authorFactory instanceof DatabaseAuthorFactory);

            return $authorFactory
                ->defaultData()
                ->create();
        });
    }
}