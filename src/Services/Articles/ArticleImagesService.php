<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Articles;

use Illuminate\Filesystem\FilesystemManager;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Image\Image;
use Patrikjak\Starter\Dto\Editorjs\EditorData;
use Patrikjak\Starter\Enums\Editorjs\BlockType;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleRepository;

readonly class ArticleImagesService
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private FilesystemManager $filesystemManager,
    ) {
    }

    public function deleteUnusedImages(): void
    {
        $articleContents = $this->articleRepository->getAllContents();
        $allImages = [];
        $existingImages = $this->filesystemManager->disk('public')->allFiles('articles/images');
        $existingImages = array_map(
            static fn (string $file) => basename($file),
            $existingImages,
        );

        foreach ($articleContents as $articleContent) {
            assert($articleContent instanceof Article);

            $content = $articleContent->content;
            assert($content instanceof EditorData);

            foreach ($content->blocks as $block) {
                if ($block->getType() === BlockType::Image) {
                    assert($block instanceof Image);
                    $allImages[] = basename($block->url);
                }
            }
        }

        $allImages = array_unique($allImages);
        $toDelete = array_diff($existingImages, $allImages);
        $toDelete = array_map(
            static fn (string $file) => sprintf('articles/images/%s', $file),
            $toDelete,
        );

        $this->filesystemManager->disk('public')->delete($toDelete);
    }
}