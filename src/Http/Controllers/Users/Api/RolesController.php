<?php

namespace Patrikjak\Starter\Http\Controllers\Users\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Services\Users\RolesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class RolesController
{
    use TableParts;

    public function tableParts(TableParametersRequest $request, RolesTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }
}
