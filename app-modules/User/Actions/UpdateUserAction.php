<?php

namespace Modules\User\Actions;

use Modules\BaseAction;
use Modules\User\Pipes\CheckUserExistsPipe;
use Modules\User\Pipes\CreateUserDatabasePipe;
use Modules\User\Pipes\ValidateUserPipe;

class UpdateUserAction extends BaseAction
{
    public function pipes(): array
    {
        return [
            ValidateUserPipe::class,
            CheckUserExistsPipe::class,
            CreateUserDatabasePipe::class,
        ];
    }
}
