<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use Prism\Prism\Enums\ChunkType;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\ValueObjects\Media\Video;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class XAI extends Command
{
	use DrawsBoxes;
	use Colors;

	protected $description = 'Chat with Gemini';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:xai';

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
			// $response = Prism::text()
			// 	// ->using(Provider::XAI, Models::XAI_4->value)
			// 	->using(Provider::Gemini, Models::Gemini2_5->value)
			// 	// ->usingTemperature(2)
			// 	->withSystemPrompt("Keep output short and concise, without fluff.")
			// 	// ->withPrompt('What is going on and Who are you?')
			// 	// ->withPrompt('Generate bounding boxes for each of the objects in this image in [y_min, x_min, y_max, x_max] format.')
			// 	->withPrompt('Who is the #1 arm wrestler in the world?')
			// 	// ->withMessages([
			// 	// 	new UserMessage(
			// 	// 		// 'what is answer in this picture?',
			// 	// 		// 'what is this video about?',
			// 	// 		'what is this YouTube video about?',
			// 	// 		// 'What is going on and Who are you?',
			// 	// 		additionalContent: [
			// 	// 			// Image::fromLocalPath('/Users/u/Desktop/exam.png'),
			// 	// 			// Media::fromLocalPath('/Users/u/Desktop/exam.png'),
			// 	// 			// Video::fromUrl('https://www.youtube.com/watch?v=4rwmCRhBTcI'),
			// 	// 			// Image::fromUrl('https://live.staticflickr.com/1893/44566807012_d52477be10_b.jpg'),
			// 	// 			// Audio::fromUrl('https://d38nvwmjovqyq6.cloudfront.net/va90web25003/companions/ws_smith/1%20Comparison%20Of%20Vernacular%20And%20Refined%20Speech.mp3'),
			// 	// 			// Media::fromLocalPath('/Users/u/www/projects/prism/tests/Fixtures/sample-video.mp4'),
			// 	// 			// Audio::fromLocalPath('/Users/u/www/projects/prism/tests/Fixtures/sample-audio.wav'),
			// 	// 			// Video::fromUrl('https://www.youtube.com/watch?v=5c5U1ADlU2g'),
			// 	// 		],
			// 	// 	),
			// 	// ])
			// 	// ->asText();
			// 	->asStream();
			// 	// ->asText();
			//
			// // dd($response->text);
			//
			// // Process each chunk as it arrives
			// foreach ($response as $chunk) {
			// 	// Write each chunk directly to output without buffering
			// 	$this->output->write($this->green($chunk->text));
			// }


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
			// 	->using(Provider::XAI, Models::XAI_4->value)
			// 	->withSchema($schema)
			// 	->withPrompt('Review the movie Inception')
			// 	->asStructured();
			//
			// dd($response->structured);

			// $weatherTool = Tool::as('weather')
			// 	->for('Get current weather conditions')
			// 	->withStringParameter('city', 'The city to get weather for')
			// 	->using(function (string $city): string {
			// 		// Your weather API logic here
			// 		return "The weather in {$city} is sunny and 72°F.";
			// 	});
			//
			// $response = Prism::text()
			// 	->using(Provider::XAI, Models::XAI_4->value)
			// 	->withMaxSteps(2)
			// 	->withSystemPrompt("Always answer me with a SHORT responses, as much as possible!")
			// 	// ->withSystemPrompt("Always answer me with a SHORT responses! You are Grok, a chatbot inspired by the Hitchhikers Guide to the Galaxy")
			// 	// ->withPrompt('What is the meaning of life, the universe, and everything?')
			// 	->withProviderOptions(['thinking' => ['enabled' => false]])
			// 	->withPrompt('What is the weather like in Paris?')
			// 	// ->withPrompt('can you explain me a quantum physics? ')
			// 	->withTools([$weatherTool])
			// 	// ->asText();
			// 	->asStream();
			//
			// // die($response->text);
			//
			// // Process each chunk as it arrives
			// foreach ($response as $chunk) {
			// 	// Write each chunk directly to output without buffering
			// 	$this->output->write($this->green($chunk->text));
			// }


			$stream = Prism::text()
				->using(Provider::XAI, 'grok-4')
				->withPrompt('Explain quantum entanglement in detail')
				->asStream();

			foreach ($stream as $chunk) {
				if ($chunk->chunkType === ChunkType::Thinking) {
					echo $chunk->text . PHP_EOL; // Outputs: Thinking...;
				} elseif ($chunk->chunkType === ChunkType::Text) {
					echo $chunk->text;
				}
			}


			$this->output->write("\n\n --DONE!-- \n");
		} catch (\Throwable $th) {
			//throw $th;
			$this->error($th->getMessage());
		}
	}
}
