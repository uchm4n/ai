<?php

namespace Modules\Posts\Pipes;

use Closure;
use Illuminate\Support\Facades\Cache;

class SaveOverridePipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        $input = $payload['input'] ?? [];
        $id    = (int) ($payload['id'] ?? 0);
        $base  = $payload['base'] ?? [];

        // Find current record from override or base
        $existing = Cache::get("posts:override:$id");
        if (!$existing) {
            foreach ($base as $item) {
                if (is_array($item) && (int)($item['id'] ?? 0) === $id) {
                    $existing = $item;
                    break;
                }
            }
        }

        $created = false;
        if (!$existing) {
            $existing = ['id' => $id];
            $created  = true;
        }

        $newPost = array_merge($existing, $input);

        // Save override for 24 hours
        Cache::put("posts:override:$id", $newPost, now()->addDay());

        // Maintain ids index
        $ids = Cache::get(ApplyOverridesPipe::OVERRIDE_IDS_KEY, []);
        if (!is_array($ids)) {
            $ids = [];
        }
        if (!in_array($id, $ids, true)) {
            $ids[] = $id;
        }
        // Persist ids list (no TTL to keep index stable)
        Cache::forever(ApplyOverridesPipe::OVERRIDE_IDS_KEY, $ids);

        $payload['post']    = $newPost;
        $payload['created'] = $created;

        return $next($payload);
    }
}
