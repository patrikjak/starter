<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticlesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class CreateTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testCreate(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->getJson(route('admin.articles.create'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testCreateFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $response = $this->getJson(route('admin.articles.create'));
        $response->assertStatus(403);
    }
}