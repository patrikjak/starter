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
            'id' => $this->faker->uuid(),
            'slug' => $this->faker->slug(),
            'prefix' => null,
            'sluggable_id' => $this->faker->uuid(),
            'sluggable_type' => $this->faker->word(),
        ];
    }
}
