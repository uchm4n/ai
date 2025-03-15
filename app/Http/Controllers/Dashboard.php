<?php

namespace App\Http\Controllers;

use App\Console\Tools\Models;
use App\Console\Tools\SearchTool;
use App\Models\Messages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Text\PendingRequest;
use Throwable;

class Dashboard extends Controller
{
	protected string $model = Models::Phi4->value;
	protected Builder|HasMany $messages;

	protected array $options = ["temperature" => 0.0, "seed" => 101, "top_p" => 1.0, "max_tokens" => 500];
	protected string $systemMessage = "You are an expert doctor named Dr.AI, Who can diagnose patients and prescribe medicine. 
										ALWAYS answer in MARKDOWN format and 
										ALWAYS provide a diagnosis or prescription.";
	private ?\Illuminate\Contracts\Auth\Authenticatable $user;
	private string $strResponse = '';

	public function __construct()
	{
		$this->user     = auth()->user();
		$this->messages = $this->user->messages();
	}

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

			$ai = Prism::text()
				// ->withTools([new SearchTool()])
				->withMaxSteps(5)
				->withSystemPrompt($this->systemMessage)
				->withMessages($request->get('promptInput'))
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
			[$messageId, $prompt] = cache()->remember('promptInput', 5, function () use ($request) {
				$request->validate(['promptInput' => 'required|string|min:5']);
				$prompt = str($request->get('promptInput'))->lower()->trim()->value();
				//save prompt as a message
				$messageId = $this->messages->updateOrCreate(['text' => $prompt], ['text' => $prompt])->getQueueableId(
				);
				return [$messageId, $prompt];
			});

			$response = Http::withOptions(['stream' => true])->post(config('prism.providers.ollama.url') . '/api/generate', [
				'system'  => $this->systemMessage,
				'prompt'  => $prompt,
				'model'   => $this->model,
				'options' => $this->options,
			]);

			return response()->stream(function () use ($messageId, $response) {
				$body = $response->getBody();

				while (!$body->eof()) {
					$chunk = $body->read(1024);
					if (!empty($chunk)) {
						$strResponse = str($chunk)->matchAll('/"response":\s*"([^"]*)"/')->implode('');

						// Proper SSE format with JSON data
						echo "data: " . json_encode(['msg' => $strResponse]) . PHP_EOL . PHP_EOL;
						$this->strResponse .= $strResponse;

						// Flush output buffer
						ob_flush();
						flush();
					}

					// Optional: Add slight delay if needed
					// usleep(1000);
				}

				//save response as a message, append to the previous messages
				$this->appendToMessageResponse($messageId, $this->strResponse);
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


	/**
	 * TODO: Waiting for the next release of Prism package
	 *      Specifically Prism::textSteam() feature
	 * @param string $prompt
	 * @return mixed
	 */
	private function previousMessagesWithPrompt(string $prompt)
	{
		$prompt = str($prompt)->lower()->trim()->value();
		$msg    = $this->messages
			->take(50)
			->get(['response', 'text'])
			->flatMap(function ($item) {
				return [
					[
						'role'    => 'user',
						'content' => str($item->text)->lower()->trim()->value(),
					],
					[
						'role'    => 'assistant',
						'content' => str($item->response)->lower()->trim()->value(),
					],
				];
			})->merge([
				[
					'role'    => 'user',
					'content' => $prompt,
				],
			]);

		//save prompt as a message
		defer(fn() => $this->messages->updateOrCreate(['text' => $prompt], ['text' => $prompt]));

		return $msg->toArray();
	}

	/**
	 * TODO: Waiting for the next release of Prism package
	 *       Specifically Prism::textSteam() feature
	 * @return PendingRequest
	 */
	private function prismFactory(): PendingRequest
	{
		return Prism::text()
			->withSystemPrompt($this->systemMessage)
			->withClientOptions(['timeout' => 120])
			->using(Provider::Ollama, $this->model);
	}

	/**
	 * @param int $messageId
	 * @param string $newResponse
	 * @return void
	 */
	private function appendToMessageResponse(int $messageId, string $newResponse): void
	{
		$message           = Messages::findOrFail($messageId);
		$message->response = $newResponse;
		$message->save();
	}
}
