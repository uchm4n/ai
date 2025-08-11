<?php

namespace Modules\Posts\Pipes;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ValidatePostInputPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        $input    = Arr::wrap($payload['input'] ?? []);
        $isUpdate = isset($payload['id']);

        if ($isUpdate) {
            $rules = [
                'title'  => ['sometimes', 'string'],
                'body'   => ['sometimes', 'string'],
                'userId' => ['sometimes', 'integer', 'min:1'],
            ];
            $validated = Validator::make($input, $rules)->validate();
            if (empty($validated)) {
                // Require at least one field on update
                $validated = [];
                // Throw manual validation error
                Validator::make([], ['_' => 'required'], ['_required' => 'At least one field must be provided'])->validate();
            }
        } else {
            $rules = [
                'title'  => ['required', 'string'],
                'body'   => ['required', 'string'],
                'userId' => ['required', 'integer', 'min:1'],
            ];
            $validated = Validator::make($input, $rules)->validate();
        }

        $payload['input'] = [
            'title'  => array_key_exists('title', $validated) ? (string) $validated['title'] : null,
            'body'   => array_key_exists('body', $validated) ? (string) $validated['body'] : null,
            'userId' => array_key_exists('userId', $validated) ? (int) $validated['userId'] : null,
        ];

        // Remove nulls for partial updates
        $payload['input'] = array_filter($payload['input'], static fn ($v) => !is_null($v));

        return $next($payload);
    }
}
