<?php

namespace Modules\StatusCodes\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\StatusCodes\Actions\StatusAction;

class HttpStatusController
{
    public function status($codes, StatusAction $action): JsonResponse
    {
        $result = $action($codes);

        return response()->json(['data' => $result]);
    }
}
