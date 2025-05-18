<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\MetadataController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\ArticleCategoryFactory;
use Patrikjak\Starter\Tests\Factories\ArticleFactory;
use Patrikjak\Starter\Tests\Factories\StaticPageFactory;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRendered(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('admin.metadata.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    #[DefineEnvironment('enableArticles')]
    public function testPageCanBeRenderedWithData(): void
    {
        $this->actingAs($this->createAdminUser());

        StaticPageFactory::createDefaultWithoutEvents();
        ArticleFactory::createDefaultWithoutEvents();
        ArticleCategoryFactory::createDefaultWithoutEvents();

        $response = $this->get(route('admin.metadata.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    public function testPageNotFoundWithoutStaticPagesFeatureEnabled(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->get('metadata/');
        $response->assertNotFound();
    }
}