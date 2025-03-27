<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use App\Models\Embedding;
use App\Models\Messages;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use Prism\Prism\Facades\Tool;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\Text\PendingRequest;
use Prism\Prism\ValueObjects\Messages\UserMessage;

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
		try {
			$tools = [
				Tool::as('weather')
					->for('useful when you need to search for current weather conditions')
					->withStringParameter('city', 'The city that you want the weather for')
					->using(fn (string $city): string => "The weather will be 75° and sunny in {$city}"),

				Tool::as('search')
					->for('useful for searching current events or data')
					->withStringParameter('query', 'The detailed search query')
					->using(fn (string $query): string => "Search results for: {$query}"),
			];

			$response = Prism::text()
				->using(Provider::Gemini, Models::Mistral->value)
				// ->withTools([$weatherTool])
				// ->withTools($tools)
				->withMaxSteps(4)
				->withPrompt('What\'s the weather like in San Francisco today? Should i where the jacket?')
				// ->withOptions([ // TODO: Planed to add
				// 	'temperature' => 0.9,
				// 	'num_predict' => 1000,
				// 	'top_p' => 0.9,
				// ])
				->asStream();

			// Process each chunk as it arrives
			foreach ($response as $chunk) {
				// Write each chunk directly to output without buffering
				$this->output->write($this->green($chunk->text));
			}


			// $schema = new ObjectSchema(
			// 	name: 'movie_review',
			// 	description: 'A structured movie review',
			// 	properties: [
			// 		new StringSchema('title', 'The movie title'),
			// 		new StringSchema('rating', 'Rating out of 5 stars'),
			// 		new StringSchema('summary', 'Brief review summary')
			// 	],
			// 	requiredFields: ['title', 'rating', 'summary']
			// );
			//
			// $response = Prism::structured()
			// 	->using(Provider::Ollama, Models::Mistral->value)
			// 	->withSchema($schema)
			// 	->withPrompt('Review the movie Inception')
			// 	// ->withOptions([
			// 	// 	'temperature' => 1.9,
			// 	// 	'num_predict' => 1000,
			// 	// 	'top_p' => 0.9,
			// 	// ])
			// 	->asStructured();
			// Process each chunk as it arrives
			// foreach ($response as $chunk) {
			// 	// Write each chunk directly to output without buffering
			// 	$this->output->write($this->green($chunk->text));
			// }
			//
			// // Access your structured data
			// $review = $response->structured;
			// // dd($review);
			// $this->output->text($this->green($review['title']) . PHP_EOL);
			// $this->output->text($this->dim($review['rating']) . PHP_EOL);
			// $this->output->text($this->yellow($review['summary']) . PHP_EOL);

			$this->output->write("\n\n");
		} catch (\Throwable $th) {
			//throw $th;
			$this->error($th->getMessage());
		}

	}

	// public function chat(PendingRequest $prism): void
	// {
	// 	$txt = textarea("Ask Ollama " . strtoupper($this->model), 'Type your question here', rows: 10);
	//
	// 	$this->previousMessages();
	// 	$this->messages->push(new UserMessage($txt));
	//
	// 	$answer = $prism->withMessages($this->messages->toArray())->generate();
	// 	$this->messages->merge($answer->responseMessages);
	//
	// 	$this->box('Response', wordwrap($answer->text), color: 'magenta');
	// }
	//
	//
	// /**
	//  * @param User $user
	//  * @return Collection<UserMessage[]>
	//  */
	// public function previousMessages(): Collection
	// {
	// 	$user = User::query()->where('email', 'ucha19871@gmail.com')->first();
	// 	$this->messages->push(new UserMessage("Name: $user->name | Email: $user->email"));
	//
	// 	// TODO: pass messages to the chat history
	// 	return Messages::query()->where('user_id', $user->id)
	// 		->take(50)
	// 		->get(['response', 'text'])
	// 		->flatMap(function ($item) {
	// 			return [
	// 				[
	// 					'role'    => 'user',
	// 					'content' => str($item->text)->lower()->trim()->value(),
	// 				],
	// 				[
	// 					'role'    => 'assistant',
	// 					'content' => str($item->response)->lower()->trim()->value(),
	// 				],
	// 			];
	// 		});
	// }

}
