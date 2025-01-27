<?php

use App\Models\Substance;
use Facebook\WebDriver\WebDriverBy;
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

			Substance::all()->map(function (Substance $item) use ($browser) {
				$figure = [];
				$browser->visit($item->url);
				$figures = $browser->elements('figure figcaption h6 a');
				foreach ($figures as $figure) {
					$href = $figure->getAttribute('href');

					$browser2 = $browser->visit($href);
					foreach ($browser2->elements('figure') as $article) {
						$img = $article->findElement(WebDriverBy::cssSelector('article a img'));
						$ul = $article->findElements(WebDriverBy::cssSelector('article div ul li'));
						$body = $article->findElements(WebDriverBy::cssSelector('figcaption p'));
						dump($img->getAttribute('src'),$body[3]->getText());
						$browser->back();
					}

					// $item->drugs()->updateOrCreate([
					//
					// ]);
				}

				return $figure;
			});

			$browser->stop();
		});


	} catch (\Exception $e) {
		logger()->error('Error: ' . $e->getMessage(), $e->getTrace());
	}


});
