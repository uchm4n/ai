<?php

namespace App\Modules\User\Actions;

use App\Actions\BaseAction;
use App\Modules\User\Pipes\CheckUserExistsPipe;
use App\Modules\User\Pipes\DeleteUserPipe;

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
