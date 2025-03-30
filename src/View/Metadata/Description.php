<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Metadata;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Description extends Component
{
    public function __construct(public readonly ?string $description)
    {
    }

    public function render(): View
    {
        return $this->view('pjstarter::metadata.description');
    }

    public function shouldRender(): bool
    {
        return $this->description !== null;
    }
} 