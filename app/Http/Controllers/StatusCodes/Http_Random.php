<?php

namespace App\Http\Controllers\StatusCodes;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Http_Random {
	/**
	 * Invoke the controller
	 *
	 * @param Request $request
	 * @param string $codes Comma-separated list of HTTP status codes
	 * @return JsonResponse
	 */
	public function __invoke(Request $request, string $codes): JsonResponse
	{
		$code = str($codes)
			->explode(',')
			->random();

		return response()->json([
			'message' => Response::$statusTexts[$code],
			'status' => (int)$code
		], $code);
	}
}
