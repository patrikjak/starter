<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\AuthorsController;

use Illuminate\Support\Carbon;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\AuthorFactory;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testIndex(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->getJson(route('admin.authors.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testIndexWithAuthor(): void
    {
        $this->createAndActAsAdmin();

        AuthorFactory::createDefaultWithoutEvents();

        $response = $this->getJson(route('admin.authors.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}
