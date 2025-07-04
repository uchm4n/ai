<?php

namespace App\Http\Controllers\StatusCodes;

use Illuminate\Http\JsonResponse;

class Http_401 {
	/**
	 * Invoke the controller
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function __invoke(): JsonResponse
	{
		return response()->json([
			'message' => 'Unauthorized',
			'status' => 401
		], 401);
	}
}
