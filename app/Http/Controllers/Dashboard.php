<?php

namespace App\Http\Controllers;

use App\Console\Tools\Models;
use App\Console\Tools\SearchTool;
use App\Models\Messages;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Throwable;

class Dashboard extends Controller
{
	protected string $model = Models::Phi4->value;

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

			$ai =  Prism::text()
				->withTools([new SearchTool()])
				->withMaxSteps(5)
				->withSystemPrompt("You are an expert doctor named Dr.AI, who can diagnose patients and prescribe medicine")
				->withPrompt($request->get('promptInput'))
				->withClientOptions(['timeout' => 120])
				->using(Provider::Ollama, $this->model)
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

			$response = Http::withOptions(['stream' => true])->post(env('OLLAMA_URL'). '/api/generate', [
				'system' => "You are an expert doctor named Dr.AI, who can diagnose patients and prescribe medicine. 
							 ALWAYS answer in MARKDOWN format and ALWAYS provide a diagnosis or prescription.",
				'prompt' => $prompt,
				'model'  => $this->model,
				"parameters"=> ["temperature"=> 3.0, "top_p"=> 1.0, "max_tokens"=> 500]
			]);

			// Save prompt as a message
			defer(function () use ($prompt) {
				auth()->user()->messages()->updateOrCreate(['text' => $prompt], ['text' => $prompt]);
			});



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
