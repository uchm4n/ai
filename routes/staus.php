<?php

use Illuminate\Support\Facades\Route;
use Modules\StatusCodes\Http\Controllers\HttpStatusController;

// Handle single HTTP status codes
Route::get('/{code}', [HttpStatusController::class, 'status'])
    ->where('code', '^[1-5][0-9]{2}(?:,[1-5][0-9]{2})*$')
    ->name('http.status');
