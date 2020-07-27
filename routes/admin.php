<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'namespace' => 'API', 'guard' => 'admin'], function () {
    // config(['auth.defaults.guard' => 'admin']); // change default guard from config/auth

    // admin auth
    Route::post('register', 'Auth\AdminAuth@register');
    Route::post('login', 'Auth\AdminAuth@login');

    // authunticated admin
    Route::group(['middleware' => ['jwt.verify']], function () {

        Route::post('refresh', 'Auth\AdminAuth@refresh'); // refresh token
        Route::get('me', 'Auth\AdminAuth@me'); // get admin detail
        Route::any('logout', 'Auth\AdminAuth@Logout'); // logout

        // admin
        Route::resource('admin', 'Admin\AdminController', ['only' => ['index', 'update', 'destroy']]);
        // user
        Route::resource('users', 'User\UserController', ['only' => ['index', 'update', 'destroy']]);
        //  countries
        Route::resource('countries', 'Country\CountryController', ['except' => ['edit', 'create']]);
        //  cities
        Route::resource('cities', 'City\CityController', ['except' => ['edit', 'create']]);
        //  states
        Route::resource('states', 'State\StateController', ['except' => ['edit', 'create']]);
        // departments
        Route::resource('departments', 'Department\DepartmentController',  ['except' => ['edit', 'create']]);
        //  trademarks
        Route::resource('trademarks', 'Trademark\TrademarksController',  ['except' => ['edit', 'create']]);
        // manufactures
        Route::resource('manufactures', 'Manufacture\ManufactureController',  ['except' => ['edit', 'create']]);
        // shpping
        Route::resource('shipping', 'Shipping\ShippingController',  ['except' => ['edit', 'create']]);
        // malls
        Route::resource('malls', 'Mall\MallController', ['except' => ['edit', 'create']]);
        // colors
        Route::resource('colors', 'Color\ColorController', ['except' => ['edit', 'create']]);
        // sizes
        Route::resource('sizes', 'Size\SizeController', ['except' => ['edit', 'create']]);
        // weights
        Route::resource('weights', 'Weight\WeightController', ['except' => ['edit', 'create']]);
        // products
        Route::resource('products', 'Product\ProductController', ['only' => ['index', 'update', 'destroy']]);
        Route::post('update/product/image/{pid}', 'Product\ProductController@update_main_photo'); // upload main photo
        Route::post('upload/product/files/{pid}', 'Product\ProductController@upload_files'); // upload another files
        Route::post('products/search/{pid}', 'Product\ProductController@product_search'); // search by (title and content)

        // settings
        Route::get('settings', 'Settings\Settings@settings');
        Route::post('settings', 'Settings\Settings@settings_save');
    });

    // language
    Route::get('lang/{lag}', function ($lang) {
        session()->has('lang') ? session()->forget('lang') : '';
        $lang === 'ar' ? session()->put('lang', 'ar') : session()->put('lang', 'en');
        return back();
    });
});
