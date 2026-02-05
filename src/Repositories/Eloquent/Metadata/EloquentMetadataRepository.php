<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Eloquent\Metadata;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Dto\Metadata\CreateMetadata;
use Patrikjak\Starter\Dto\Metadata\UpdateMetadata;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Repositories\Contracts\Metadata\MetadataRepository as MetadataRepositoryContract;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Common\Services\QueryBuilder\FilterService;
use Patrikjak\Utils\Common\Services\QueryBuilder\SortService;

readonly class EloquentMetadataRepository implements MetadataRepositoryContract
{
    public function __construct(private SortService $sortService, private FilterService $filterService)
    {
    }

    public function getAllPaginated(
        int $pageSize,
        int $page,
        string $refreshUrl,
        ?SortCriteria $sortCriteria,
        ?FilterCriteria $filterCriteria,
    ): LengthAwarePaginator {
        $columnsMask = Metadata::COLUMNS_MASK;

        $query = Metadata::with('metadatable')
            ->leftJoin('static_pages as sp', static function ($join): void {
                $join->on('metadata.metadatable_id', '=', 'sp.id')
                    ->where('metadata.metadatable_type', '=', StaticPage::class);
            })
            ->leftJoin('article_categories as ac', static function ($join): void {
                $join->on('metadata.metadatable_id', '=', 'ac.id')
                    ->where('metadata.metadatable_type', '=', ArticleCategory::class);
            })
            ->select(
                'metadata.*',
                'sp.name AS static_page_name',
                'sp.id AS static_page_id',
                'ac.name AS article_category_name',
                'ac.id AS article_category_id',
            );

        $this->sortService->applySort($query, $sortCriteria, $columnsMask);
        $this->filterService->applyFilter($query, $filterCriteria, $columnsMask);

        return $query->paginate($pageSize, page: $page)->withPath($refreshUrl);
    }

    public function create(CreateMetadata $createMetadata): void
    {
        $metadata = new Metadata();

        $metadata->title = $createMetadata->title;
        $metadata->description = $createMetadata->description;
        $metadata->keywords = $createMetadata->keywords;
        $metadata->canonical_url = $createMetadata->canonicalUrl;
        $metadata->structured_data = $createMetadata->structuredData;
        $metadata->metadatable_id = $createMetadata->metadatableId;
        $metadata->metadatable_type = $createMetadata->metadatableType;

        $metadata->save();
    }

    public function update(string $id, UpdateMetadata $updateMetadata): void
    {
        $metadata = Metadata::findOrFail($id);

        $metadata->title = $updateMetadata->title;
        $metadata->description = $updateMetadata->description;
        $metadata->keywords = $updateMetadata->keywords;
        $metadata->canonical_url = $updateMetadata->canonicalUrl;
        $metadata->structured_data = $updateMetadata->structuredData;

        $metadata->save();
    }

    public function delete(string $id): void
    {
        Metadata::destroy($id);
    }
}
