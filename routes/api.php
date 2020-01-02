<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth')->group(function () {
    Route::namespace('Admin\\Api')->prefix('admin')->name('api.admin.')->group(function () {
        Route::resource('factions', 'FactionController')->only('index');
    });

    Route::namespace('Api')->name('api.')->group(function () {
        Route::resource('charts', 'ChartController')->only('show');
    });
});
Route::middleware('auth:api')->group(function () {
});

