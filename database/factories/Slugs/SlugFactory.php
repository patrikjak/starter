<?php

namespace Patrikjak\Starter\Database\Factories\Slugs;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patrikjak\Starter\Database\Factories\StaticPages\StaticPageFactory;
use Patrikjak\Starter\Models\Slugs\Slug;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

class SlugFactory extends Factory
{
    protected $model = Slug::class;

    public function definition(): array
    {
        return [
            'id' => '7f03daf7-955d-4d58-9d65-30e589d3cd89',
            'slug' => 'about-us',
            'prefix' => null,
            'sluggable_id' => StaticPageFactory::ID,
            'sluggable_type' => StaticPage::class,
        ];
    }
}
