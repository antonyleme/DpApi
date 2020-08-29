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
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::post('register', 'UserController@store');

Route::post('products', 'ProductController@store');
Route::get('products', 'ProductController@index');
Route::get('products/{id}', 'ProductController@show');
Route::delete('products/{id}', 'ProductController@destroy');
Route::put('products/{id}', 'ProductController@update');

Route::post('categories', 'CategoryController@store');
Route::get('categories', 'CategoryController@index');
Route::get('categories/{id}', 'CategoryController@products');
Route::delete('categories/{id}', 'CategoryController@destroy');
Route::put('categories/{id}', 'CategoryController@update');

Route::put('users', 'UserController@update');
Route::get('users/demands', 'UsersDemandController@userDemands');

Route::post('demands', 'CheckoutController@registerDemand');
