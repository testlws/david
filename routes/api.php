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

Route::group(['middleware' => 'auth:api'], function(){
    // Users
    Route::get('users', 'UserController@index')->middleware('isAdmin');
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
});

//Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');

    Route::group(['middleware' => 'auth:api'], function(){   
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::get('products', 'Api\ProductController@index');
Route::get('categories', 'Api\CategoryController@index');
Route::get('links', 'Api\LinkController@index');
Route::get('links/{category_id}', 'Api\LinkController@byCategory');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('links/{link}/like', 'Api\LinkController@like')->name('links.like');
    Route::post('links/{link}/unlike', 'Api\LinkController@unlike')->name('links.unlike');
    Route::post('links/{link}/dislike', 'Api\LinkController@dislike')->name('links.dislike');
    Route::post('links/{link}/undislike', 'Api\LinkController@undislike')->name('links.undislike');
});

