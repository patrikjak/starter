<?php

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
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('admin.authors.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testIndexWithAuthor(): void
    {
        Carbon::setTestNow(Carbon::create(2025, 5, 18));

        $this->actingAs($this->createAdminUser());

        AuthorFactory::createDefaultWithoutEvents();

        $response = $this->getJson(route('admin.authors.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}
