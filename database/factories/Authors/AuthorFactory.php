<?php

namespace Patrikjak\Starter\Database\Factories\Authors;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patrikjak\Starter\Models\Authors\Author;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'id' => '3eb904c7-ba46-43e3-afaf-a8ebf3c9c3c2',
            'name' => 'John Doe',
        ];
    }
}
