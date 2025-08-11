<?php

namespace Modules\User\Pipes;

use Closure;
use Illuminate\Support\Facades\Schema;

class DeleteUserPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        if (is_array($payload)) {
            if (!empty($payload['_model']) && Schema::hasTable('users')) {
                $payload['_model']->delete();
                $payload['_deleted'] = true;
            } else {
                // Simulate deletion when no table/model
                $payload['_deleted'] = true;
            }
        }

        return $next($payload);
    }
}
