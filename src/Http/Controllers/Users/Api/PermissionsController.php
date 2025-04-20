<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Users\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Services\Users\PermissionsTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class PermissionsController
{
    use TableParts;

    public function tableParts(TableParametersRequest $request, PermissionsTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }
}
