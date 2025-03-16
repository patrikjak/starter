<?php

namespace Patrikjak\Starter\Services\Metadata;

use Patrikjak\Starter\Models\Page;
use Patrikjak\Starter\Repositories\Contracts\PageRepository;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

class PagesTableProvider extends BasePaginatedTableProvider
{
    public function __construct(private readonly PageRepository $pageRepository)
    {
    }

    public function getTableId(): string
    {
        return 'pages-table';
    }

    /**
     * @inheritDoc
     */
    public function getHeader(): ?array
    {
        return [
            'name' => __('pjstarter::pages.meta_data.pages.name'),
            'slug' => __('pjstarter::pages.meta_data.pages.slug'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getPageData()->map(static function (Page $page) {
            return [
                'id' => $page->id,
                'name' => CellFactory::simple($page->name),
                'slug' => CellFactory::simple($page->slug),
            ];
        })->toArray();
    }

    public function showOrder(): bool
    {
        return true;
    }

    protected function getPaginator(): TablePaginator
    {
        return PaginatorFactory::createFromLengthAwarePaginator($this->pageRepository->getAllPaginated(
            $this->getPageSize(),
            $this->getCurrentPage(),
            '',
        ));
    }
}