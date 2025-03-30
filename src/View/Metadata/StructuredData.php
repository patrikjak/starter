<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Metadata;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StructuredData extends Component
{
    public function __construct(public readonly ?string $structuredData)
    {
    }

    public function render(): View
    {
        return $this->view('pjstarter::metadata.structured-data');
    }

    public function shouldRender(): bool
    {
        return $this->structuredData !== null;
    }
} 