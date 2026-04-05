<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Support\Content;

use Patrikjak\Starter\Exceptions\Content\ContentContextNotFoundException;
use Patrikjak\Starter\ValueObjects\Content\ContentContextDefinition;

class ContentContextRegistry
{
    /**
     * @var array<string, ContentContextDefinition>
     */
    private array $contexts = [];

    public function register(string $key, ContentContextDefinition $definition): void
    {
        $this->contexts[$key] = $definition;
    }

    public function get(string $key): ContentContextDefinition
    {
        if (!isset($this->contexts[$key])) {
            throw new ContentContextNotFoundException($key);
        }

        return $this->contexts[$key];
    }
}
