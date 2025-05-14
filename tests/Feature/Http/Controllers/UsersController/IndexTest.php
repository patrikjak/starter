<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\UsersController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testIndex(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('admin.users.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}