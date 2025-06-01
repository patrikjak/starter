<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\StaticPages;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Patrikjak\Starter\Database\Factories\StaticPages\StaticPageFactory;
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
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property Slug $slug
 * @property ?Metadata $metadata
 */
#[ObservedBy(SluggableObserver::class)]
#[ObservedBy(MetadatableObserver::class)]
class StaticPage extends Model implements Sluggable, Visitable, Metadatable
{
    use HasUuids;
    use SlugRelationship;
    use MetadataRelationship;
    use HasFactory;
    use VisitableViaSlug;
    use MetadatableDefaults;
    use SluggableDefaults;

    /**
     * @var list<string>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $with = ['slug', 'metadata'];

    public function getNewSlug(): string
    {
        return Str::slug($this->name);
    }

    public function getMetadatableTypeLabel(): string
    {
        return __('pjstarter::pages.static_pages.metadatable_type');
    }

    /**
     * @return array<string, string>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    protected static function newFactory(): StaticPageFactory
    {
        return StaticPageFactory::new();
    }
}
