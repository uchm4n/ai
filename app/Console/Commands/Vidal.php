<?php

namespace App\Console\Commands;

use App\Models\Drug;
use App\Models\Substance;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Console\Command;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Concerns\ProvidesBrowser;

use function Laravel\Prompts\textarea;

class Vidal extends Command
{
	use ProvidesBrowser;

	protected $description = 'Vidal scraping';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'apps:vidal';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->info('Starting Vidal Scraping...');

		try {

			// $textarea = textarea('Enter your text here','Enter your text here');

			// $drugs = Substance::query()->where('title', 'like', '%aceclofenac%')
			// 	->first()
			// 	->drugs()
			// 	->get(['id','url','title_ka','title_en','img'])
			// 	->toArray();

			$response = Prism::embeddings()
				->using(Provider::Ollama, 'mxbai-embed-large:latest')
				->fromInput('Your text goes here')
				->generate();

			dd($response);
			$drugs = Drug::take(50000)
				->get()
				// ->get(['id','url','title_ka','title_en','img'])
				->each(function (Drug $drug) {
					$substance = $drug->substance()->find($drug->substance_id);
					dd($substance);
					
				});


			// replace all non-alphanumeric characters
			dd('done');

		} catch (\Exception $e) {
			logger()->error('Error: ' . $e->getMessage(), $e->getTrace());
			$this->error('An error occurred: ' . $e->getMessage());
		}

		$this->info('Vidal scraping completed.');
	}

	/**
	 * Create the RemoteWebDriver instance.
	 */
	protected function driver(): RemoteWebDriver
	{
		$options = (new ChromeOptions)->addArguments(
			collect([
				'--start-maximized',
				'--disable-search-engine-choice-screen',
				'--disable-gpu',
				'--headless=new',
			])->all(),
		);

		return RemoteWebDriver::create(
			$_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
			DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options),
		);
	}
}
