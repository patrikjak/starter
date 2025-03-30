<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Metadata\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\Metadata\UpdateMetadataRequest;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Repositories\Contracts\Metadata\MetadataRepository;
use Patrikjak\Starter\Services\Metadata\MetadataTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class MetadataController
{
    use TableParts;

    public function update(
        Metadata $metadata,
        UpdateMetadataRequest $request,
        MetadataRepository $metadataRepository,
    ): void {
        $metadataRepository->update($metadata->id, $request->getUpdateMetadata());
    }

    public function tableParts(TableParametersRequest $request, MetadataTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }
}