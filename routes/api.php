<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace('Api')->name('api.')->group(function () {
    Route::get('auth', 'AuthController@auth')->name('auth');
});

Route::middleware('auth')->group(function () {
    Route::namespace('Admin\\Api')->middleware('admin')->prefix('admin')->name('api.admin.')->group(function () {
        Route::resource('factions', 'FactionController')->only('index');
        Route::resource('punish', 'PunishController')->only('show', 'update');
        Route::patch('users/{user}/teamspeak', 'UserTeamSpeakController@update')->name('users.teamspeak');
        Route::put('users/{user}/teamspeak', 'UserTeamSpeakController@update')->name('users.teamspeak');
        Route::resource('users', 'UserController')->only('update');
        Route::resource('users.warns', 'UserWarnController')->only('index', 'destroy', 'store');
        Route::resource('users.punish', 'UserPunishController')->only('store');
        Route::resource('punish.log', 'PunishPunishLogController')->only('index');
    });

    Route::namespace('Api')->name('api.')->group(function () {
        Route::resource('charts', 'ChartController')->only('show');
        Route::resource('histories', 'HistoryController')->only('show');
        Route::resource('vehicles', 'VehicleController')->only('show');
        Route::resource('tickets/categories', 'TicketCategoryController')->only('index');
        Route::resource('tickets', 'TicketController');
        Route::resource('trainings', 'TrainingController')->only('index', 'show', 'update');
        Route::post('users/search', 'User\UserSearchController@index')->name('user.search');
    });
});
