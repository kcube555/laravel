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

Route::get('/', 'ControllerBase@index');

Route::match(['GET', 'POST'], 'login', 'UserController@login')->name('login');
// Route::match(['GET', 'POST'], 'store', 'UserController@registration');
Route::get('logout', 'UserController@logout')->middleware('auth');

Route::get('dashboard', 'UserController@dashboard')->middleware('auth');

Route::get('{dir}/{model}', 'FormController@list')->middleware('auth');
Route::get('{dir}/{model}/{id}', 'FormController@edit')->middleware('auth');

