<?php

namespace Patrikjak\Starter\Database\Factories\StaticPages;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

class StaticPageFactory extends Factory
{
    public const string ID = '594f620b-47a4-4d77-87de-38289b56ba8c';

    protected $model = StaticPage::class;

    public function definition(): array
    {
        return [
            'id' => self::ID,
            'name' => 'About us',
        ];
    }
}
