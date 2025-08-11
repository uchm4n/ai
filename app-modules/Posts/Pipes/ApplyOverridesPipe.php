<?php

namespace Modules\Posts\Pipes;

use Closure;
use Illuminate\Support\Facades\Cache;

class ApplyOverridesPipe
{
    public const OVERRIDE_IDS_KEY = 'posts:override_ids';

    public function handle(mixed $payload, Closure $next): mixed
    {
        $base = $payload['base'] ?? [];
        $ids  = Cache::get(self::OVERRIDE_IDS_KEY, []);
        if (!is_array($ids)) {
            $ids = [];
        }

        // Index base by id for quick override
        $indexed = [];
        foreach ($base as $post) {
            if (is_array($post) && isset($post['id'])) {
                $indexed[(string)$post['id']] = $post;
            }
        }

        // Apply overrides (replace or add) by reading each cached per-id override
        foreach ($ids as $id) {
            $override = Cache::get('posts:override:'.(string) $id);
            if (is_array($override)) {
                $indexed[(string)$id] = $override;
            }
        }

        // Rebuild list, sorted by id asc
        ksort($indexed, SORT_NATURAL);
        $payload['list'] = array_values($indexed);

        return $next($payload);
    }
}
