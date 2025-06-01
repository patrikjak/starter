<?php

namespace Patrikjak\Starter\Database\Factories\Metadata;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patrikjak\Starter\Database\Factories\StaticPages\StaticPageFactory;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

class MetadataFactory extends Factory
{
    protected $model = Metadata::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(10),
            'keywords' => $this->faker->sentence(5),
            'canonical_url' => $this->faker->url(),
            'structured_data' => null,
            'metadatable_id' => $this->faker->uuid(),
            'metadatable_type' => $this->faker->word(),
        ];
    }

    public function defaultData(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => '68828772-5b02-4a62-b800-8a8a07d5b0bd',
                'title' => 'About us | App',
                'description' => 'About us page',
                'keywords' => 'about us, app',
                'canonical_url' => 'https://app.com/about-us',
                'structured_data' => null,
                'metadatable_id' => '7f03daf7-955d-4d58-9d65-30e589d3cd89',
                'metadatable_type' => StaticPage::class,
            ];
        });
    }
}
