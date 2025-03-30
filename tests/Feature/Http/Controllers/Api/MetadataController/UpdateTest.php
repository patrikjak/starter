<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\MetadataController;

use Illuminate\Foundation\Console\Kernel;
use Mockery\MockInterface;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class UpdateTest extends TestCase
{
    /**
     * @param array<string, string> $data
     */
    #[DefineEnvironment('enableStaticPages')]
    #[DataProvider('correctMetadataDataProvider')]
    public function testSuccessfulUpdate(array $data): void
    {
        $this->actingAs($this->createAdminUser());

        StaticPage::withoutEvents(function () use ($data): void {
            $staticPage = StaticPage::factory()->hasMetadata()->create();
            assert($staticPage instanceof StaticPage);

            $response = $this->put(route('api.metadata.update', ['metadata' => $staticPage->metadata->id]), $data);
            $response->assertOk();
        });

        $this->assertDatabaseHas('metadata', $data);
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testSuccessfulUpdateWithDispatchedUpdatedEvent(): void
    {
        $this->actingAs($this->createAdminUser());

        $this->mock(Kernel::class, static function (MockInterface $mock): void {
            $mock->shouldReceive('call')->once()->with('view:clear');
        });

        $staticPage = StaticPage::factory()->hasMetadata()->create();
        assert($staticPage instanceof StaticPage);

        $response = $this->put(route('api.metadata.update', ['metadata' => $staticPage->metadata->id]), [
            'title' => 'Test title',
            'description' => 'Test description',
            'keywords' => 'Test keywords',
            'canonical_url' => null,
            'structured_data' => null,
        ]);

        $response->assertOk();
    }

    /**
     * @param array<string, string> $data
     */
    #[DefineEnvironment('enableStaticPages')]
    #[DataProvider('incorrectMetadataDataProvider')]
    public function testFailedUpdate(array $data): void
    {
        $this->actingAs($this->createAdminUser());

        StaticPage::withoutEvents(function () use ($data): void {
            $staticPage = StaticPage::factory()->hasMetadata()->create();
            assert($staticPage instanceof StaticPage);

            $response = $this->put(route('api.metadata.update', ['metadata' => $staticPage->metadata->id]), $data);
            $response->assertUnprocessable();

            $this->assertMatchesJsonSnapshot($response->getContent());
        });

        $this->assertDatabaseMissing('metadata', $data);
    }

    /**
     * @return iterable<array<int, array<string, string|null>>>
     */
    public static function correctMetadataDataProvider(): iterable
    {
        yield [[
            'title' => 'Test title',
            'description' => 'Test description',
            'keywords' => 'Test keywords',
            'canonical_url' => null,
            'structured_data' => '{}',
        ]];

        yield [[
            'title' => 'Test title',
            'description' => 'Test description',
            'keywords' => null,
            'canonical_url' => 'https://example.com',
            'structured_data' => null,
        ]];
    }

    /**
     * @return iterable<array<int, array<string, string|null>>>
     */
    public static function incorrectMetadataDataProvider(): iterable
    {
        yield 'Empty title' => [[
            'title' => '',
            'description' => 'Test description',
            'keywords' => 'Test keywords',
            'canonical_url' => null,
            'structured_data' => '{}',
        ]];

        yield 'Long data' => [[
            'title' => str_repeat('a', 192),
            'description' => str_repeat('a', 192),
            'keywords' => str_repeat('a', 192),
            'canonical_url' => str_repeat('a', 192),
            'structured_data' => str_repeat('a', 192),
        ]];

        yield 'Not valid structured data' => [[
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => null,
            'canonical_url' => null,
            'structured_data' => '{\'not valid json\'}',
        ]];

        yield 'Not valid URL for canonical URL' => [[
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => null,
            'canonical_url' => 'not valid url',
            'structured_data' => null,
        ]];
    }
}