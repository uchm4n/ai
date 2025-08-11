<?php

use Illuminate\Support\Facades\Route;
use Modules\Posts\Http\Controllers\PostsController;

Route::prefix('posts')->group(function (): void {
    Route::get('/', [PostsController::class, 'index'])->name('posts.index');
    Route::get('/{id}', [PostsController::class, 'show'])->whereNumber('id')->name('posts.show');
    Route::post('/', [PostsController::class, 'store'])->name('posts.store');
    Route::match(['put', 'patch'], '/{id}', [PostsController::class, 'update'])->whereNumber('id')->name('posts.update');
});
