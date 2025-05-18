<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\MetadataController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\StaticPageFactory;
use Patrikjak\Starter\Tests\TestCase;

class EditTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRendered(): void
    {
        $this->actingAs($this->createAdminUser());

        $staticPage = StaticPageFactory::createDefaultWithoutEvents();

        $response = $this->get(route('admin.metadata.edit', ['metadata' => $staticPage->metadata->id]));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}