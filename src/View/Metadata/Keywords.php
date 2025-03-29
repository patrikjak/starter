<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Metadata;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Keywords extends Component
{
    public function __construct(public readonly ?string $keywords)
    {
    }

    public function render(): View
    {
        return $this->view('pjstarter::metadata.keywords');
    }

    public function shouldRender(): bool
    {
        return $this->keywords !== null;
    }
} 