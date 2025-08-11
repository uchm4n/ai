<?php

namespace Modules\StatusCodes;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HttpStatusController
{
    /**
     * Handle the incoming request for any HTTP status code
     *
     * @param int|string $code The HTTP status code
     */
    public function __invoke(int|string $code): JsonResponse
    {
        $code = str($code)
            ->explode(',')
            ->random();

        // Validate the code is a valid HTTP status code
        if (!isset(Response::$statusTexts[$code])) {
            $code = 404;
        }

        return response()->json([
            'message' => Response::$statusTexts[$code],
            'status'  => (int)$code,
        ], $code);
    }
}
