<?php

declare(strict_types=1);

namespace Patrikjak\Starter\View;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Starter\Enums\Editorjs\EditorTool;

class Editor extends Component
{
    /**
     * @param Collection<int, EditorTool> $tools
     */
    public function __construct(
        public readonly Collection $tools,
        public readonly ?string $context = null,
        public readonly ?string $uploadUrl = null,
        public readonly ?string $fetchUrl = null,
        public readonly ?string $contentUrl = null,
    ) {
    }

    public function render(): View
    {
        return $this->view('pjstarter::components.editor');
    }

    public function toolsValue(): string
    {
        return $this->tools->map(static fn (EditorTool $tool) => $tool->value)->implode(',');
    }

    public function resolvedUploadUrl(): ?string
    {
        if ($this->uploadUrl !== null) {
            return $this->uploadUrl;
        }

        if ($this->context !== null) {
            return route('admin.api.content.upload-image', ['context' => $this->context]);
        }

        return null;
    }

    public function resolvedFetchUrl(): ?string
    {
        if ($this->fetchUrl !== null) {
            return $this->fetchUrl;
        }

        if ($this->context !== null) {
            return route('admin.api.content.fetch-image', ['context' => $this->context]);
        }

        return null;
    }
}
