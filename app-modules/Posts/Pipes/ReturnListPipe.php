<?php

namespace Modules\Posts\Pipes;

use Closure;

class ReturnListPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        $list = is_array($payload) && isset($payload['list']) ? $payload['list'] : [];

        return $next($list);
    }
}
