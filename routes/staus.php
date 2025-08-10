<?php

use App\Http\Controllers\StatusCodes\Http_Random;
use App\Http\Controllers\StatusCodes\HttpStatusController;
use Illuminate\Support\Facades\Route;

// Handle single HTTP status codes
Route::get('/{code}', HttpStatusController::class)
    ->where('code', '^[1-5][0-9]{2}$')
    ->name('http.status');

// Handle random status from comma-separated list
Route::get('/random/{codes}', Http_Random::class)
    ->where('codes', '^[1-5][0-9]{2}(?:,[1-5][0-9]{2})*$')
    ->name('http.random');
