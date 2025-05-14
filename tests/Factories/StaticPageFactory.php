<?php

namespace Patrikjak\Starter\Tests\Factories;

use Patrikjak\Starter\Database\Factories\StaticPages\StaticPageFactory as DatabaseStaticPageFactory;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\Slugs\Slug;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

class StaticPageFactory
{
    public static function createDefaultWithoutEvents(): StaticPage
    {
        return StaticPage::withoutEvents(static function () {
            $staticPageFactory = StaticPage::factory()
                ->has(Slug::factory()->state([
                    'id' => '7f03daf7-955d-4d58-9d65-30e589d3cd89',
                    'slug' => 'about-us',
                ]), 'slug')
                ->has(Metadata::factory()->state([
                    'id' => '68828772-5b02-4a62-b800-8a8a07d5b0bd',
                    'title' => 'About us | App',
                    'description' => 'About us page',
                    'keywords' => 'about us, app',
                    'canonical_url' => 'https://app.com/about-us',
                ]), 'metadata');

            assert($staticPageFactory instanceof DatabaseStaticPageFactory);

            return $staticPageFactory
                ->defaultData()
                ->create();
        });
    }

    public static function createCustomWithoutEvents(
        array $data = [],
        array $slugData = [
            'id' => '7f03daf7-955d-4d58-9d65-30e589d3cd89',
            'slug' => 'about-us',
        ],
        array $metadataData = [
            'id' => '68828772-5b02-4a62-b800-8a8a07d5b0bd',
            'title' => 'About us | App',
            'description' => 'About us page',
            'keywords' => 'about us, app',
            'canonical_url' => 'https://app.com/about-us',
        ],
    ): StaticPage {
        return StaticPage::withoutEvents(static function () use ($data, $slugData, $metadataData) {
            $staticPageFactory = StaticPage::factory()
                ->has(Slug::factory()->state($slugData), 'slug')
                ->has(Metadata::factory()->state($metadataData), 'metadata');

            assert($staticPageFactory instanceof DatabaseStaticPageFactory);

            return $staticPageFactory
                ->defaultData()
                ->create($data);
        });
    }
}