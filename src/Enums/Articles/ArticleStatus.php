<?php

namespace Patrikjak\Starter\Enums\Articles;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Traits\EnumValues;

enum ArticleStatus: string
{
    use EnumValues;

    case DRAFT = 'draft';

    case PUBLISHED = 'published';

    case ARCHIVED = 'archived';

    public static function asOptions(): Collection
    {
        return new Collection(self::getAll())->mapWithKeys(
            static fn (ArticleStatus $status) => [$status->value => __(
                sprintf('pjstarter::pages.articles.statuses.%s', $status->value)),
            ]);
    }
}
