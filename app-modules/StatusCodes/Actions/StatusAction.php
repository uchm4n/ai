<?php

namespace Modules\StatusCodes\Actions;

use Modules\BaseAction;
use Modules\StatusCodes\Pipes\StatusPipe;

class StatusAction extends BaseAction
{
    public function pipes(): array
    {
        return [
            StatusPipe::class,
        ];
    }
}
