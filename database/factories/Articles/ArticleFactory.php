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
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'type' => 'header',
                        'data' => [
                            'text' => $this->faker->sentence(3),
                            'level' => 2,
                        ],
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => $this->faker->sentence(10),
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
                    'time' => 1747430180190,
                    'blocks' => [
                        [
                            'type' => 'header',
                            'data' => [
                                'text' => 'Super Cool Article Title',
                                'level' => 1,
                            ],
                        ],
                        [
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'Super cool',
                            ],
                        ],
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
                        [
                            'type' => 'header',
                            'data' => [
                                'text' => 'Testing level 3',
                                'level' => 3,
                            ],
                        ],
                        [
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'This is a super cool article about testing. - after level 3',
                            ],
                        ],
                        [
                            'type' => 'header',
                            'data' => [
                                'text' => 'Testing level 4',
                                'level' => 4,
                            ],
                        ],
                        [
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'This is a super cool article about testing. - after level 4',
                            ],
                        ],
                        [
                            'type' => 'header',
                            'data' => [
                                'text' => 'Testing level 5',
                                'level' => 5,
                            ],
                        ],
                        [
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'This is a super cool article about testing. - after level 5',
                            ],
                        ],
                        [
                            'type' => 'header',
                            'data' => [
                                'text' => 'Testing level 6',
                                'level' => 6,
                            ],
                        ],
                        [
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'This is a super cool article about testing. - after level 6',
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
