<?php

namespace Patrikjak\Starter\Services\Metadata;

use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Repositories\Contracts\PageSlugRepository;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

class PageSlugsTableProvider extends BasePaginatedTableProvider
{
    public function __construct(private readonly PageSlugRepository $pageRepository)
    {
    }

    public function getTableId(): string
    {
        return 'page-slugs-table';
    }

    /**
     * @inheritDoc
     */
    public function getHeader(): ?array
    {
        return [
            'name' => __('pjstarter::pages.metadata.pages.name'),
            'slug' => __('pjstarter::pages.metadata.pages.slug'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getPageData()->map(static function (PageSlug $page) {
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

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        return [
            new Item(__('pjstarter::general.edit'), 'edit'),
            new Item(__('pjstarter::general.delete'), 'delete', type: Type::DANGER),
        ];
    }

    protected function getPaginator(): TablePaginator
    {
        return PaginatorFactory::createFromLengthAwarePaginator($this->pageRepository->getAllPaginated(
            $this->getPageSize(),
            $this->getCurrentPage(),
            route('api.page-slugs.table-parts'),
        ));
    }
}