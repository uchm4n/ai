<?php

namespace App\Http\Controllers;

use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
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
			if ($request->has('switch') && $request->get('switch')) {
				abort(404);
			}
			$request->validate(['promptInput' => 'required|string|min:5',]);

			// 2. Redis Query equivalent array in php
			// 3. Laravel Query equivalent in php
			$ai = Prism::text()
				->using(Provider::Ollama, 'phi4')
				->withSystemPrompt(
					"
					Answer shot and concise.
					Respond every request with a raw markdown text.
					You are an expert in SQL, Elasticsearch, Redis, and Laravel.
					[if] string does not REPRESENT SQL QUERY or string is empty then:
						return: No Data
					[else if] string REPRESENTS SQL query then:
						[if] string query syntax is correct and be very strict then:
							return: 1. Elasticsearch Query equivalent array in php
						[else if] string is NOT have correct sq query syntax then:
							return: Check your SQL query syntax!
						[/if]
					[/if]
					",
				)
				->withPrompt('Please convert a SQL query to Elasticsearch: ' . $request->get('promptInput'))
				->withClientOptions(['timeout' => 120])
				->generate();

			return Inertia::render('Dashboard', ['msg' => trim($ai->text), 'status' => Response::HTTP_OK]);
		} catch (Throwable $e) {
			return Inertia::render('Dashboard', ['msg' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR]);
		}
	}

	public function stream(Request $request)
	{
		try {
			// prompt caching for 5 seconds
			$prompt = cache()->remember('promptInput', 5, function () use ($request) {
				return $request->get('promptInput');
			});

			$response = Http::withOptions(['stream' => true])->post('http://localhost:11434/api/generate', [
				'system' => 'You are a GIRL and your name is Alice, age 40, blonde.Expert on everything. 
				You are especially good at SQL, Elasticsearch, Redis, and PHP/Laravel.
				[if] you have any code request then:
					respond only in markdown format.
				[else] 
					respond with a regular text.
				[/if]',
				'prompt' => $prompt,
				// 'model'  => 'deepseek-r1:32b',
				// "parameters"=> ["temperature"=> 0.0, "top_p"=> 1.0, "max_tokens"=> 100, "stop"=> ["<think></think>"]]
				'model'  => 'phi4',
				"parameters"=> ["temperature"=> 0.0, "top_p"=> 1.0, "max_tokens"=> 100]
			]);

			return response()->stream(function () use ($response) {
				$body = $response->getBody();

				while (!$body->eof()) {
					$chunk = $body->read(1024);
					if (!empty($chunk)) {
						$strResponse = str($chunk)->matchAll('/"response":\s*"([^"]*)"/')->implode('');

						// Proper SSE format with JSON data
						echo "data: " . json_encode(['msg' => $strResponse]) . PHP_EOL . PHP_EOL;
						logger([$strResponse]);
						// Flush output buffer
						ob_flush();
						flush();
					}

					// Optional: Add slight delay if needed
					// usleep(1000);
				}
			},
				200,
				[
					'Content-Type'      => 'text/event-stream',
					'Cache-Control'     => 'no-cache',
					'Connection'        => 'keep-alive',
					'X-Accel-Buffering' => 'no',
				],
			);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}
}
