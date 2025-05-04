<?php

namespace Patrikjak\Starter\Database\Factories\Articles;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Models\Articles\Article;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'title' => $this->faker->sentence(3),
            'excerpt' => $this->faker->sentence(10),
            'content' => [
                'time' => 0,
                'blocks' => [
                    [
                        'type' => 'header',
                        'data' => [
                            'text' => 'Super Cool Article',
                            'level' => 2,
                        ],
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'This is a super cool article about testing.',
                        ],
                    ],
                ],
                'version' => '2.24.0',
            ],
            'featured_image' => 'https://example.com/image.jpg',
            'status' => ArticleStatus::DRAFT,
            'visibility' => 'public',
            'read_time' => $this->faker->numberBetween(1, 10),
            'published_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function defaultData(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => '56495910-b334-4426-92ff-6564e95cb1ea',
                'title' => 'Super Cool Article',
                'excerpt' => 'This is a super cool article about testing.',
                'content' => [
                    'time' => 0,
                    'blocks' => [
                        [
                            'type' => 'header',
                            'data' => [
                                'text' => 'Super Cool Article',
                                'level' => 2,
                            ],
                        ],
                        [
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'This is a super cool article about testing.',
                            ],
                        ],
                    ],
                    'version' => '2.24.0',
                ],
                'featured_image' => 'https://example.com/image.jpg',
                'status' => ArticleStatus::DRAFT,
                'visibility' => 'public',
                'read_time' => 5,
                'published_at' => null,
            ];
        });
    }
}
