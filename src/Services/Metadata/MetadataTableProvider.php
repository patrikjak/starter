<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Metadata;

use Illuminate\Auth\AuthManager;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Metadata\MetadataRepository;
use Patrikjak\Starter\Support\StringCropper;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterableColumn;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Filter\FilterableFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

class MetadataTableProvider extends BasePaginatedTableProvider
{
    use StringCropper;

    private User $user;

    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly AuthManager $authManager,
    ) {
        $user = $this->authManager->user();
        assert($user instanceof User);

        $this->user = $user;
    }

    public function getTableId(): string
    {
        return 'metadata-table';
    }

    /**
     * @inheritDoc
     */
    public function getHeader(): ?array
    {
        return [
            'page_name' => __('pjstarter::pages.metadata.page_name'),
            'title' => __('pjstarter::pages.metadata.meta_title'),
            'description' => __('pjstarter::pages.metadata.meta_description'),
            'keywords' => __('pjstarter::pages.metadata.meta_keywords'),
            'canonical_url' => __('pjstarter::pages.metadata.canonical_url'),
            'structured_data' => __('pjstarter::pages.metadata.structured_data'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $canViewDetail = $this->user->canViewMetadata();

        return $this->getPageData()->map(function (Metadata $metadata) use ($canViewDetail) {
            $canonicalUrl = $metadata->canonical_url === null
                ? CellFactory::simple('')
                : CellFactory::link($metadata->canonical_url, $metadata->canonical_url);

            return [
                'id' => $metadata->id,
                'title' => $canViewDetail
                    ? CellFactory::link(
                        $this->getCroppedString($metadata->title),
                        route('admin.metadata.show', ['metadata' => $metadata->id]),
                    )
                    : CellFactory::simple($this->getCroppedString($metadata->title)),
                'description' => CellFactory::simple($this->getCroppedString($metadata->description)),
                'keywords' => CellFactory::simple($metadata->keywords ?? ''),
                'canonical_url' => $canonicalUrl,
                'structured_data' => CellFactory::simple($this->getCroppedString($metadata->structured_data)),
                'page_name' => CellFactory::double(
                    $metadata->metadatable->getPageName(),
                    $metadata->metadatable->getMetadatableTypeLabel(),
                ),
            ];
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        if (!$this->user->canEditMetadata()) {
            return [];
        }

        return [
            new Item(__('pjstarter::general.edit'), 'edit', href: static function (array $row) {
                return route('admin.metadata.edit', ['metadata' => $row['id']]);
            }),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getSortableColumns(): array
    {
        $columnsMask = Metadata::COLUMNS_MASK;

        return [
            new SortableColumn(__('pjstarter::pages.metadata.meta_title'), $columnsMask['title']),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getFilterableColumns(): array
    {
        $columnsMask = Metadata::COLUMNS_MASK;

        return [
            new FilterableColumn(
                __('pjstarter::pages.metadata.page_name'),
                $columnsMask['sp.name'],
                FilterableFactory::text(),
            ),
            new FilterableColumn(
                __('pjstarter::pages.metadata.meta_title'),
                $columnsMask['title'],
                FilterableFactory::text(),
            ),
            new FilterableColumn(
                __('pjstarter::pages.metadata.meta_description'),
                $columnsMask['description'],
                FilterableFactory::text(),
            ),
            new FilterableColumn(
                __('pjstarter::pages.metadata.meta_keywords'),
                $columnsMask['keywords'],
                FilterableFactory::text(),
            ),
            new FilterableColumn(
                __('pjstarter::pages.metadata.canonical_url'),
                $columnsMask['canonical_url'],
                FilterableFactory::text(),
            ),
        ];
    }

    protected function getPaginator(): TablePaginator
    {
        return PaginatorFactory::createFromLengthAwarePaginator($this->metadataRepository->getAllPaginated(
            $this->getPageSize(),
            $this->getCurrentPage(),
            route('admin.api.metadata.table-parts'),
            $this->getSortCriteria(),
            $this->getFilterCriteria(),
        ));
    }
}