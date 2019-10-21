<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. 
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/invoices','InvoiceController@index')->name('invoices.index');
Route::get('/invoices/create','InvoiceController@create')->name('invoices.create');
Route::post('/invoices','InvoiceController@store')->name('invoices.store');
Route::get('/invoices/{id}','InvoiceController@show')->name('invoices.show');
Route::get('/invoices/{id}/edit','InvoiceController@edit')->name('invoices.edit');
Route::put('/invoices/{id}','InvoiceController@update')->name('invoices.update');
Route::delete('/invoices/{id}','InvoiceController@destroy')->name('invoices.destroy');

Route::get('/redirect', 'SocialAuthGoogleController@redirect');
Route::get('/callback', 'SocialAuthGoogleController@callback');