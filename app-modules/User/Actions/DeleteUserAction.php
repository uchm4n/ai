<?php

namespace Modules\User\Actions;

use Modules\BaseAction;
use Modules\User\Pipes\CheckUserExistsPipe;
use Modules\User\Pipes\DeleteUserPipe;

class DeleteUserAction extends BaseAction
{
    public function pipes(): array
    {
        return [
            CheckUserExistsPipe::class,
            DeleteUserPipe::class,
        ];
    }
}
