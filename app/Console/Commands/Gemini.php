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
			// $tools = [
			// 	Tool::as('weather')
			// 		->for('useful when you need to search for current weather conditions')
			// 		->withStringParameter('city', 'The city that you want the weather for')
			// 		->using(fn (string $city): string => "The weather will be 75° and sunny in {$city}"),
			//
			// 	Tool::as('search')
			// 		->for('useful for searching current events or data')
			// 		->withStringParameter('query', 'The detailed search query')
			// 		->using(fn (string $query): string => "Search results for: {$query}"),
			// ];



			$response = Prism::text()
				->using(Provider::Gemini, Models::Gemini2_0->value)
				// ->withTools([$weatherTool])
				// ->withTools($tools)
				->withProviderMeta(Provider::Gemini, ['searchGrounding' => true])
				->withMaxSteps(4)
				->withPrompt('What\'s the weather like in San Francisco today? And tel me a short story about the weather')
				->usingTemperature(2)
				// ->withOptions([
				// 	'temperature' => 0.9,
				// 	'num_predict' => 1000,
				// 	'top_p' => 0.9,
				// ])
				->asStream();
				// ->asText();

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
