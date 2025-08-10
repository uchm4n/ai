<?php

namespace App\Http\Controllers\StatusCodes;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Http_Random
{
    /**
     * Invoke the controller
     *
     * @param  string  $codes  Comma-separated list of HTTP status codes
     */
    public function __invoke(Request $request, string $codes): JsonResponse
    {
        $code = str($codes)
            ->explode(',')
            ->random();

        return response()->json([
            'message' => Response::$statusTexts[$code],
            'status'  => (int) $code,
        ], $code);
    }
}
