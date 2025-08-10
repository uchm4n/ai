<?php

namespace App\Modules\User\Http\Controllers;

use App\Modules\User\Actions\CreateUserAction;
use App\Modules\User\Actions\DeleteUserAction;
use App\Modules\User\Actions\UpdateUserAction;
use App\Modules\User\Http\Requests\UserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    public function store(UserRequest $request, CreateUserAction $create): JsonResponse
    {
        $payload = $request->validated();
        $result = $create($payload);

        return response()->json(['data' => $result]);
    }

    public function update(string $id, UserRequest $request, UpdateUserAction $update): JsonResponse
    {
        $payload = array_merge(['id' => $id], $request->validated());
        $result = $update($payload);

        return response()->json(['data' => $result]);
    }

    public function destroy(string $id, Request $request, DeleteUserAction $delete): JsonResponse
    {
        $payload = ['id' => $id];
        $result = $delete($payload);

        return response()->json(['data' => $result]);
    }
}
