<?php

namespace App\Http\Controllers\StatusCodes;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HttpStatusController
{
    /**
     * Handle the incoming request for any HTTP status code
     *
     * @param int $code The HTTP status code
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $code): JsonResponse
    {
        // Validate the code is a valid HTTP status code
        if (!isset(Response::$statusTexts[$code])) {
            $code = 404;
        }

        return response()->json([
            'message' => Response::$statusTexts[$code],
            'status' => $code
        ], $code);
    }
}
