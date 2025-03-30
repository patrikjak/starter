<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\MetadataController;

use Carbon\Carbon;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Tests\TestCase;

class ShowTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRendered(): void
    {
        $this->actingAs($this->createAdminUser());

        StaticPage::withoutEvents(function (): void {
            $staticPage = StaticPage::factory()->hasMetadata()->create();
            assert($staticPage instanceof StaticPage);

            $response = $this->get(route('metadata.show', ['metadata' => $staticPage->metadata->id]));
            $response->assertOk();

            $this->assertMatchesHtmlSnapshot($response->getContent());
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2025, 3, 30));
    }
}