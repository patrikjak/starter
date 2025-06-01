<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\StaticPagesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\StaticPageFactory;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRendered(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->get(route('admin.static-pages.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRenderedWithData(): void
    {
        $this->createAndActAsAdmin();

        StaticPageFactory::createDefaultWithoutEvents();

        $response = $this->get(route('admin.static-pages.index'))
            ->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    public function testPageCannotBeFound(): void
    {
        $this->createAndActAsUser();

        $this->get('static-pages/')->assertNotFound();
    }
}