<?php

namespace Modules;

interface ActionInterface
{
    /**
     * Execute the action as an invokable class.
     */
    public function __invoke(mixed $payload): mixed;

    /**
     * Define the list of pipes (middlewares) that the action should pass through.
     * Each pipe must have a handle($payload, \Closure $next): mixed method.
     *
     * @return array<int, class-string|object>
     */
    public function pipes(): array;
}
