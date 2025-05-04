<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Slugs\Concerns;

use Patrikjak\Starter\Models\Slugs\Slug;

trait VisitableViaSlug
{
    public function getUrl(): string
    {
        assert($this->slug instanceof Slug);

        return sprintf(
            '%s%s%s',
            config('app.url'),
            $this->slug->prefix === null ? '' : sprintf('/%s', $this->slug->prefix),
            $this->slug->slug === '' ? '' : sprintf('/%s', $this->slug->slug),
        );
    }
}