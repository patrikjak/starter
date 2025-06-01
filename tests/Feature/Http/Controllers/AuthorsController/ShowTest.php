<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\AuthorsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\AuthorFactory;
use Patrikjak\Starter\Tests\TestCase;

class ShowTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testShow(): void
    {
        $this->createAndActAsAdmin();

        $author = AuthorFactory::createDefaultWithoutEvents();

        $response = $this->getJson(route(
            'admin.authors.show',
            ['author' => $author->id],
        ));

        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}