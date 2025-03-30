<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Metadata;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CanonicalUrl extends Component
{
    public function __construct(public readonly ?string $canonicalUrl)
    {
    }

    public function render(): View
    {
        return $this->view('pjstarter::metadata.canonical-url');
    }

    public function shouldRender(): bool
    {
        return $this->canonicalUrl !== null;
    }
} 