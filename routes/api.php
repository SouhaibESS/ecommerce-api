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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

route::get('products','ProductController@index');
route::get('product/{product}','ProductController@show');   
route::post('product/{product}','ProductController@order');

route::delete('order/{order}','OrderController@destroy');
 
route::post('contact-us','MessageController@store');

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::get('profile', 'UserController@getAuthentificatedUser');

Route::group(['middleware' => 'auth:api'], function(){
    route::post('product','ProductController@store');
    route::put('product','ProductController@store');
    route::delete('product/{product}','ProductController@destroy');
    
    route::get('orders','OrderController@index');
    
    // update the order if it's done 
    route::put('order/{order}','OrderController@markAsOrdered');
    
    // get a json response of all the messages in the database
    route::get('contact-us','MessageController@index');
});