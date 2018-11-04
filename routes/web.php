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

Route::get('/login', 'LoginController@show')->name('login');
Route::post('/login', 'LoginController@process');

Route::get('/logout', 'LoginController@logout');

Route::middleware('auth')->group(function () {
  Route::get('/', 'DataController@show')->name('dashboard');
  Route::get('/data', 'DataController@get');
  Route::get('/data/{id}', 'DataController@get');
  Route::post('/data/add', 'DataController@add');
  Route::post('/data/edit', 'DataController@edit');
  Route::post('/data/delete', 'DataController@delete');
  Route::post('/data/upload', 'DataController@upload');
});
