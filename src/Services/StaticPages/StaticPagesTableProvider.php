<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\StaticPages;

use Illuminate\Contracts\Auth\Access\Gate;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
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
        private readonly Gate $gate,
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
                'url' => CellFactory::simple(sprintf(
                    '%s/%s%s',
                    config('app.url'),
                    $page->slug->prefix === null ? '' : sprintf('%s/', $page->slug->prefix),
                    $page->slug->slug,
                )),
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
        if ($this->gate->denies('update', StaticPage::class)) {
            return [];
        }

        return [
            new Item(__('pjstarter::general.edit'), 'edit'),
            new Item(__('pjstarter::general.delete'), 'delete', type: Type::DANGER),
        ];
    }

    protected function getPaginator(): TablePaginator
    {
        return PaginatorFactory::createFromLengthAwarePaginator($this->staticPageRepository->getAllPaginated(
            $this->getPageSize(),
            $this->getCurrentPage(),
            route('api.static-pages.table-parts'),
        ));
    }
}