<?php

namespace App\Modules\User\Pipes;

use App\Modules\User\Models\User as UserModel;
use Closure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUserDatabasePipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        if (! is_array($payload)) {
            return $next($payload);
        }

        if (Schema::hasTable('users')) {
            $model = $payload['_model'] ?? null;

            $attributes = [
                'name'  => $payload['name'] ?? ($model?->name),
                'email' => $payload['email'] ?? ($model?->email),
            ];

            if (! empty($payload['password'])) {
                $attributes['password'] = Hash::make($payload['password']);
            }

            if ($model) {
                $model->fill($attributes);
                $model->save();
            } else {
                $model = new UserModel($attributes);
                $model->save();
            }

            $payload['_model'] = $model;
            $payload['id'] = $model->getKey();
        } else {
            // Simulate persistence if table doesn't exist
            $payload['id'] ??= uniqid('user_', true);
        }

        return $next($payload);
    }
}
