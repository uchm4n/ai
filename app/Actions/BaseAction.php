<?php

namespace App\Actions;

use Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;
use Illuminate\Pipeline\Pipeline;

abstract class BaseAction implements ActionInterface
{
    /**
     * Invokes the action with the given payload.
     */
    public function __invoke(mixed $payload): mixed
    {
        return $this->run($payload);
    }

    /**
     * Run the pipeline for this action.
     */
    public function run(mixed $payload): mixed
    {
        /** @var Pipeline $pipeline */
        $pipeline = app(PipelineContract::class);

        return $pipeline
            ->send($payload)
            ->through($this->pipes())
            ->thenReturn();
    }

    /**
     * Define the list of pipes that the action should pass through.
     *
     * @return array<int, class-string|object>
     */
    abstract public function pipes(): array;
}
