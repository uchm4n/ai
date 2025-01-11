<?php

use App\Http\Controllers\Dashboard;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TutorialsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
	Route::match(['GET','POST'],'/send', [Dashboard::class, 'send'])->name('ai.send');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
