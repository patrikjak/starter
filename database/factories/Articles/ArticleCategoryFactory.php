<?php

namespace Patrikjak\Starter\Database\Factories\Articles;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patrikjak\Starter\Models\Articles\ArticleCategory;

class ArticleCategoryFactory extends Factory
{
    protected $model = ArticleCategory::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(10),
        ];
    }

    public function defaultData(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 'e13e5622-0acf-41e7-96b0-74d4fd41fb84',
                'name' => 'Testing Category',
                'description' => 'Testing Category Description',
            ];
        });
    }
}
