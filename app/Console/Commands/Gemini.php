<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Tool;
use Prism\Prism\Prism;
use Prism\Relay\Facades\Relay;

class Gemini extends Command
{
	use DrawsBoxes;
	use Colors;

	protected $description = 'Chat with Gemini';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:gemini';

	protected string $model = Models::Gemini2_0->value;

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

				Tool::as('history')
					->for('say history for current city')
					->withStringParameter('query', 'Tell me a history about the city.')
					->using(fn (string $query): string => "Here's a little glimpse: {$query} has such a vibrant and interesting past!
					**Early Days:** Before Europeans arrived, the area was home to the Yelamu tribe. In 1776, Spanish settlers established a presence, founding the Presidio of San Francisco and Mission San Francisco de Asís (named for St. Francis of Assisi). Initially, the area was called Yerba Buena."),
			];



			$response = Prism::text()
				->using(Provider::Gemini, Models::Gemini2_0->value)
				->withProviderMeta(Provider::Gemini, ['searchGrounding' => true])
				->withMaxSteps(4)
				// ->usingTemperature(2)
				->withSystemPrompt("You are a sweet weather woman")
				->withPrompt('What\'s the weather in San Francisco today.')
				->asStream();

			// dd($response->text);

			// Process each chunk as it arrives
			foreach ($response as $chunk) {
				// Write each chunk directly to output without buffering
				$this->output->write($this->green($chunk->text));
			}

			$this->output->write("\n\n --DONE!-- \n");
		} catch (\Throwable $th) {
			//throw $th;
			$this->error($th->getMessage());
		}

	}
}
