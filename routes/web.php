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

Route::get('/', function () {
    return view('frontend.welcome');
});

Route::get('/sqli','App\Http\Controllers\SearchController@index')->name('search');

Route::get('/create','App\Http\Controllers\ItemController@create')->name('create');
Route::post('/store','App\Http\Controllers\ItemController@store')->name('store');
