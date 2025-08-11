<?php

namespace Modules\StatusCodes\Pipes;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class StatusPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        $code = str($payload)->explode(',');

        // If multiple codes are provided, pick one randomly
        if ($code->count() > 1) {
            $code = $code->random();
        } else {
            $code = $code->first();
        }


        // Validate the code is a valid HTTP status code
        if (!isset(Response::$statusTexts[$code])) {
            $code = 404;
        }

        return $next([
            'message' => Response::$statusTexts[$code],
            'status'  => (int)$code,
        ]);
    }
}
