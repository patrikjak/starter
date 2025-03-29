<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Layout;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\Metadata\Metadatable;
use Patrikjak\Starter\Services\Slugs\SlugService;

class Header extends Component
{
    public function __construct(private readonly Request $request, private readonly SlugService $slugService)
    {
    }

    public function render(): View
    {
        $sluggable = $this->slugService->getSluggableFromUrl($this->request->url());
        $metadata = $sluggable instanceof Metadatable ? $sluggable->metadata : null;

        return view('pjstarter::layout.header', [
            'metadata' => $metadata,
        ]);
    }

    private function loadMetadata(?Metadata $metadata): void
    {

    }
}
