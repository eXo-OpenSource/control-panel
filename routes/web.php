<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::namespace('Auth')->prefix('auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::get('who-is-online', 'WhoIsOnlineController@index')->name('who.is.online');
Route::get('statistics', 'StatisticsController@index')->name('statistics');

Route::resource('groups', 'GroupController')->only('index', 'show');
Route::get('groups/{group}/{page}', 'GroupController@show')->name('groups.show.page');

Route::resource('factions', 'FactionController')->only('index', 'show');
Route::get('factions/{faction}/{page}', 'FactionController@show')->name('factions.show.page');

Route::resource('companies', 'CompanyController')->only('index', 'show');
Route::get('companies/{company}/{page}', 'CompanyController@show')->name('companies.show.page');



Route::middleware('auth')->group(function () {
    Route::resource('users', 'UserController')->only('index', 'show');
    Route::get('users/{user}/{page}', 'UserController@show')->name('users.show.page');

    Route::get('companies/{company}/{page}', 'CompanyController@show')->name('companies.show.page');
    Route::resource('textures', 'TextureController');
    Route::resource('teamspeak', 'TeamspeakController');

    Route::namespace('Event')->prefix('events')->name('events.')->group(function () {
        Route::resource('santa', 'SantaController')->only(['index', 'store', 'create']);
    });

    Route::namespace('Admin')->prefix('admin')->group(function () {
        Route::resource('dashboard', 'DashboardController', ['as' => 'admin'])->only('index');
        Route::resource('users.logs', 'UserLogController', ['as' => 'admin'])->only('index', 'show');
        Route::resource('users', 'UserController', ['as' => 'admin'])->only('update');
        Route::get('users/search', 'UserSearchController@index')->name('admin.user.search');
        Route::get('users/forum/{forumId}', 'ForumUserController@show')->name('admin.user.forum');
        Route::get('textures', 'TextureController@index')->name('admin.texture');
    });
});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
