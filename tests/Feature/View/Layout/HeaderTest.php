<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\View\Layout;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class HeaderTest extends TestCase
{
    /**
     * @param array<string|null> $metadata
     */
    #[DataProvider('metadataProvider')]
    public function testHeaderCanBeRendered(array $metadata): void
    {
        StaticPage::withoutEvents(static function () use ($metadata): void {
            StaticPage::factory()->hasSlug(['prefix' => null, 'slug' => ''])->hasMetadata($metadata)->create();
        });
        
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::layout.header />
            HTML
        ));
    }

    /**
     * @return iterable<array<int, array<string|null>>>
     */
    public static function metadataProvider(): iterable
    {
        yield [[
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'canonical_url' => 'https://example.com',
            'structured_data' => json_encode([
                'type' => 'WebPage',
                'name' => 'Name',
                'description' => 'Description',
                'url' => 'https://example.com',
                'publisher' => [
                    'type' => 'Organization',
                    'name' => 'Name',
                    'logo' => 'https://example.com/logo.png',
                ],
                'image' => 'https://example.com/image.png',
            ]),
        ]];

        yield [[
            'title' => 'Title',
            'description' => null,
            'keywords' => null,
            'canonical_url' => null,
            'structured_data' => null,
        ]];

        yield [[
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'canonical_url' => 'https://example.com',
            'structured_data' => null,
        ]];

        yield [[
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => null,
            'canonical_url' => null,
            'structured_data' => null,
        ]];
    }
}