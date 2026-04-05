<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Controllers\Content;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Patrikjak\Starter\Exceptions\Content\ContentAccessDeniedException;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Services\Content\ContentImageService;
use Patrikjak\Starter\Support\Content\ContentContextRegistry;
use Patrikjak\Starter\ValueObjects\Content\ContentContextDefinition;

class ContentController
{
    public function uploadImage(
        Request $request,
        ContentImageService $contentImageService,
        ContentContextRegistry $registry,
    ): JsonResponse {
        $context = $registry->get($request->query('context', ''));

        $this->ensureUserIsAuthorized($request, $context);

        $image = $request->file('image');

        if ($image === null) {
            return new JsonResponse(['success' => false]);
        }

        return new JsonResponse([
            'success' => true,
            'file' => [
                'url' => $contentImageService->saveImage($image, $context),
            ],
        ]);
    }

    public function fetchImage(
        Request $request,
        ContentImageService $contentImageService,
        ContentContextRegistry $registry,
    ): JsonResponse {
        $context = $registry->get($request->query('context', ''));

        $this->ensureUserIsAuthorized($request, $context);

        return new JsonResponse([
            'success' => true,
            'file' => [
                'url' => $contentImageService->saveImageFromUrl($request->input('url'), $context),
            ],
        ]);
    }

    private function ensureUserIsAuthorized(Request $request, ContentContextDefinition $context): void
    {
        $user = $request->user();

        if (
            $user === null
            || (
                $user->cannot(BasePolicy::CREATE, $context->modelClass)
                && $user->cannot(BasePolicy::EDIT, $context->modelClass)
            )
        ) {
            throw new ContentAccessDeniedException($context->modelClass);
        }
    }
}
