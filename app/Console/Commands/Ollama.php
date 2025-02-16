<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use App\Models\Embedding;
use App\Models\Messages;
use App\Models\User;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Text\PendingRequest;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;

use function Laravel\Prompts\textarea;

class Ollama extends Command
{
	use DrawsBoxes;
	use Colors;

	protected $description = 'Chat with Ollama';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:ollama';

	protected string $model = Models::Phi4->value;

	protected Collection $messages;

	public function __construct()
	{
		parent::__construct();
		$this->messages = new Collection();
	}

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		// $prism = Prism::text()
		// 	// ->withTools([new SearchTool()])
		// 	// ->withMaxSteps(5)
		// 	->withSystemPrompt("You are an expert doctor named Dr.AI, who can diagnose patients and prescribe medicine")
		// 	->withClientOptions(['timeout' => 120])
		// 	->using(Provider::Ollama, $this->model);

		try {

			// fetch embeddings from the database
			// $drug = Drug::query()->first();
			$prism = Prism::embeddings()->using(Provider::Ollama, Models::Nomic->value);
			$inputText = 'რა მივიღოთ ალერგის დროს';
			$embedding = $prism->fromInput($inputText)->generate();

			$inputVector = '[' . implode(',', last($embedding->embeddings)) . ']';
			// dd($inputVector);

			$nearest = Embedding::query()->whereRaw("embedding <=> '$inputVector' < 0.18")
				->limit(5)
				->pluck('source')
				->first()

			// $nearest = Embedding::query()
			// 	->orderByRaw("embedding <=> '$inputVector'")
			// 	->limit(5)
			// 	->pluck('source')
			// 	->first()
			;



			$out = Prism::text()->using(Provider::Ollama, Models::Phi4->value)
				->withPrompt("Question: $inputText\n Answer: $nearest")
				->generate();

			dd($out->text);
			// $drug = Drug::query()->pluck('all')->lazy()->each(function ($drug,$k) {
			//
			// 	$chunks = str($drug)
			// 		->explode(PHP_EOL)
			// 		->filter(fn($str) => !blank($str))
			// 		// ->implode(PHP_EOL);
			//
			// 		->reduce(function ($carry, $line) {
			// 			// Get the last chunk
			// 			$lastChunkIndex = count($carry) - 1;
			//
			// 			// If the last chunk + new line is within limit, append to it
			// 			if ($lastChunkIndex >= 0 && Str::length($carry[$lastChunkIndex] . ' ' . $line) <= 2024) {
			// 				$carry[$lastChunkIndex] .= ' ' . $line;
			// 			} else {
			// 				// Otherwise, start a new chunk
			// 				$carry[] = $line;
			// 			}
			//
			// 			return $carry;
			// 		}, []);
			//
			// 	// dd($chunks); // Array of text chunks, each <= 2048 characters
			// 	$prism = Prism::embeddings()->using(Provider::Ollama, Models::Nomic->value);
			// 	// dd($prism->fromInput($chunks)->generate());
			//
			// 	collect($chunks)->map(function ($chunk) use ($prism) {
			// 		return [
			// 			'source' => $chunk,
			// 			'embedding' => last($prism->fromInput($chunk)->generate()->embeddings)
			// 		];
			// 	})->each(function ($item) {
			// 		Embedding::query()->create([
			// 			'source' => $item['source'],
			// 			'embedding' => json_encode($item['embedding']),
			// 		]);
			// 	});
			//
			// 	$this->info('inserted:' . $k);
			// });


			dd('Done!');
		} catch (\Throwable $th) {
			//throw $th;
			dd($th->getMessage(), $th->getFile());
		}

		while (true) {
			$this->chat($prism);
		}
	}

	public function chat(PendingRequest $prism): void
	{
		$txt = textarea("Ask Ollama " . strtoupper($this->model), 'Type your question here', rows: 10);

		$this->previousMessages();
		$this->messages->push(new UserMessage($txt));

		$answer = $prism->withMessages($this->messages->toArray())->generate();
		$this->messages->merge($answer->responseMessages);

		$this->box('Response', wordwrap($answer->text), color: 'magenta');
	}


	/**
	 * @param User $user
	 * @return Collection<UserMessage[]>
	 */
	public function previousMessages(): Collection
	{
		$user = User::query()->where('email', 'ucha19871@gmail.com')->first();
		$this->messages->push(new UserMessage("Name: $user->name | Email: $user->email"));

		// TODO: pass messages to the chat history
		return Messages::query()->where('user_id', $user->id)
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
			});
	}

}
