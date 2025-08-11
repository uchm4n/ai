<?php

namespace Modules\Posts\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Posts\Actions\ListPostsAction;
use Modules\Posts\Actions\ShowPostAction;
use Modules\Posts\Actions\UpsertPostAction;

class PostsController
{
    public function index(ListPostsAction $action): JsonResponse
    {
        $result = $action(null);

        return response()->json(['data' => $result]);
    }

    public function show(int $id, ShowPostAction $action): JsonResponse
    {
        $result = $action(['id' => $id]);
        if (!($result['found'] ?? false)) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json(['data' => $result['post']]);
    }

    public function store(Request $request, UpsertPostAction $action): JsonResponse
    {
        $result = $action(['input' => $request->all()]);

        return response()->json(['data' => $result['post'], 'created' => $result['created']], $result['created'] ? 201 : 200);
    }

    public function update(int $id, Request $request, UpsertPostAction $action): JsonResponse
    {
        $result = $action(['id' => $id, 'input' => $request->all()]);

        return response()->json(['data' => $result['post'], 'created' => $result['created']], $result['created'] ? 201 : 200);
    }
}
