<?php

namespace App\Http\Controllers\StatusCodes;

use Illuminate\Http\JsonResponse;

class Http_301 {
	/**
	 * Invoke the controller
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function __invoke(): JsonResponse
	{
		return response()->json([
			'message' => 'Moved Permanently',
			'status' => 301
		], 301);
	}
}
