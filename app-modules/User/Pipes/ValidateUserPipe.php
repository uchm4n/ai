<?php

namespace Modules\User\Pipes;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ValidateUserPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        // If payload already marked as validated, skip
        if (is_array($payload) && Arr::get($payload, '_validated', false)) {
            return $next($payload);
        }

        if (is_array($payload)) {
            $validator = Validator::make($payload, [
                'name'  => ['required', 'string', 'max:255'],
                'email' => ['required', 'email'],
                // password optional for demo
            ]);

            $validator->validate();

            $payload['_validated'] = true;

            return $next($payload);
        }

        // If payload is not array, just pass through
        return $next($payload);
    }
}
