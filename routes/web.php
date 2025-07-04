<?php

use App\Console\Tools\Models;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TutorialsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth','verified'])->group(function () {
	Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
	Route::group(['prefix' => 'tutorials'], function () {
		Route::get('/tenzies', [TutorialsController::class, 'tenzies'])->name('tutorials.tenzies');
		Route::get('/word', [TutorialsController::class, 'word'])->name('tutorials.word');
		Route::get('/generate', [TutorialsController::class, 'wordGenerate'])->name('tutorials.word.generate');
		Route::get('/tosty', [TutorialsController::class, 'tosty'])->name('tutorials.tosty');
	});

	Route::match(['GET','POST'],'/stream', [Dashboard::class, 'stream'])->name('ai.stream');
	Route::match(['GET','POST'],'/streamG', [Dashboard::class, 'streamG'])->name('ai.streamG');
	Route::match(['GET','POST'],'/send', [Dashboard::class, 'send'])->name('ai.send');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::post('/text-to-speech', [Dashboard::class, 'textToSpeech'])->name('dashboard.text-to-speech');


Route::get('/streaming', function () {
	return response()->eventStream(function () {
		$response = Prism::text()
			->using(Provider::Gemini, Models::Gemini2_0->value)
			->withProviderMeta(Provider::Gemini, ['searchGrounding' => true])
			->withMaxSteps(4)
			->withPrompt('What\'s the weather like in San Francisco today? And tel me a short story about the weather')
			->usingTemperature(2)
			->asStream();

		// Process each chunk as it arrives
		foreach ($response as $chunk) {
			// Write each chunk directly to output without buffering
			yield $chunk->text;
		}
	});
});

require __DIR__.'/auth.php';
require __DIR__.'/staus.php';
