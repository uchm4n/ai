<?php

namespace App\Http\Controllers;

use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Exceptions\PrismException;
use EchoLabs\Prism\Prism;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Throwable;

class Dashboard extends Controller
{
    public function index()
	{


		return Inertia::render('Dashboard');
	}

	public function send(Request $request)
	{
		try {
			$ai = Prism::text()
				->using(Provider::Gemini, 'gemini-1.5-flash')
				->withPrompt($request->get('promptInput'))
				->withClientOptions(['timeout' => 60])

				->generate();

			return $ai->text;

		} catch (PrismException $e) {
			dd('Text generation failed:', ['error' => $e->getMessage()]);
		} catch (Throwable $e) {
			dd('Generic error:', ['error' => $e->getMessage()]);
		}

	}
}
