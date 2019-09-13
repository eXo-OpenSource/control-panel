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
Auth::routes(['register' => false]);

Route::resource('users', 'UserController');
Route::resource('factions', 'FactionController');
Route::resource('companies', 'CompanyController');
Route::resource('groups', 'GroupController');
Route::get('/home', 'HomeController@index')->name('home');

