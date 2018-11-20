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
Route::group(['prefix' => 'data'], function () {
  Route::get('{id?}', 'DataController@get');
  Route::post('/', 'DataController@add');
  Route::put('{id}', 'DataController@edit');
  Route::delete('{id}', 'DataController@delete');

  Route::post('upload', 'DataController@upload');
});
Route::group(['prefix' => 'user'], function () {
  Route::post('changepassword', 'AccountController@changePassword');
  Route::get('config', 'AccountController@config');
  Route::group(['middleware' => 'role:admin'], function () {
    Route::get('{id?}', 'AccountController@get');
    Route::post('/', 'AccountController@add');
    Route::put('{id}', 'AccountController@edit');
    Route::delete('{id}', 'AccountController@delete');
  });
});
