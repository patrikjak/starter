<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\StaticPages;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Patrikjak\Starter\Models\Common\Visitable;
use Patrikjak\Starter\Models\Slugs\Slug;
use Patrikjak\Starter\Models\Slugs\SlugRelationship;
use Patrikjak\Starter\Models\Slugs\Sluggable;
use Patrikjak\Starter\Observers\Slugs\SlugObserver;

/**
 * @property string $id
 * @property string $name
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property Slug $slug
 */
#[ObservedBy(SlugObserver::class)]
class StaticPage extends Model implements Sluggable, Visitable
{
    use HasUuids;
    use SlugRelationship;

    /**
     * @var list<string>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $with = ['slug'];

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
