<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use App\Console\Tools\SearchTool;
use App\Models\Drug;
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
				Tool::as('drugs')
					->for('useful when you need to search for drugs and health related information')
					->withStringParameter('drug', 'The drug that you want the information for')
					->using(function (string $drug): string {
						return new SearchTool()->search($drug);
					}),
			];

			$response = Prism::text()
				->using(Provider::Gemini, Models::Gemini2_0->value)
				// ->withProviderMeta(Provider::Gemini, ['searchGrounding' => true])
				->withTools($tools)
				->withMaxSteps(50)
				// ->usingTemperature(2)
				->withSystemPrompt("You are a sweet pharmaceutical shop woman. And ALWAYS Answer in Georgian language.")
				// ->withPrompt('what is the drug called: acc, and how can i take it?')
				->withPrompt('რა არის წამალი: ვაცენაკი და როგორ მივიღოთ ის?')
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
