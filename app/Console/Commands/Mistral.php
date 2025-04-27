<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use App\Console\Tools\SearchTool;
use Illuminate\Console\Command;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Tool;
use Prism\Prism\Prism;
use Prism\Prism\Schema\ArraySchema;
use Prism\Prism\Schema\BooleanSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\ValueObjects\Messages\Support\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class Mistral extends Command
{
	use DrawsBoxes;
	use Colors;
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:mistral';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'use Mistral ai model to answer questions';

	/**
	 * Execute
	 */
	public function handle()
	{
		try {

			$tools = [
				Tool::as('weather')
					->for('Get current weather conditions')
					->withStringParameter('city', 'The city to get weather for')
					->using(function (string $city): string {
						// Your weather API logic here
						return "The weather in {$city} is sunny and 25° (celsius).";
					})
			];
			// {"name": "Coq au Vin", "ingredients": ["chicken", "red wine", "bacon", "mushrooms", "onions", "garlic", "chicken broth", "thyme", "bay leaf", "flour", "butter", "olive oil", "salt", "pepper"]}

			$recipeSchema = new ObjectSchema(
				'recipe',
				'A recipe object',
				[
					new StringSchema('name', 'The name of the recipe'),
					new ArraySchema(
						'ingredients',
						'List of ingredients',
						new StringSchema('ingredient', 'An ingredient'),
					),
				],
				['name', 'ingredients'],
			);

			$response = Prism::text()
				->using(Provider::Mistral, Models::Mistral_Small->value)
				->withClientOptions(['timeout' => 60])
				->withMaxTokens(500)
				// ->withSchema($recipeSchema)
				// ->withMaxSteps(3)
				// ->withTools($tools)
				// ->withSystemPrompt('ALWAYS answer in MARKDOWN format')
				// ->withPrompt('What is the capital of Georgia (Country)? and What is the weather in this city?')
				->withMessages([
					// new UserMessage('What is the best French cheese? Return the product and produce location in JSON format!'),
					// new UserMessage('What is the best French meal? Return the name and the ingredients in short JSON object'),
					new UserMessage('transcribe this receipt',[
						Image::fromUrl('https://www.boredpanda.com/blog/wp-content/uploads/2022/11/interesting-receipts-102-6364c8d181c6a__700.jpg')
					])
				])
				// ->asText()
				->asStream()
				// ->asStructured()
			;

			// dd($response->structured);

			// Process each chunk as it arrives
			foreach ($response as $chunk) {
				// Write each chunk directly to output without buffering
				$this->output->write($this->green($chunk->text));
			}

			$this->output->write("\n\n --DONE!-- \n");
		} catch (\Throwable $th) {
			$this->error($th->getMessage());
		}
	}
}
