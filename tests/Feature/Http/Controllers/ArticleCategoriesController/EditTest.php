<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticleCategoriesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\Slugs\Slug;
use Patrikjak\Starter\Tests\TestCase;

class EditTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testEdit(): void
    {
        $this->actingAs($this->createAdminUser());

        ArticleCategory::withoutEvents(function () {
            $category = ArticleCategory::factory()
                ->for(Slug::factory(), 'slug')
                ->for(Metadata::factory(), 'metadata')
                ->defaultData()
                ->create();
            assert($category instanceof ArticleCategory);

            $response = $this->getJson(route('admin.articles.categories.edit', ['articleCategory' => $category->id]));
            $response->assertOk();

            $this->assertMatchesHtmlSnapshot($response->getContent());
        });
    }
}