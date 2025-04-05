<?php

namespace Patrikjak\Starter\Http\Controllers\Users\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Services\Users\UsersTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class UsersController
{
    use TableParts;

    public function tableParts(TableParametersRequest $request, UsersTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }
}
