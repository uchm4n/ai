<?php

namespace App\Http\Controllers;

use App\Console\Tools\Models;
use App\Models\Messages;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Prism\Prism\ValueObjects\ProviderTool;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class Dashboard extends Controller
{
    protected string $model = Models::Phi4->value;

    protected Builder|HasMany|null $messages;

    protected array $options = ['temperature' => 0.0, 'seed' => 101, 'top_p' => 1.0, 'max_tokens' => 500];

    protected string $systemMessage = 'You are an expert doctor named Dr.AI, Who can diagnose patients and prescribe medicine. 
										ALWAYS answer in MARKDOWN format and 
										ALWAYS provide a diagnosis or prescription.';

    private readonly ?Authenticatable $user;

    private string $strResponse = '';

    public function __construct()
    {
        $this->user = auth()->user();
        $this->messages = $this->user?->messages();
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
                ->asText();

            return Inertia::render('Dashboard', ['msg' => trim($ai->text), 'status' => Response::HTTP_OK]);
        } catch (Throwable) {
            return Inertia::render('Dashboard', ['msg' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function stream(Request $request)
    {
        try {
            // prompt caching for 5 seconds
            [$messageId, $prompt] = cache()->remember('promptInput', 5, function () use ($request): array {
                $request->validate(['promptInput' => 'required|string|min:5']);
                $prompt = str($request->get('promptInput'))->lower()->trim()->value();
                // save prompt as a message
                $messageId = $this->messages->updateOrCreate(['text' => $prompt], ['text' => $prompt])->getQueueableId(
                );

                return [$messageId, $prompt];
            });

            $response = Http::withOptions(['stream' => true])->post(
                config('prism.providers.ollama.url').'/api/generate',
                [
                    'system'  => $this->systemMessage,
                    'prompt'  => $prompt,
                    'model'   => $this->model,
                    'options' => $this->options,
                ],
            );

            return response()->stream(function () use ($messageId, $response): void {
                $body = $response->getBody();

                while (! $body->eof()) {
                    $chunk = $body->read(1024);
                    if (! empty($chunk)) {
                        $strResponse = str($chunk)->matchAll('/"response":\s*"([^"]*)"/')->implode('');

                        // Proper SSE format with JSON data
                        echo 'data: '.json_encode(['msg' => $strResponse]).PHP_EOL.PHP_EOL;
                        $this->strResponse .= $strResponse;

                        // Flush output buffer
                        ob_flush();
                        flush();
                    }

                    // Optional: Add slight delay if needed
                    // usleep(1000);
                }

                // save response as a message, append to the previous messages
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

    public function streamG(Request $request)
    {
        try {
            // prompt caching for 3 seconds
            [$messageId, $prompt, $assistant] = cache()->remember('promptInput', 3, function () use ($request): array {
                $request->validate(['promptInput' => 'required|string|min:5']);
                $prompt = str($request->get('promptInput'))->lower()->trim()->value();
                // save prompt as a message
                $messageId = $this->messages->updateOrCreate(['text' => $prompt], ['text' => $prompt]);

                return [$messageId->getQueueableId(), $prompt, data_get($messageId->toArray(), 'response', '')];
            });

            // dd($messageId, $prompt, $assistant);
            return response()->stream(function () use ($messageId, $prompt): void {
                $stream = Prism::text()
                    ->using(Provider::Gemini, Models::Gemini2_5->value)
                    ->withProviderTools([
                        new ProviderTool('google_search'),
                    ])
                    ->withSystemPrompt($this->systemMessage)
                    ->withMessages([
                        new UserMessage($prompt),
                        // new AssistantMessage($assistant ?? '') // TODO: fix this
                    ])
                    ->withMaxTokens(8000)
                    ->asStream();

                foreach ($stream as $chunk) {
                    echo 'data: '.json_encode(['msg' => $chunk->text]).PHP_EOL.PHP_EOL;
                    $this->strResponse .= $chunk->text;
                    ob_flush();
                    flush();
                }

                // save response as a message, append to the previous messages
                $this->appendToMessageResponse($messageId, $this->strResponse);
            }, 200, [
                'Cache-Control'     => 'no-cache',
                'Content-Type'      => 'text/event-stream',
                'X-Accel-Buffering' => 'no', // Prevents Nginx from buffering
            ]);
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function appendToMessageResponse(int $messageId, string $newResponse): void
    {
        $message = Messages::findOrFail($messageId);
        $message->response = $newResponse;
        $message->save();
    }

    /**
     * Convert text to speech using Gemini 2.5
     *
     * @return JsonResponse
     */
    public function textToSpeech(Request $request): StreamedResponse|Response|JsonResponse
    {
        try {
            return response()->streamDownload(function (): void {
                echo file_get_contents(resource_path('audio/welcome.mp3'));
            }, 'speech.mp3', [
                'Content-Type' => 'audio/mpeg',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
