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


Route::middleware('auth:api')->group(function () {
    Route::namespace('Api\\User')->name('user-api.')->group(function () {
        Route::resource('groups.bank', 'GroupBankAccountController')->only('index');
    });
});

Route::namespace('Api\\User')->name('user-api.')->group(function () {
    Route::resource('factions', 'FactionController')->only('index', 'show');
});
