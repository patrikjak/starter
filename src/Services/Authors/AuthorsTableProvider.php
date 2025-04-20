<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Authors;

use Illuminate\Auth\AuthManager;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\Authors\AuthorPolicy;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Repositories\Contracts\Authors\AuthorRepository;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

final class AuthorsTableProvider extends BasePaginatedTableProvider
{
    private User $user;

    public function __construct(
        private readonly AuthorRepository $authorRepository,
        private readonly AuthManager $authManager,
    ) {
        $user = $this->authManager->user();
        assert($user instanceof User);

        $this->user = $user;
    }

    public function getTableId(): string
    {
        return 'authors-table';
    }

    /**
     * @return array<string, string>
     */
    public function getHeader(): array
    {
        return [
            'name' => __('pjstarter::pages.authors.name'),
            'created_at' => __('pjstarter::general.created_at'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $canViewAuthor = $this->user->hasPermission(AuthorPolicy::FEATURE_NAME, BasePolicy::VIEW);

        return $this->getPageData()->map(static function (Author $author) use ($canViewAuthor) {
            return [
                'id' => $author->id,
                'name' => $canViewAuthor
                    ? CellFactory::link($author->name, route('authors.show', ['author' => $author->id]))
                    : CellFactory::simple($author->name),
                'created_at' => CellFactory::simple($author->created_at->format('d.m.Y H:i')),
            ];
        })->toArray();
    }

    public function showOrder(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        $actions = [];

        if ($this->user->hasPermission(AuthorPolicy::FEATURE_NAME, BasePolicy::EDIT)) {
            $actions[] = new Item(
                __('pjstarter::general.edit'),
                'edit',
                Icon::EDIT,
                href: static function (array $row) {
                    return route('authors.edit', ['author' => $row['id']]);
                },
            );
        }

        if ($this->user->hasPermission(AuthorPolicy::FEATURE_NAME, BasePolicy::DELETE)) {
            $actions[] = new Item(
                __('pjstarter::general.delete'),
                'delete',
                Icon::TRASH,
                Type::DANGER,
                href: static function (array $row) {
                    return route('api.authors.destroy', ['author' => $row['id']]);
                },
                method: 'DELETE',
            );
        }

        return $actions;
    }

    protected function getPaginator(): TablePaginator
    {
        return PaginatorFactory::createFromLengthAwarePaginator($this->authorRepository->getAllPaginated(
            $this->getPageSize(),
            $this->getCurrentPage(),
            route('api.authors.table-parts'),
        ));
    }
}