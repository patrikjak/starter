<?php

namespace Patrikjak\Starter\Database\Factories\Metadata;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patrikjak\Starter\Database\Factories\StaticPages\StaticPageFactory;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

class MetadataFactory extends Factory
{
    protected $model = Metadata::class;

    public function definition(): array
    {
        return [
            'id' => '68828772-5b02-4a62-b800-8a8a07d5b0bd',
            'title' => 'About us | App',
            'description' => 'About us page',
            'keywords' => 'about us, app',
            'canonical_url' => 'https://app.com/about-us',
            'structured_data' => null,
            'metadatable_id' => StaticPageFactory::ID,
            'metadatable_type' => StaticPage::class,
        ];
    }
}
