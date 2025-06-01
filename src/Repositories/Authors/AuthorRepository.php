<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Authors;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Repositories\Contracts\Authors\AuthorRepository as AuthorRepositoryContract;
use Patrikjak\Starter\Repositories\SupportsPagination;

class AuthorRepository implements AuthorRepositoryContract
{
    use SupportsPagination;

    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return $this->getAllPaginatedByModel(Author::class, $pageSize, $page, $refreshUrl);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection
    {
        return Author::all();
    }

    public function getById(string $id): Author
    {
        return Author::findOrFail($id);
    }

    public function create(string $name, ?string $profilePicture): void
    {
        $model = new Author();

        $model->name = $name;
        $model->profile_picture = $profilePicture;

        $model->save();
    }

    public function update(string $id, string $name, ?string $profilePicture): void
    {
        $model = Author::findOrFail($id);
        assert($model instanceof Author);

        $model->name = $name;
        $model->profile_picture = $profilePicture;

        $model->save();
    }

    public function delete(string $id): void
    {
        $model = Author::findOrFail($id);

        $model->delete();
    }
}