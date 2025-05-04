<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Enums\Articles;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Traits\EnumValues;

enum ArticleStatus: string
{
    use EnumValues;

    case DRAFT = 'draft';

    case PUBLISHED = 'published';

    case ARCHIVED = 'archived';

    /**
     * @lang("pjstarter::pages.articles.statuses.draft")
     * @lang("pjstarter::pages.articles.statuses.published")
     * @lang("pjstarter::pages.articles.statuses.archived")
     */
    public function toLabel(): string
    {
        return __(sprintf('pjstarter::pages.articles.statuses.%s', $this->value));
    }

    public static function asOptions(): Collection
    {
        return new Collection(self::getAll())->mapWithKeys(
            static fn (ArticleStatus $status) => [$status->value => __(
                sprintf('pjstarter::pages.articles.statuses.%s', $status->value)),
            ]);
    }
}
