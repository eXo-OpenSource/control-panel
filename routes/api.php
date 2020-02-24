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
    Route::namespace('Admin\\Api')->middleware('admin')->prefix('admin')->name('api.admin.')->group(function () {
        Route::resource('factions', 'FactionController')->only('index');
        Route::resource('users', 'UserController')->only('update');
        Route::resource('users.warns', 'UserWarnController')->only('index');
    });

    Route::namespace('Api')->name('api.')->group(function () {
        Route::resource('charts', 'ChartController')->only('show');
        Route::resource('histories', 'HistoryController')->only('show');
        Route::resource('vehicles', 'VehicleController')->only('show');
        Route::resource('tickets/categories', 'TicketCategoryController')->only('index');
        Route::resource('tickets', 'TicketController');
        Route::post('users/search', 'User\UserSearchController@index')->name('user.search');

    });
});
