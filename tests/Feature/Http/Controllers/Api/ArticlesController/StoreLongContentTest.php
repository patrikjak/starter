<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\ArticlesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Tests\TestCase;

class StoreLongContentTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testStoreWithLongContent(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);

        $response = $this->postJson(route('admin.api.articles.store'), [
            'title' => 'Article With Long Content',
            'category' => $category->id,
            'author' => $author->id,
            'excerpt' => 'This is an article with long content that includes all available block types',
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'id' => '1',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Article With Long Content',
                            'level' => 1,
                        ],
                    ],
                    [
                        'id' => '2',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'This is a paragraph block. It contains regular text content for the article.',
                        ],
                    ],
                    [
                        'id' => '3',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Second Level Heading',
                            'level' => 2,
                        ],
                    ],
                    [
                        'id' => '4',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Another paragraph with more detailed information about the topic.',
                        ],
                    ],
                    [
                        'id' => '5',
                        'type' => 'list',
                        'data' => [
                            'style' => 'ordered',
                            'items' => [
                                [
                                    'content' => 'First item in ordered list',
                                    'meta' => [],
                                    'items' => [],
                                ],
                                [
                                    'content' => 'Second item in ordered list',
                                    'meta' => [],
                                    'items' => [],
                                ],
                                [
                                    'content' => 'Third item in ordered list',
                                    'meta' => [],
                                    'items' => [],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => '6',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Third Level Heading',
                            'level' => 3,
                        ],
                    ],
                    [
                        'id' => '7',
                        'type' => 'list',
                        'data' => [
                            'style' => 'unordered',
                            'items' => [
                                [
                                    'content' => 'First bullet point',
                                    'meta' => [],
                                    'items' => [],
                                ],
                                [
                                    'content' => 'Second bullet point',
                                    'meta' => [],
                                    'items' => [],
                                ],
                                [
                                    'content' => 'Third bullet point',
                                    'meta' => [],
                                    'items' => [],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => '8',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'More explanatory text after the unordered list.',
                        ],
                    ],
                    [
                        'id' => '9',
                        'type' => 'list',
                        'data' => [
                            'style' => 'unordered',
                            'items' => [
                                [
                                    'content' => 'Parent item 1',
                                    'meta' => [],
                                    'items' => [
                                        [
                                            'content' => 'Nested child item 1.1',
                                            'meta' => [],
                                            'items' => [],
                                        ],
                                        [
                                            'content' => 'Nested child item 1.2',
                                            'meta' => [],
                                            'items' => [
                                                [
                                                    'content' => 'Deeply nested item 1.2.1',
                                                    'meta' => [],
                                                    'items' => [],
                                                ],
                                                [
                                                    'content' => 'Deeply nested item 1.2.2',
                                                    'meta' => [],
                                                    'items' => [],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'content' => 'Parent item 2',
                                    'meta' => [],
                                    'items' => [
                                        [
                                            'content' => 'Nested child item 2.1',
                                            'meta' => [],
                                            'items' => [],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => '10',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Fourth Level Heading',
                            'level' => 4,
                        ],
                    ],
                    [
                        'id' => '11',
                        'type' => 'raw',
                        'data' => [
                            'html' => '<div class="custom-block">' .
                                '<p>This is a raw HTML block that can contain custom HTML content.</p>' .
                                '<p>It allows for more complex formatting.</p>' .
                                '</div>',
                        ],
                    ],
                    [
                        'id' => '12',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Fifth Level Heading',
                            'level' => 5,
                        ],
                    ],
                    [
                        'id' => '13',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Additional paragraph content to provide more information.',
                        ],
                    ],
                    [
                        'id' => '14',
                        'type' => 'image',
                        'data' => [
                            'file' => [
                                'url' => 'https://example.com/image.jpg',
                            ],
                            'caption' => 'This is an example image with a caption',
                            'withBorder' => true,
                            'withBackground' => false,
                            'stretched' => false,
                        ],
                    ],
                    [
                        'id' => '15',
                        'type' => 'header',
                        'data' => [
                            'text' => 'Sixth Level Heading',
                            'level' => 6,
                        ],
                    ],
                    [
                        'id' => '16',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Final concluding paragraph to summarize the article content.',
                        ],
                    ],
                ],
                'version' => '2.24.0',
            ]),
            'status' => ArticleStatus::DRAFT->value,
            'read_time' => 10,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'title' => 'Article With Long Content',
            'article_category_id' => $category->id,
            'author_id' => $author->id,
            'excerpt' => 'This is an article with long content that includes all available block types',
            'status' => ArticleStatus::DRAFT->value,
            'read_time' => 10,
        ]);
    }
}