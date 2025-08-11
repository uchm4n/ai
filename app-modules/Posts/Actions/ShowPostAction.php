<?php

namespace Modules\Posts\Actions;

use Modules\BaseAction;
use Modules\Posts\Pipes\EnsureBasePostsPipe;
use Modules\Posts\Pipes\FetchPostPipe;
use Modules\Posts\Pipes\ReturnPostPipe;

class ShowPostAction extends BaseAction
{
    public function pipes(): array
    {
        return [
            EnsureBasePostsPipe::class,
            FetchPostPipe::class,
            ReturnPostPipe::class,
        ];
    }
}
