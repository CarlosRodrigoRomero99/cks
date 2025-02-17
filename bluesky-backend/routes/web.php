<?php

use App\Http\Controllers\BlueSkyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('throttle:100,1')->group(function () {
    Route::get('/search', [BlueSkyController::class, 'search']);
});