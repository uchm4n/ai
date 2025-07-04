<?php

namespace App\Http\Controllers\StatusCodes;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Http_Random {
	/**
	 * Invoke the controller
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function __invoke(Request $request): JsonResponse
	{
		$code = str($request->segment(1))
			->explode(',')
			->random();

		return response()->json([
			'message' => Response::$statusTexts[$code],
			'status' => $code
		], $code);
	}
}
