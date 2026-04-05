<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Services\Content;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Patrikjak\Starter\Exceptions\Content\ContentImageStoreFailedException;
use Patrikjak\Starter\Exceptions\Content\InvalidImageUrlException;
use Patrikjak\Starter\ValueObjects\Content\ContentContextDefinition;

readonly class ContentImageService
{
    public function __construct(private FilesystemManager $filesystemManager)
    {
    }

    public function saveImage(UploadedFile $file, ContentContextDefinition $context): string
    {
        $disk = $context->disk ?? $this->filesystemManager->getDefaultDriver();
        $path = $file->store($context->directory, $disk);

        if ($path === false) {
            throw new ContentImageStoreFailedException();
        }

        return $this->filesystemManager->disk($disk)->url($path);
    }

    public function saveImageFromUrl(string $url, ContentContextDefinition $context): string
    {
        if (!str_starts_with($url, 'https://')) {
            throw new InvalidImageUrlException($url);
        }

        $disk = $context->disk ?? $this->filesystemManager->getDefaultDriver();
        $imageName = Str::random(40);
        $parsedPath = parse_url($url, PHP_URL_PATH) ?? '';
        $extension = pathinfo($parsedPath, PATHINFO_EXTENSION);
        $path = $extension !== ''
            ? sprintf('%s/%s.%s', $context->directory, $imageName, $extension)
            : sprintf('%s/%s', $context->directory, $imageName);

        $contents = file_get_contents($url);

        if ($contents === false) {
            throw new ContentImageStoreFailedException();
        }

        $this->filesystemManager->disk($disk)->put($path, $contents);

        return $this->filesystemManager->disk($disk)->url($path);
    }
}
