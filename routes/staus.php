<?php

use App\Http\Controllers\StatusCodes\Http_200;
use App\Http\Controllers\StatusCodes\Http_201;
use App\Http\Controllers\StatusCodes\Http_204;
use App\Http\Controllers\StatusCodes\Http_300;
use App\Http\Controllers\StatusCodes\Http_301;
use App\Http\Controllers\StatusCodes\Http_302;
use App\Http\Controllers\StatusCodes\Http_304;
use App\Http\Controllers\StatusCodes\Http_400;
use App\Http\Controllers\StatusCodes\Http_401;
use App\Http\Controllers\StatusCodes\Http_403;
use App\Http\Controllers\StatusCodes\Http_404;
use App\Http\Controllers\StatusCodes\Http_405;
use App\Http\Controllers\StatusCodes\Http_409;
use App\Http\Controllers\StatusCodes\Http_422;
use App\Http\Controllers\StatusCodes\Http_429;
use App\Http\Controllers\StatusCodes\Http_500;
use App\Http\Controllers\StatusCodes\Http_501;
use App\Http\Controllers\StatusCodes\Http_502;
use App\Http\Controllers\StatusCodes\Http_503;
use App\Http\Controllers\StatusCodes\Http_504;
use App\Http\Controllers\StatusCodes\Http_Random;
use Illuminate\Support\Facades\Route;



// 2xx Success
Route::get('/200', Http_200::class)->name('200');
Route::get('/201', Http_201::class)->name('201');
Route::get('/204', Http_204::class)->name('204');

// 3xx Redirection
Route::get('/300', Http_300::class)->name('300');
Route::get('/301', Http_301::class)->name('301');
Route::get('/302', Http_302::class)->name('302');
Route::get('/304', Http_304::class)->name('304');

// 4xx Client Errors
Route::get('/400', Http_400::class)->name('400');
Route::get('/401', Http_401::class)->name('401');
Route::get('/403', Http_403::class)->name('403');
Route::get('/404', Http_404::class)->name('404');
Route::get('/405', Http_405::class)->name('405');
Route::get('/409', Http_409::class)->name('409');
Route::get('/422', Http_422::class)->name('422');
Route::get('/429', Http_429::class)->name('429');

// 5xx Server Errors
Route::get('/500', Http_500::class)->name('500');
Route::get('/501', Http_501::class)->name('501');
Route::get('/502', Http_502::class)->name('502');
Route::get('/503', Http_503::class)->name('503');
Route::get('/504', Http_504::class)->name('504');


// Random status range
Route::get('/{codes}', Http_Random::class)->where('codes', '^[1-5][0-9]{2}(?:,[1-5][0-9]{2})*$')->name('http.random');
