<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\MetadataController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\StaticPageFactory;
use Patrikjak\Starter\Tests\TestCase;

class ShowTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRendered(): void
    {
        $this->createAndActAsAdmin();

        $staticPage = StaticPageFactory::createDefaultWithoutEvents();

        $response = $this->get(route('admin.metadata.show', ['metadata' => $staticPage->metadata->id]));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}