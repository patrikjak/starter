<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Observers\Metadata;

use Illuminate\Foundation\Console\Kernel;

final readonly class MetadataObserver
{
    public function __construct(private Kernel $kernel)
    {
    }

    public function updated(): void
    {
        $this->kernel->call('view:clear');
    }
}