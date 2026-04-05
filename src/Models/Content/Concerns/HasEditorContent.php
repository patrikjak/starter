<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Models\Content\Concerns;

use Patrikjak\Starter\Casts\EditorjsDataCast;

trait HasEditorContent
{
    /**
     * @return array<string, string>
     */
    protected function getEditorContentCasts(): array
    {
        return [
            'content' => EditorjsDataCast::class,
        ];
    }
}
