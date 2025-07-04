<?php

namespace App\Http\Controllers\StatusCodes;

use Illuminate\Http\JsonResponse;

class Http_501 {
	/**
	 * Invoke the controller
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function __invoke(): JsonResponse
	{
		return response()->json([
			'message' => 'Not Implemented',
			'status' => 501
		], 501);
	}
}
