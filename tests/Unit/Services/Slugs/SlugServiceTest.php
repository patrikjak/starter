<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Unit\Services\Slugs;

use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Services\Slugs\SlugService;
use Patrikjak\Starter\Tests\TestCase;

class SlugServiceTest extends TestCase
{
    public function testGetSluggableFromUrl(): void
    {
        StaticPage::withoutEvents(function (): void {
            $staticPage = StaticPage::factory()->hasSlug(['slug' => 'test', 'prefix' => null])->create();

            $slugService = app(SlugService::class);

            $sluggable = $slugService->getSluggableFromUrl(sprintf('%s/test', config('app.url')));

            $this->assertEquals($staticPage->id, $sluggable->getSluggableId());
        });
    }

    public function testGetSluggableFromUrlWithEmptySlug(): void
    {
        StaticPage::withoutEvents(function (): void {
            $staticPage = StaticPage::factory()->hasSlug(['slug' => '', 'prefix' => 'test'])->create();

            $slugService = app(SlugService::class);

            $sluggable = $slugService->getSluggableFromUrl(sprintf('%s/test', config('app.url')));

            $this->assertEquals($staticPage->id, $sluggable->getSluggableId());
        });
    }

    public function testGetSluggableFromUrlNotFound(): void
    {
        StaticPage::withoutEvents(function (): void {
            StaticPage::factory()->hasSlug(['slug' => 'testttt', 'prefix' => null])->create();

            $slugService = app(SlugService::class);

            $sluggable = $slugService->getSluggableFromUrl(sprintf('%s/test', config('app.url')));

            $this->assertNull($sluggable);
        });
    }
}