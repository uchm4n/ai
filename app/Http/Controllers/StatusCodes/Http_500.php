<?php

namespace App\Http\Controllers\StatusCodes;

use Illuminate\Http\JsonResponse;

class Http_500 {
	/**
	 * Invoke the controller
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function __invoke(): JsonResponse
	{
		return response()->json([
			'message' => 'Internal Server Error',
			'status' => 500
		], 500);
	}
}
