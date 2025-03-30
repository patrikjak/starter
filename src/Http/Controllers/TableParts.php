<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;
use Patrikjak\Utils\Table\Services\SupportsPagination;
use Patrikjak\Utils\Table\Services\TableProviderInterface;

trait TableParts
{
    public function getTableParts(TableParametersRequest $request, SupportsPagination $tableProvider): JsonResponse
    {
        assert($tableProvider instanceof TableProviderInterface);

        return new JsonResponse($tableProvider->getHtmlParts(
            $request->getTableParameters($tableProvider->getTableId()),
        ));
    }
}