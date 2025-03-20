<?php

namespace Patrikjak\Starter\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;
use Patrikjak\Utils\Table\Services\SupportsPagination;

trait TableParts
{
    public function getTableParts(TableParametersRequest $request, SupportsPagination $tableProvider): JsonResponse
    {
        return new JsonResponse($tableProvider->getHtmlParts(
            $request->getTableParameters($tableProvider->getTableId()),
        ));
    }
}