<?php

use App\Models\Drug;
use App\Models\Substance;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;

// test('basic example', function () {
// 	$url = rtrim(env('VIDAL_URL'), '/') . '/';
// 	try {
// 		$this->browse(function (Browser $browser)  use ($url) {
//
//
// 			collect(range('a', 'z'))->map(fn($letter) => $url . $letter)->each(function ($url) use (&$browser) {
// 				$browser->visit($url);
//
// 				logger()->info("Start Scraping Url: $url");
//
// 				$elements = $browser->elements('table td a');
//
// 				foreach ($elements as $element) {
// 					if (!empty($element->getText())) {
// 						Substance::updateOrCreate([
// 							'title' => strtolower($element->getText()),
// 							'url'   => strtolower($element->getAttribute('href')),
// 						]);
//
// 						logger([
// 							'title' => $element->getText(),
// 							'url'   => $element->getAttribute('href'),
// 						]);
// 					}
// 				}
//
// 				logger()->notice("Complete Scraping Url: $url");
// 			});
//
// 			$browser->quit();
// 			return true;
// 		});
//
//
// 	} catch (\Exception $e) {
// 		logger()->error('Error: ' . $e->getMessage(), $e->getTrace());
// 	}
//
//
// });

test('basic example', function () {

	try {

		$this->browse(function (Browser $browser) {
			$stream = Storage::readStream('hrefs.txt');
			while (($line = fgets($stream)) !== false) {
				$url = trim($line);
				$browser->visit($url);
				getBodyData($browser, $url)->map(function($item) use($url){
					Substance::query()->where('title', $item['substance'])
						->first()
						->drugs()
						->updateOrCreate([
							'url'      => $url,
							'title_ka' => $item['title'][0],
							'title_en' => $item['title'][1],
							'img'      => $item['img'],
							'all'      => $item['body'],
						]);

				});
				// $browser->quit(); // stop after 1 iteration
				// dd('done');
			}

			fclose($stream); // Close the stream after reading
			$browser->quit();
		});


	} catch (\Exception $e) {
		logger()->error('Error: ' . $e->getMessage(), $e->getTrace());
	}


});

function getBodyData(Browser $browser, string $href)
{
	$data = [];
	$browser = $browser->visit($href);
	foreach ($browser->elements('figure') as $article) {
		$img = $article->findElement(WebDriverBy::cssSelector('article a img'))->getAttribute('src');
		$title = $article->findElement(WebDriverBy::cssSelector('article div[class="col-xs-12 col-sm-7 p_x_0"] h6'))->getText();
		$substance = $article->findElement(WebDriverBy::cssSelector('article div ul li a'))->getText();
		$head = collect($article->findElements(WebDriverBy::cssSelector('article div ul li')))
			->map(fn($item) => $item->getText())->implode(PHP_EOL);
		$body = collect($article->findElements(WebDriverBy::cssSelector('figcaption p')))
			->map(fn($item) => $item->getText())->implode(PHP_EOL);

		$data[] = [
			'img' => $img,
			'title' => str($title)->explode('/')->map(fn($s) => trim($s)),
			'substance' => str($substance)->lower()->value(),
			'head' => $head,
			'body' => $body,
		];
 	}


	return collect($data);
}
