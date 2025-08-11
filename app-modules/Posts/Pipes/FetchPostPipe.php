<?php

namespace Modules\Posts\Pipes;

use Closure;
use Illuminate\Support\Facades\Cache;

class FetchPostPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        $id   = (string)($payload['id'] ?? '');
        $base = $payload['base'] ?? [];

        $post = Cache::get("posts:override:$id");

        if (!$post) {
            foreach ($base as $item) {
                if (is_array($item) && (string)($item['id'] ?? '') === $id) {
                    $post = $item;
                    break;
                }
            }
        }

        $payload['post'] = $post ?: null;

        return $next($payload);
    }
}
