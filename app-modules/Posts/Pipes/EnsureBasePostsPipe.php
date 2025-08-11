<?php

namespace Modules\Posts\Pipes;

use Closure;
use Faker\Factory;
use Illuminate\Support\Facades\Cache;

class EnsureBasePostsPipe
{
    public const BASE_CACHE_KEY = 'posts:base';

    public function handle(mixed $payload, Closure $next): mixed
    {
        $base = Cache::get(self::BASE_CACHE_KEY);

        if (!$base) {
            try {
                $faker = Factory::create();
                $base  = collect(range(1, 100))->map(function () use ($faker) {
                    return [
                        'userId' => $faker->numberBetween(1, 10),
                        'id'     => $faker->unique()->numberBetween(1, 100),
                        'title'  => $faker->sentence,
                        'body'   => $faker->paragraphs(3, true),
                    ];
                })->toArray();
            } catch (\Throwable $e) {
                // Fallback to empty array if remote is not reachable
                $base = [];
            }

            // Cache base posts for 1 hour to avoid frequent external calls
            Cache::put(self::BASE_CACHE_KEY, $base, now()->addHour());
        }

        $payload         = is_array($payload) ? $payload : [];
        $payload['base'] = is_array($base) ? $base : [];

        return $next($payload);
    }
}
