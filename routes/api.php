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

Route::namespace('Admin\\Api')->prefix('admin')->name('api.admin.')->group(function () {
    Route::resource('screenshots', 'ScreenshotController')->only('store');
    Route::resource('screencaptures', 'ScreencaptureController')->only('store');
});

Route::namespace('Api')->name('api.')->group(function () {
    Route::get('users/online', 'UserOnlineController@index')->name('users.online.index');
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
        Route::resource('polls', 'PollController')->only('index', 'store', 'show');
        Route::resource('tickets', 'TicketController')->only('index');
    });

    Route::namespace('Api')->name('api.')->group(function () {
        Route::resource('charts', 'ChartController')->only('show');
        Route::resource('histories', 'HistoryController')->only('show');
        Route::resource('vehicles', 'VehicleController')->only('show');
        Route::resource('tickets/categories', 'TicketCategoryController')->only('index');
        Route::put('tickets/settings', 'TicketSettingController@update')->name('tickets.settings.update');
        Route::patch('tickets/settings', 'TicketSettingController@update')->name('tickets.settings.update');
        Route::resource('tickets', 'TicketController');
        Route::resource('trainings', 'TrainingController')->only('index', 'show', 'update');
        Route::post('users/search', 'User\UserSearchController@index')->name('users.search');
        Route::get('users/list', 'User\UserListController@index')->name('users.list');
        Route::post('users/list', 'User\UserListController@index')->name('users.list');
    });
});

Route::namespace('Api\\Shop')->prefix('shop')->name('api.shop.')->group(function () {
    Route::post('/payments/notifications/paypal', 'PaymentNotificationController@paypal')->name('payment.notification.paypal');
    Route::post('/payments/notifications/paysafecard', 'PaymentNotificationController@paysafecard')->name('payment.notification.paysafecard');
    Route::post('/payments/notifications/klarna', 'PaymentNotificationController@klarna')->name('payment.notification.klarna');
    Route::get('/payments/status/{payment_id}', 'PaymentApiController@status')->name('payments.status');
});
