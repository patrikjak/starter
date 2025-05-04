<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Metadata\Concerns;

use Patrikjak\Starter\Models\Common\Visitable;
use Patrikjak\Starter\Models\Metadata\Metadata;

trait MetadatableDefaults
{
    public function getMetaTitle(): string
    {
        return $this->getTitleFromTemplate($this->name);
    }

    public function getCanonicalUrl(): ?string
    {
        if ($this instanceof Visitable) {
            return $this->getUrl();
        }

        return null;
    }

    public function getMetadatableId(): string
    {
        return $this->id;
    }

    public function getMetadata(): ?Metadata
    {
        assert($this->metadata instanceof Metadata || $this->metadata === null);

        return $this->metadata;
    }

    public function getPageName(): string
    {
        return $this->name;
    }

    protected function getTitleFromTemplate(string $dynamicPart): string
    {
        $metaTitleFormat = config('pjstarter.meta_title_format');

        return str_replace(['{title}', '{appName}'], [$dynamicPart, config('pjstarter.app_name')], $metaTitleFormat);
    }
}