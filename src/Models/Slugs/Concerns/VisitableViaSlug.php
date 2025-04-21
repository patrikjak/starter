<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Slugs\Concerns;

trait VisitableViaSlug
{
    public function getUrl(): string
    {
        return sprintf(
            '%s%s%s',
            config('app.url'),
            $this->slug->prefix === null ? '' : sprintf('/%s', $this->slug->prefix),
            $this->slug->slug === '' ? '' : sprintf('/%s', $this->slug->slug),
        );
    }
}