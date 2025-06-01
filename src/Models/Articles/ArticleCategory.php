<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Articles;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Patrikjak\Starter\Database\Factories\Articles\ArticleCategoryFactory;
use Patrikjak\Starter\Models\Common\Visitable;
use Patrikjak\Starter\Models\Metadata\Concerns\MetadatableDefaults;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\Metadata\Metadatable;
use Patrikjak\Starter\Models\Metadata\MetadataRelationship;
use Patrikjak\Starter\Models\Slugs\Concerns\SluggableDefaults;
use Patrikjak\Starter\Models\Slugs\Concerns\VisitableViaSlug;
use Patrikjak\Starter\Models\Slugs\Slug;
use Patrikjak\Starter\Models\Slugs\Sluggable;
use Patrikjak\Starter\Models\Slugs\SlugRelationship;
use Patrikjak\Starter\Observers\Metadata\MetadatableObserver;
use Patrikjak\Starter\Observers\Slugs\SluggableObserver;

/**
 * @property string $id
 * @property string $name
 * @property string $description
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property Slug $slug
 * @property ?Metadata $metadata
 */
#[ObservedBy(SluggableObserver::class)]
#[ObservedBy(MetadatableObserver::class)]
class ArticleCategory extends Model implements Sluggable, Metadatable, Visitable
{
    use SlugRelationship;
    use MetadataRelationship;
    use HasUuids;
    use VisitableViaSlug;
    use MetadatableDefaults;
    use SluggableDefaults;
    use HasFactory;

    public function getNewSlug(): string
    {
        return Str::slug($this->name);
    }

    public function getMetadatableTypeLabel(): string
    {
        return __('pjstarter::pages.articles.categories.category');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    protected static function newFactory(): ArticleCategoryFactory
    {
        return ArticleCategoryFactory::new();
    }
}
