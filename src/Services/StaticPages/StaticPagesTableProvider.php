<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\StaticPages;

use Illuminate\Auth\AuthManager;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\StaticPages\StaticPageRepository;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

class StaticPagesTableProvider extends BasePaginatedTableProvider
{
    public function __construct(
        private readonly StaticPageRepository $staticPageRepository,
        private readonly AuthManager $authManager,
    ) {
    }

    public function getTableId(): string
    {
        return 'static-pages-table';
    }

    /**
     * @inheritDoc
     */
    public function getHeader(): ?array
    {
        return [
            'name' => __('pjstarter::pages.static_pages.name'),
            'url' => __('pjstarter::pages.static_pages.url'),
            'created_at' => __('pjstarter::general.created_at'),
            'updated_at' => __('pjstarter::general.updated_at'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getPageData()->map(static function (StaticPage $page) {
            return [
                'id' => $page->id,
                'name' => CellFactory::simple($page->name),
                'url' => CellFactory::link($page->getUrl(), $page->getUrl()),
                'created_at' => CellFactory::simple($page->created_at->format('d.m.Y H:i')),
                'updated_at' => CellFactory::simple($page->updated_at->format('d.m.Y H:i')),
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
        $user = $this->authManager->user();
        assert($user instanceof User);

        $actions = [];

        if ($user->canEditStaticPage()) {
            $actions[] = new Item(__('pjstarter::general.edit'), 'edit', href: static function (array $row) {
                return route('admin.static-pages.edit', ['staticPage' => $row['id']]);
            });
        }

        if ($user->canDeleteStaticPage()) {
            $actions[] = new Item(
                __('pjstarter::general.delete'),
                'delete',
                type: Type::DANGER,
                href: static function (array $row) {
                    return route('admin.api.static-pages.destroy', ['staticPage' => $row['id']]);
                },
                method: 'DELETE',
            );
        }

        return $actions;
    }

    protected function getPaginator(): TablePaginator
    {
        return PaginatorFactory::createFromLengthAwarePaginator($this->staticPageRepository->getAllPaginated(
            $this->getPageSize(),
            $this->getCurrentPage(),
            route('admin.api.static-pages.table-parts'),
        ));
    }
}