<?php

namespace App\Http\Controllers\StatusCodes;

use Illuminate\Http\JsonResponse;

class Http_404 {
	/**
	 * Invoke the controller
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function __invoke(): JsonResponse
	{
		return response()->json([
			'message' => 'Not Found',
			'status' => 404
		], 404);
	}
}
