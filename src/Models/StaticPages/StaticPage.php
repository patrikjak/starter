<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\StaticPages;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Patrikjak\Starter\Models\Common\Visitable;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\Metadata\Metadatable;
use Patrikjak\Starter\Models\Metadata\MetadataRelationship;
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

    /**
     * @var list<string>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $with = ['slug', 'metadata'];

    public function getNewSlug(): string
    {
        return Str::slug($this->name);
    }

    public function getSluggableId(): string
    {
        return $this->id;
    }

    public function getSlug(): Slug
    {
        return $this->slug;
    }

    public function getPrefix(): ?string
    {
        return null;
    }

    public function getUrl(): string
    {
        return sprintf(
            '%s%s%s',
            config('app.url'),
            $this->slug->prefix === null ? '' : sprintf('/%s', $this->slug->prefix),
            $this->slug->slug === '' ? '' : sprintf('/%s', $this->slug->slug),
        );
    }

    public function getMetadatableId(): string
    {
        return $this->id;
    }

    public function getMetaTitle(): string
    {
        $metaTitleFormat = config('pjstarter.meta_title_format');

        return str_replace(['{title}', '{appName}'], [$this->name, config('pjstarter.app_name')], $metaTitleFormat);
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->getUrl();
    }

    public function getMetadata(): ?Metadata
    {
        return $this->metadata;
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
}
