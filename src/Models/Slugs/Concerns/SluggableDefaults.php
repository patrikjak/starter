<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Slugs\Concerns;

use Patrikjak\Starter\Models\Slugs\Slug;

trait SluggableDefaults
{
    public function getSluggableId(): string
    {
        return $this->id;
    }

    public function getSlug(): Slug
    {
        assert($this->slug instanceof Slug);

        return $this->slug;
    }

    public function getPrefix(): ?string
    {
        return null;
    }
}