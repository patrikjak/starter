<?php

namespace Patrikjak\Starter\Tests\Factories;

use Patrikjak\Starter\Database\Factories\Authors\AuthorFactory as DatabaseAuthorFactory;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;

class AuthorFactory
{
    public static function createDefaultWithoutEvents(): ArticleCategory
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