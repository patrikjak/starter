<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Authors\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\Authors\StoreAuthorRequest;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Services\Authors\AuthorService;
use Patrikjak\Starter\Services\Authors\AuthorsTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class AuthorsController
{
    use TableParts;

    /**
     * @throws Exception
     */
    public function store(StoreAuthorRequest $request, AuthorService $authorService): void
    {
        $authorService->saveAuthor($request->getName(), $request->getProfilePicture());
    }

    public function update(StoreAuthorRequest $request, Author $author, AuthorService $authorService): void
    {
        $authorService->updateAuthor(
            $author,
            $request->getName(),
            $request->getProfilePicture(),
            $request->getFilesToDelete('profile_picture'),
        );
    }

    public function destroy(Author $author, AuthorService $authorService): JsonResponse
    {
        $authorService->deleteAuthor($author);

        return new JsonResponse([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.authors.author_deleted'),
            'level' => 'success',
        ]);
    }

    public function tableParts(TableParametersRequest $request, AuthorsTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }
}
