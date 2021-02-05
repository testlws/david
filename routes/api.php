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

//Auth::routes(['verify' => true]);

Route::group(['middleware' => 'auth:api'], function(){
    // Users
    Route::get('users', 'UserController@index')->middleware('isAdmin');
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
});

//Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('check-email', 'AuthController@checkEmail');
    Route::get('refresh', 'AuthController@refresh');
    // Send reset password mail
    Route::post('reset-password', 'AuthController@sendPasswordResetLink');    
    // handle reset password form process
    Route::post('reset/password', 'AuthController@callResetPassword');

    Route::group(['middleware' => 'auth:api'], function(){   
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('todos', 'Api\\TodoController@index');
    Route::group(['prefix' => 'todos'], function () {
        Route::post('create', 'Api\\TodoController@create');
        Route::post('update/{id}', 'Api\\TodoController@update');
        Route::delete('delete/{id}', 'Api\\TodoController@delete');
    });
});