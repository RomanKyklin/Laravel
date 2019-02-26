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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::namespace('Admin')->prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/users', 'UserController@index')->name('admin.users');
});

Route::namespace('Parsers')->prefix('parser')->group(function () {
   Route::get('/avito', 'ParserController@avitoList')->name('avito.list');
});
