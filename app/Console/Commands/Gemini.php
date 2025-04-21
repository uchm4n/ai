<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use App\Console\Tools\SearchTool;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Tool;
use Prism\Prism\Prism;
use Prism\Prism\ValueObjects\Messages\Support\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;

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
				->using(Provider::Gemini, Models::Gemini2_5->value)
				// ->withProviderMeta(Provider::Gemini, ['searchGrounding' => true])
				// ->withTools($tools)
				// ->withMaxSteps(50)
				// ->usingTemperature(2)
				// ->withPrompt('what is the drug called: acc, and how can i take it?')
				->withSystemPrompt("If you see any text in the input file just get text If no text than describe image in short. Always return Image 1, Image 2 .. so on for each image in the input file. If you see any text in the image then return the text in the image. If you see any text in the input file just get text If no text than describe image in short. Always return Image 1, Image 2 .. so on for each image in the input file.")
				// ->withPrompt('What do you see in this input file?')
				// ->withPrompt('Generate bounding boxes for each of the objects in this image in [y_min, x_min, y_max, x_max] format.')
				// ->withPrompt('Can you summarize this videos? use bullet points')
				// ->withProviderMeta(Provider::Gemini, [
				// 	'parts' => [
				// 		// 'https://www.youtube.com/watch?v=9hE5-98ZeCg',
				// 		// 'https://live.staticflickr.com/1893/44566807012_d52477be10_b.jpg',
				// 		// '/Users/u/Downloads/ForBiggerBlazes.mp4',
				// 		'/Users/u/Downloads/gem.png',
				// 		'/Users/u/Downloads/300X300.png',
				// 		// 'https://placehold.co/600x400/000000/FFFFFF.png'
				// 		// '/Users/u/www/projects/prism/assets/prism-banner.webp'
				// 	]
				// ])
				->withMessages([
					new UserMessage(
						'Description of the image',
						additionalContent: [
							// Image::fromPath('/Users/u/Downloads/gem.png'),
							// Image::fromPath('/Users/u/Downloads/300X300.png'),
							Image::fromUrl('https://live.staticflickr.com/1893/44566807012_d52477be10_b.jpg'),
							Image::fromUrl('https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png'),
							Image::fromUrl('https://thumbs.dreamstime.com/b/bird-perched-tree-branch-30-36-jpg-5136761.jpg')
						],
					),
				])
				// ->asText();
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
