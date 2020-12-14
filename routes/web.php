<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Static Route
Route::get('/', 'ControllerBase@index');
Route::match(['GET', 'POST'], 'login', 'UserController@login')->name('login');

Route::get('dashboard', 'UserController@dashboard')->middleware('auth');
Route::get('logout', 'UserController@logout')->middleware('auth');

Route::post('{dir}/{model}/simple_json', 'FormController@simpleJson')->middleware('auth');

// Dynamic Route
Route::get('{dir}/{model}', 'FormController@indexList')->middleware('auth');
Route::match(['GET', 'POST'], '{dir}/{model}/{id}', 'FormController@edit')->middleware('auth');

