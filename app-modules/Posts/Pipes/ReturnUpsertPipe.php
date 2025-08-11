<?php

namespace Modules\Posts\Pipes;

use Closure;

class ReturnUpsertPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        $post    = is_array($payload) && isset($payload['post']) ? $payload['post'] : null;
        $created = is_array($payload) && array_key_exists('created', $payload) ? (bool)$payload['created'] : false;

        return $next([
            'post'    => $post,
            'created' => $created,
        ]);
    }
}
