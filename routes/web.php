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

//Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/redirect', 'SocialController@redirect');
// Route::get('/callback', 'SocialController@callback');
Route::get('/{any}', 'AppController@index')->where('any', '.*');