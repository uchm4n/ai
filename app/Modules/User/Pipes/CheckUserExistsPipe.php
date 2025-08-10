<?php

namespace App\Modules\User\Pipes;

use App\Modules\User\Models\User as UserModel;
use Closure;
use Illuminate\Support\Facades\Schema;

class CheckUserExistsPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        if (is_array($payload) && Schema::hasTable('users')) {
            $query = UserModel::query();
            if (! empty($payload['id'])) {
                $query->whereKey($payload['id']);
            } elseif (! empty($payload['email'])) {
                $query->where('email', $payload['email']);
            }

            $existing = $query->first();
            $payload['_exists'] = (bool) $existing;
            $payload['_model'] = $existing;
        } else {
            $payload['_exists'] = false;
        }

        return $next($payload);
    }
}
