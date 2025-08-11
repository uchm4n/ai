<?php

use Illuminate\Support\Facades\Route;
use Modules\StatusCodes\Http_Random;
use Modules\StatusCodes\HttpStatusController;

// Handle single HTTP status codes
Route::get('/status/{code}', HttpStatusController::class)
    ->where('code', '^[1-5][0-9]{2}(?:,[1-5][0-9]{2})*$')
    ->name('http.status');

// Handle random status from comma-separated list
Route::get('/random/{codes}', Http_Random::class)
    ->where('codes', '^[1-5][0-9]{2}(?:,[1-5][0-9]{2})*$')
    ->name('http.random');
