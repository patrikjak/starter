<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\ArticlesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class TablePartsTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testTablePartsAsSuperAdmin(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->getJson(route('admin.api.articles.table-parts', [
            'page' => 1,
            'perPage' => 10,
        ]));

        $response->assertOk();
        $response->assertJsonStructure();
    }

    #[DefineEnvironment('enableArticles')]
    public function testTablePartsAsAdminWithPermission(): void
    {
        $this->createAndActAsAdmin([
            'view-article',
        ]);

        $response = $this->getJson(route('admin.api.articles.table-parts', [
            'page' => 1,
            'perPage' => 10,
        ]));

        $response->assertOk();
    }

    #[DefineEnvironment('enableArticles')]
    public function testTablePartsAsAdminWithoutPermission(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->getJson(route('admin.api.articles.table-parts', [
            'page' => 1,
            'perPage' => 10,
        ]));

        $response->assertOk();
    }

    #[DefineEnvironment('enableArticles')]
    public function testTablePartsAsUser(): void
    {
        $this->createAndActAsUser();

        $response = $this->getJson(route('admin.api.articles.table-parts', [
            'page' => 1,
            'perPage' => 10,
        ]));

        $response->assertForbidden();
    }
}