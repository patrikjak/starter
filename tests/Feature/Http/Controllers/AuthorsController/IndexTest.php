<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\AuthorsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
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
}