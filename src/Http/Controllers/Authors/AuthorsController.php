<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Authors;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Services\Authors\AuthorService;
use Patrikjak\Starter\Services\Authors\AuthorsTableProvider;
use Patrikjak\Utils\Common\Dto\Image;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class AuthorsController
{
    public function index(TableParametersRequest $request, AuthorsTableProvider $authorsTableProvider): View
    {
        return view('pjstarter::pages.authors.index', [
            'authorsTable' => $authorsTableProvider->getTable(
                $request->getTableParameters($authorsTableProvider->getTableId()),
            ),
        ]);
    }

    public function show(Author $author): View
    {
        return view('pjstarter::pages.authors.show', [
            'author' => $author,
        ]);
    }

    public function create(): View
    {
        return view('pjstarter::pages.authors.create');
    }

    public function edit(Author $author): View
    {
        return view('pjstarter::pages.authors.edit', [
            'author' => $author,
            'profilePicture' => $author->profile_picture
                ? [new Image($author->getProfilePicturePath(), $author->profile_picture)]
                : [],
        ]);
    }
}
