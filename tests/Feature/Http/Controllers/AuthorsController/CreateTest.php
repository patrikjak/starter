<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\AuthorsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class CreateTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testCreateAsSuperAdmin(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->getJson(route('admin.authors.create'));

        $response->assertOk();
        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testCreateAsAdminWithPermission(): void
    {
        $this->createAndActAsAdmin([
            'create-author',
        ]);

        $response = $this->getJson(route('admin.authors.create'));

        $response->assertOk();
        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testCreateAsUser(): void
    {
        $this->createAndActAsUser();

        $response = $this->getJson(route('admin.authors.create'));

        $response->assertForbidden();
    }
}