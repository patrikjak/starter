<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Observers\Metadata;

use Illuminate\Database\Eloquent\Model;
use Patrikjak\Starter\Dto\Metadata\CreateMetadata;
use Patrikjak\Starter\Models\Common\Visitable;
use Patrikjak\Starter\Models\Metadata\Metadatable;
use Patrikjak\Starter\Repositories\Contracts\Metadata\MetadataRepository;

final readonly class MetadatableObserver
{
    public function __construct(private MetadataRepository $metadataRepository)
    {
    }

    public function created(Metadatable $metadatable): void
    {
        assert($metadatable instanceof Model);

        $this->metadataRepository->create(new CreateMetadata(
            $metadatable->getMetaTitle(),
            $metadatable->getMetadatableId(),
            $metadatable->getMorphClass(),
            canonicalUrl: $metadatable instanceof Visitable ? $metadatable->getUrl() : null,
        ));
    }

    public function deleted(Metadatable $metadatable): void
    {
        $this->metadataRepository->delete($metadatable->getMetadata()->id);
    }
}