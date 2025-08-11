<?php

namespace Modules\Posts\Actions;

use Modules\BaseAction;
use Modules\Posts\Pipes\AssignPostIdPipe;
use Modules\Posts\Pipes\EnsureBasePostsPipe;
use Modules\Posts\Pipes\ReturnUpsertPipe;
use Modules\Posts\Pipes\SaveOverridePipe;
use Modules\Posts\Pipes\ValidatePostInputPipe;

class UpsertPostAction extends BaseAction
{
    public function pipes(): array
    {
        return [
            EnsureBasePostsPipe::class,
            ValidatePostInputPipe::class,
            AssignPostIdPipe::class,
            SaveOverridePipe::class,
            ReturnUpsertPipe::class,
        ];
    }
}
