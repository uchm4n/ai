<?php

namespace App\Modules\User\Actions;

use App\Actions\BaseAction;
use App\Modules\User\Pipes\CheckUserExistsPipe;
use App\Modules\User\Pipes\CreateUserDatabasePipe;
use App\Modules\User\Pipes\ValidateUserPipe;

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
