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
    return view('stripePayment');
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

Route::get('/products','ProductController@index')->name('products.index');
Route::get('/products/create','ProductController@create')->name('products.create');
Route::post('/products','ProductController@store')->name('products.store');
Route::get('/products/{id}','ProductController@show')->name('products.show');
Route::get('/products/{id}/edit','ProductController@edit')->name('products.edit');
Route::put('/products/{id}','ProductController@update')->name('products.update');
Route::delete('/products/{id}','ProductController@destroy')->name('products.destroy');

Route::get('/redirect', 'SocialAuthGoogleController@redirect');
Route::get('/callback', 'SocialAuthGoogleController@callback');


Route::get('/stripe','StripePaymentController@index')->name('stripePayment');
