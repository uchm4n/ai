<?php

namespace App\Console\Commands;

use App\Models\Substance;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Console\Command;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Concerns\ProvidesBrowser;

class Vidal extends Command
{
	use ProvidesBrowser;

	public $name = 'app:vidal';

	protected $description = 'Vidal scraping';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:vidal';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->info('Starting Vidal Scraping...');

		try {
			$this->browse(function (Browser $browser)  {

				Substance::all()->map(function ($item) use (&$browser) {
					dd($item);
					$browser->visit($url);
					$figures = $browser->elements('figure');

					foreach ($figures as $figure) {
						$figure->findElement('img');
					}


				});


				//
				// $elements = $browser->elements('table td a');
				//
				// foreach ($elements as $element) {
				// 	if (!empty($element->getText())) {
				// 		Substance::updateOrCreate([
				// 			'title' => $element->getText() ?? ' ',
				// 			'url'   => $element->getAttribute('href') ?? ' ',
				// 		]);
				//
				// 		logger([
				// 			'title' => $element->getText(),
				// 			'url'   => $element->getAttribute('href'),
				// 		]);
				// 	}
				// }
				$browser->quit();
			});
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
