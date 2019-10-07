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

Route::middleware('auth')->group(function () {
    Route::resource('users', 'UserController');
    Route::resource('factions', 'FactionController');
    Route::resource('companies', 'CompanyController');
    Route::resource('groups', 'GroupController');
    Route::resource('textures', 'TextureController');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::namespace('Admin')->prefix('admin')->group(function () {
        Route::resource('dashboard', 'DashboardController')->only('index');
    });
});

Route::get('/2D5DBD7FFEE15070A8EFADE1491690879A80B736E51C891D65791030387DE112', function () {
    $mtaService = new \App\Services\MTAService();

    return $mtaService->kickPlayer(1, 3, "Da war er weg...");
});
