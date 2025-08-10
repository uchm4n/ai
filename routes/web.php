<?php

use App\Console\Tools\Models;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TutorialsController;
use App\Mcp\CalculatorElements;
use App\Modules\Order\Http\Controllers\OrderController as ModulesOrderController;
use App\Modules\Product\Http\Controllers\ProductController as ModulesProductController;
use App\Modules\User\Http\Controllers\UserController as ModulesUserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use PhpMcp\Laravel\Facades\Mcp;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;

Route::get('/', fn () => Inertia::render('Welcome', [
    'canLogin'       => Route::has('login'),
    'canRegister'    => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion'     => PHP_VERSION,
]));

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'tutorials'], function (): void {
        Route::get('/tenzies', [TutorialsController::class, 'tenzies'])->name('tutorials.tenzies');
        Route::get('/word', [TutorialsController::class, 'word'])->name('tutorials.word');
        Route::get('/generate', [TutorialsController::class, 'wordGenerate'])->name('tutorials.word.generate');
        Route::get('/tosty', [TutorialsController::class, 'tosty'])->name('tutorials.tosty');
    });

    Route::match(['GET', 'POST'], '/stream', [Dashboard::class, 'stream'])->name('ai.stream');
    Route::match(['GET', 'POST'], '/streamG', [Dashboard::class, 'streamG'])->name('ai.streamG');
    Route::match(['GET', 'POST'], '/send', [Dashboard::class, 'send'])->name('ai.send');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::post('/text-to-speech', [Dashboard::class, 'textToSpeech'])->name('dashboard.text-to-speech');

Route::get('/streaming', fn () => response()->eventStream(function () {
    $response = Prism::text()
        ->using(Provider::Gemini, Models::Gemini2_0->value)
        ->withMaxSteps(4)
        ->withPrompt('What\'s the weather like in San Francisco today? And tel me a short story about the weather')
        ->usingTemperature(2)
        ->asStream();

    // Process each chunk as it arrives
    foreach ($response as $chunk) {
        // Write each chunk directly to output without buffering
        yield $chunk->text;
    }
}));

Route::get('/good-js', fn () => view('good-js'))->name('good-js');

// Demo routes for modular DDD structure
Route::prefix('modules')->group(function (): void {
    Route::prefix('users')->group(function (): void {
        Route::post('/', (new ModulesUserController())->store(...));
        Route::put('/{id}', (new ModulesUserController())->update(...));
        Route::delete('/{id}', (new ModulesUserController())->destroy(...));
    });
    Route::prefix('orders')->group(function (): void {
        Route::post('/', [ModulesOrderController::class, 'store']);
        Route::put('/{id}/process', [ModulesOrderController::class, 'process']);
        Route::delete('/{id}', [ModulesOrderController::class, 'cancel']);
    });
    Route::prefix('products')->group(function (): void {
        Route::post('/', [ModulesProductController::class, 'store']);
    });
});

// //////////// Register MCP tools //////////////////
// /
Mcp::tool([CalculatorElements::class, 'add'])->description('Add two numbers together')->name('add_numbers');
// Register a resource with metadata
Mcp::resource('config://app/settings', [CalculatorElements::class, 'getAppSettings'])
    ->name('app_settings')
    ->description('Application configuration settings')
    ->mimeType('application/json')
    ->size(100);
// //////////// MCP tools //////////////////

require __DIR__.'/auth.php';
require __DIR__.'/staus.php';
