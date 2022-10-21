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


Route::group(['prefix' => 'v1'], function () {
    
    Route::post('/register', 'API\AuthController@register')->name('api.register');
    Route::post('/login', 'API\AuthController@login')->name('api.login');
    Route::post('/url-shorten', 'API\UrlGenController@urlGen')->name('api.shorten');
    Route::post('/url-shorten-be', 'API\UrlGenController@urlGenBackEnd')->name('api.shorten.bg');
    Route::post('/get-original-url', 'API\UrlGenController@getOriginalUrl')->name('url.getOriginalUrl');

    Route::fallback(function () {
        return view('errors.api');
    });
});


Route::group(['prefix' => 'v2', 'middleware' => 'api'], function () {
    Route::Post('/chk-password', 'Auth\ChangePasswordController@checkPassword')->name('chk.pass');
    Route::Post('/chk-username', 'Auth\ProfilesController@checkUsername')->name('chk.username');
    Route::Post('/chk-email', 'Auth\ProfilesController@checkEmail')->name('chk.email');
 

    Route::fallback(function () {
        return view('errors.404');
    });
});

    Route::fallback(function () {
        return view('errors.api');
    });

