<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Database\Factories\StaticPages;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

/**
 * @extends Factory<StaticPage>
 * @method $this hasSlug(array<string, mixed> $attributes = [])
 * @method $this hasMetadata(array<string, mixed> $attributes = [])
 */
class StaticPageFactory extends Factory
{
    protected $model = StaticPage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->words(2, true),
        ];
    }

    public function defaultData(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'id' => '594f620b-47a4-4d77-87de-38289b56ba8c',
                'name' => 'About us',
            ];
        });
    }
}
