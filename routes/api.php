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

Route::group(['prefix' => 'user', 'namespace' => 'API', 'guard' => 'api'], function () {
    // config(['auth.defaults.guard' => 'api']); // change default guard from config/auth

    // user auth
    Route::post('register', 'Auth\UserAuth@register');
    Route::post('login', 'Auth\UserAuth@login');

    // authunticated user
    Route::group(['middleware' => 'jwt.verify'], function () {
        Route::post('refresh', 'Auth\UserAuth@refresh'); // refresh token
        Route::get('detail', 'Auth\UserAuth@detail'); // get user detail
        Route::any('logout', 'Auth\UserAuth@Logout'); // logout
    });
});
