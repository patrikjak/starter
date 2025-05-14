<?php

namespace Patrikjak\Starter\Database\Factories\StaticPages;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

class StaticPageFactory extends Factory
{
    protected $model = StaticPage::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->words(2, true),
        ];
    }

    public function defaultData(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => '594f620b-47a4-4d77-87de-38289b56ba8c',
                'name' => 'About us',
            ];
        });
    }
}
