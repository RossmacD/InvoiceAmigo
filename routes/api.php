<?php

use App\Http\Controllers\SearchController;
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
//Only display to logged in users
Route::middleware('auth:api')->group(function () {
    //User Auth
    Route::get('user', 'PassportController@user');
    Route::get('logout', 'PassportController@logout');

    //Dashboard
    Route::get('dashboard','DashboardController');

    //Products
    Route::apiResource('/products', 'ProductController');
    Route::get('/search/products', 'SearchController@searchProducts')->name('products.search');

    //Services
    Route::apiResource('/services', 'ServiceController');
    Route::get('/search/services', 'SearchController@searchServicesOnly')->name('service.search');

    //Invoices
    Route::apiResource('/invoices', 'InvoiceController');
    Route::get('/invoice/create','InvoiceController@create');

    //Businesses
    Route::apiResource('/businesses', 'BusinessController');

    //Stripe
    // Route::get('/stripe', 'PaymentController@index')->name('stripe.index');
    Route::get('/pay/{id}', 'PaymentController@paySingleInvoice')->name('stripe.paySingleInvoice');

    //cPanel
    Route::get('/cpanel', 'CpanelController@searchAccounts');

});

//User Auth
Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');

Route::post('/callback', 'SocialController@callback');

//Password Reset
Route::group([    
    'namespace' => 'Auth'
], function () {    
    Route::post('/password/create', 'PasswordResetController@create');
    Route::get('/password/find/{token}', 'PasswordResetController@find');
    Route::post('/password/reset', 'PasswordResetController@reset');
});

