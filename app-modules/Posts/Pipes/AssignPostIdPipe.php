<?php

namespace Modules\Posts\Pipes;

use Closure;
use Illuminate\Support\Facades\Cache;

class AssignPostIdPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        $id = $payload['id'] ?? null;
        if (!$id) {
            // Generate next ID = max(base ids + override ids) + 1
            $base = $payload['base'] ?? [];
            $max  = 0;
            foreach ($base as $item) {
                if (is_array($item) && isset($item['id'])) {
                    $max = max($max, (int)$item['id']);
                }
            }
            $ids = Cache::get(ApplyOverridesPipe::OVERRIDE_IDS_KEY, []);
            if (is_array($ids)) {
                foreach ($ids as $oid) {
                    $max = max($max, (int)$oid);
                }
            }
            $id = $max + 1;
        }

        $payload['id']          = (int)$id;
        $payload['input']['id'] = (int)$id;

        return $next($payload);
    }
}
