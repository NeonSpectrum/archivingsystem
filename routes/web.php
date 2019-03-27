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

Route::get('login', 'LoginController@show')->name('login')->middleware('guest');
Route::post('login', 'LoginController@process');

Route::get('logout', 'LoginController@logout');

Route::middleware('auth')->group(function () {
  Route::get('/', 'DataController@show')->name('dashboard');
  Route::get('/college', 'DataController@showCollege')->name('dashboard.college');
  Route::get('/all', 'DataController@showAll')->name('dashboard.all');
  Route::match(['get', 'post'], 'pdf', 'DataController@pdf')->name('pdf');
  Route::match(['get', 'post'], 'excel', 'DataController@excel')->name('excel');
  Route::get('accounts', 'AccountController@show')->name('account')->middleware('role:college-admin');
  Route::get('colleges', 'CollegeController@show')->name('college')->middleware('role:super-admin');
  Route::get('logs', 'LogsController@show')->name('logs')->middleware('role:super-admin');
  Route::get('uploads/{title}/{id}', 'DataController@showUpload');
});
