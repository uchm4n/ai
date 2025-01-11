<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
	/**
	 * The root template that is loaded on the first page visit.
	 *
	 * @var string
	 */
	protected $rootView = 'app';

	/**
	 * Determine the current asset version.
	 */
	public function version(Request $request): ?string
	{
		return parent::version($request);
	}

	/**
	 * Define the props that are shared by default.
	 *
	 * @return array<string, mixed>
	 */
	public function share(Request $request): array
	{
		return [
			...parent::share($request),
			'auth'        => [
				'user' => $request->user(),
			],
			'breadcrumbs' => function () use ($request) {
				return str($request->getRequestUri())->explode('/')->map(function ($item) use ($request) {
					if (empty($item)) {
						return [
							'title' => 'Home',
							'link' => '/',
						];
					}

					return [
						'title' => ucfirst($item),
						'link' => $request->getRequestUri()
					];
				});
			},
		];
	}
}
