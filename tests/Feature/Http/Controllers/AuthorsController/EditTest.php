<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\AuthorsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\AuthorFactory;
use Patrikjak\Starter\Tests\TestCase;

class EditTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testEdit(): void
    {
        $this->createAndActAsAdmin();

        $author = AuthorFactory::createDefaultWithoutEvents();

        $response = $this->getJson(route(
            'admin.authors.edit',
            [
                'author' => $author->id,
            ],
        ));

        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}