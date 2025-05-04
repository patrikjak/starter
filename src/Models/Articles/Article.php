<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Articles;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Patrikjak\Starter\Casts\EditorjsDataCast;
use Patrikjak\Starter\Dto\Editorjs\EditorData;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Enums\Articles\Visibility;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Models\Common\Visitable;
use Patrikjak\Starter\Models\Metadata\Concerns\MetadatableDefaults;
use Patrikjak\Starter\Models\Metadata\Metadatable;
use Patrikjak\Starter\Models\Metadata\MetadataRelationship;
use Patrikjak\Starter\Models\Slugs\Concerns\SluggableDefaults;
use Patrikjak\Starter\Models\Slugs\Concerns\VisitableViaSlug;
use Patrikjak\Starter\Models\Slugs\Sluggable;
use Patrikjak\Starter\Models\Slugs\SlugRelationship;
use Patrikjak\Starter\Observers\Metadata\MetadatableObserver;
use Patrikjak\Starter\Observers\Slugs\SluggableObserver;

/**
 * @property string $id
 * @property Author $author
 * @property ArticleCategory $articleCategory
 * @property string $title
 * @property string $excerpt
 * @property EditorData $content
 * @property string $featured_image
 * @property ArticleStatus $status
 * @property Visibility $visibility
 * @property ?integer $read_time
 * @property CarbonInterface $published_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
#[ObservedBy(SluggableObserver::class)]
#[ObservedBy(MetadatableObserver::class)]
class Article extends Model implements Visitable, Metadatable, Sluggable
{
    use HasUuids;
    use VisitableViaSlug;
    use SluggableDefaults;
    use MetadatableDefaults;
    use SlugRelationship;
    use MetadataRelationship;

    public function getMetaTitle(): string
    {
        return $this->getTitleFromTemplate($this->title);
    }

    public function getMetadatableTypeLabel(): string
    {
        return __('pjstarter::pages.articles.article');
    }

    public function getPageName(): string
    {
        return $this->title;
    }

    public function getNewSlug(): string
    {
        return Str::slug($this->title);
    }

    public function articleCategory(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function getFeaturedImagePath(): string
    {
        return asset(sprintf('storage/%s', $this->featured_image));
    }

    protected function casts(): array
    {
        return [
            'content' => EditorjsDataCast::class,
            'status' => ArticleStatus::class,
            'visibility' => Visibility::class,
            'published_at' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }
}
