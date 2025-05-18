<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\SlugsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Tests\Factories\StaticPageFactory;
use Patrikjak\Starter\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class UpdateTest extends TestCase
{
    public function testUpdateRouteCannotBeFoundWithoutEnabledStaticPagesFeature(): void
    {
        $this->put('slug/some-slug')
            ->assertNotFound();
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testCannotUpdateDueToPolicy(): void
    {
        $this->createAndActAsAdmin();

        $staticPage = StaticPageFactory::createDefaultWithoutEvents();

        $this->put(route('admin.api.slugs.update', ['slug' => $staticPage->slug->id]))
            ->assertForbidden();
    }

    /**
     * @param array<string, string> $data
     */
    #[DefineEnvironment('enableStaticPages')]
    #[DataProvider('updateCorrectDataProvider')]
    public function testSuccessfulUpdate(array $data): void
    {
        $staticPage = StaticPageFactory::createDefaultWithoutEvents();
        
        $this->createAndActAsSuperAdmin();

        $this->put(route('admin.api.slugs.update', ['slug' => $staticPage->slug->id]), $data)
            ->assertOk();

        $this->assertDatabaseHas('slugs', $data);
    }

    /**
     * @param array<string, string> $data
     */
    #[DefineEnvironment('enableStaticPages')]
    #[DataProvider('updateIncorrectDataProvider')]
    public function testUnsuccessfulUpdateDueValidation(array $data): void
    {
        $staticPage = StaticPageFactory::createDefaultWithoutEvents();

        $this->createAndActAsSuperAdmin();

        $response = $this->put(route('admin.api.slugs.update', ['slug' => $staticPage->slug->id]), $data);
        $response->assertUnprocessable();

        $this->assertMatchesJsonSnapshot($response->getContent());

        $this->assertDatabaseMissing('slugs', $data);
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testUnsuccessfulUpdateDueToDuplicateSlug(): void
    {
        $staticPage = StaticPageFactory::createDefaultWithoutEvents();

        $staticPage2 = StaticPage::factory()->create([
            'id' => '69eed203-5218-4359-8efb-83b92a5142ed',
            'name' => 'Some other name',
        ]);
        assert($staticPage2 instanceof StaticPage);

        $this->createAndActAsSuperAdmin();

        $response = $this->put(route('admin.api.slugs.update', ['slug' => $staticPage->slug->id]), [
            'slug' => $staticPage2->slug->slug,
        ]);

        $response->assertUnprocessable();

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testUnsuccessfulUpdateDueToDuplicateEmptySlug(): void
    {
        StaticPageFactory::createCustomWithoutEvents(slugData: ['slug' => '']);

        $staticPage2 = StaticPage::factory()->create([
            'id' => '69eed203-5218-4359-8efb-83b92a5142ed',
            'name' => 'Some other name',
        ]);
        assert($staticPage2 instanceof StaticPage);

        $this->createAndActAsSuperAdmin();

        $response = $this->put(route('admin.api.slugs.update', ['slug' => $staticPage2->slug->id]), [
            'slug' => '',
        ]);

        $response->assertUnprocessable();

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    /**
     * @return iterable<array<int, array<string, string>>>
     */
    public static function updateIncorrectDataProvider(): iterable
    {
        yield 'Slug is too long' => [
            ['slug' => str_repeat('a', 192)],
        ];

        yield 'Slug starts with a dash' => [
            ['slug' => '-some-slug'],
        ];

        yield 'Slug ends with a dash' => [
            ['slug' => 'some-slug-'],
        ];

        yield 'Slug contains invalid characters' => [
            ['slug' => 'some-slug!'],
        ];
    }

    /**
     * @return iterable<array<int, array<string, string|null>>>
     */
    public static function updateCorrectDataProvider(): iterable
    {
        yield 'Filled both values' => [
            [
                'prefix' => 'some-prefix',
                'slug' => 'some-slug',
            ],
        ];

        yield 'Without prefix' => [
            [
                'prefix' => null,
                'slug' => 'some-slug',
            ],
        ];

        yield 'Empty slug' => [
            [
                'prefix' => 'some-prefix',
                'slug' => '',
            ],
        ];

        yield 'Homepage' => [
            [
                'prefix' => null,
                'slug' => '',
            ],
        ];
    }
}