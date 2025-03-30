<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\MetadataController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRendered(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('metadata.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRenderedWithData(): void
    {
        $this->actingAs($this->createAdminUser());

        StaticPage::withoutEvents(function (): void {
            $staticPage = StaticPage::factory()->hasMetadata()->create();
            assert($staticPage instanceof StaticPage);

            $response = $this->get(route('metadata.index'));
            $response->assertOk();

            $this->assertMatchesHtmlSnapshot($response->getContent());
        });
    }

    public function testPageNotFoundWithoutStaticPagesFeatureEnabled(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->get('metadata/');
        $response->assertNotFound();
    }
}