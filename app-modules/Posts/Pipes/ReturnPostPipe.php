<?php

namespace Modules\Posts\Pipes;

use Closure;

class ReturnPostPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        $post = is_array($payload) && isset($payload['post']) ? $payload['post'] : null;

        return $next([
            'post'  => $post,
            'found' => (bool) $post,
        ]);
    }
}
