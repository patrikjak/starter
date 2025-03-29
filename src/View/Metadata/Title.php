<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Metadata;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Title extends Component
{
    public function __construct(public ?string $title)
    {
    }

    public function render(): View
    {
        $this->title ??= config('pjstarter.app_name');

        return $this->view('pjstarter::metadata.title');
    }
} 