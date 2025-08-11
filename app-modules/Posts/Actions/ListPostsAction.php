<?php

namespace Modules\Posts\Actions;

use Modules\BaseAction;
use Modules\Posts\Pipes\ApplyOverridesPipe;
use Modules\Posts\Pipes\EnsureBasePostsPipe;
use Modules\Posts\Pipes\ReturnListPipe;

class ListPostsAction extends BaseAction
{
    public function pipes(): array
    {
        return [
            EnsureBasePostsPipe::class,
            ApplyOverridesPipe::class,
            ReturnListPipe::class,
        ];
    }
}
