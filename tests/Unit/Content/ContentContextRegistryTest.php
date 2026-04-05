<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Unit\Content;

use Patrikjak\Starter\Exceptions\Content\ContentContextNotFoundException;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Support\Content\ContentContextRegistry;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\ValueObjects\Content\ContentContextDefinition;

class ContentContextRegistryTest extends TestCase
{
    public function testGetReturnsRegisteredDefinition(): void
    {
        $registry = new ContentContextRegistry();
        $definition = new ContentContextDefinition(
            'articles/images',
            Article::class,
        );

        $registry->register('articles', $definition);

        $result = $registry->get('articles');

        self::assertSame('articles/images', $result->directory);
        self::assertSame(Article::class, $result->modelClass);
        self::assertNull($result->disk);
    }

    public function testDiskIsPreservedWhenSet(): void
    {
        $registry = new ContentContextRegistry();
        $registry->register('products', new ContentContextDefinition(
            directory: 'products/images',
            modelClass: Article::class,
            disk: 's3',
        ));

        self::assertSame('s3', $registry->get('products')->disk);
    }

    public function testGetUnknownContextThrowsContentContextNotFoundException(): void
    {
        $registry = new ContentContextRegistry();

        $this->expectException(ContentContextNotFoundException::class);
        $this->expectExceptionMessage("Content context 'unknown' not found in registry.");

        $registry->get('unknown');
    }

    public function testCustomContextCanBeRegistered(): void
    {
        $registry = new ContentContextRegistry();
        $definition = new ContentContextDefinition(
            directory: 'products/images',
            modelClass: Article::class,
        );

        $registry->register('products', $definition);

        $result = $registry->get('products');

        self::assertSame('products/images', $result->directory);
    }

    public function testRegisterOverwritesExistingKey(): void
    {
        $registry = new ContentContextRegistry();

        $registry->register('articles', new ContentContextDefinition(
            directory: 'articles/images',
            modelClass: Article::class,
        ));

        $registry->register('articles', new ContentContextDefinition(
            directory: 'articles/images/v2',
            modelClass: Article::class,
        ));

        self::assertSame('articles/images/v2', $registry->get('articles')->directory);
    }
}
