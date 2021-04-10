<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('admin/login', 'AuthController@adminLogin');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::delete('products/{id}', 'ProductController@destroy');
    Route::post('products/update/product', 'ProductController@update');
    Route::put('products/entry/{id}', 'ProductController@productEntry');

    Route::post('categories', 'CategoryController@store');
    Route::delete('categories/{id}', 'CategoryController@destroy');
    Route::put('categories/{id}', 'CategoryController@update');

    Route::put('users', 'UserController@update');
    Route::get('users/demands', 'UsersDemandController@userDemands');

    Route::post('bills', 'BillController@store');
    Route::put('bills/{id}', 'BillController@setPaid');
    Route::delete('bills/{id}', 'BillController@destroy');
    Route::get('bills/{month}/{year}', 'BillController@monthBills');
    Route::get('bills/not-paid', 'BillController@notPaidBills');
    
    Route::get('finance/stats', 'FinanceController@stats');
    Route::get('finance/stats/{date}', 'FinanceController@statsByDate');
    Route::get('finance/stats/{month}/{year}', 'FinanceController@statsByMonth');

    Route::put('dashboard/demands/{id}/{status}', 'DemandsDashboardController@updateStatus');
    Route::post('dashboard/store/demand', 'DemandsDashboardController@storeBalconySale');

    Route::get('users', 'UserController@index');
    
    Route::get('demands', 'UsersDemandController@index');
    Route::get('demands/{date}', 'UsersDemandController@indexByDate');
    
    Route::post('demands', 'CheckoutController@registerDemand');

    Route::get('key/{name}', 'KeyController@getKey');
});

Route::post('register', 'UserController@store');

Route::post('products', 'ProductController@store');
Route::get('products', 'ProductController@index');
Route::get('products/{id}', 'ProductController@show');

Route::post('banners', 'BannerController@store');
Route::get('banners', 'BannerController@index');
Route::get('banners/{id}', 'BannerController@show');
Route::delete('banners/{id}', 'BannerController@destroy');

Route::get('categories', 'CategoryController@index');
Route::get('categories/{id}', 'CategoryController@products');

Route::get('dashboard', 'DemandsDashboardController@stats');
Route::get('dashboard/demands/{status}', 'DemandsDashboardController@demands');

Route::get('delivery-tax', 'DeliveryTaxController@getTax');