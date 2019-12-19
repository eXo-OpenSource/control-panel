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




Route::namespace('Admin\\Api')->prefix('admin')->name('api.admin.')->group(function () {
    Route::resource('factions', 'FactionController')->only('index');
});
Route::middleware('auth')->group(function () {
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
