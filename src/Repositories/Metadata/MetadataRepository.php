<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Metadata;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Dto\Metadata\CreateMetadata;
use Patrikjak\Starter\Dto\Metadata\UpdateMetadata;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Repositories\Contracts\Metadata\MetadataRepository as MetadataRepositoryContract;

readonly class MetadataRepository implements MetadataRepositoryContract
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return Metadata::with('metadatable')->paginate($pageSize, page: $page)->withPath($refreshUrl);
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