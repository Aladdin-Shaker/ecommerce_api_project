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
        Route::delete('admin/destroy/all', 'Admin\AdminController@multi_delete');
        // user
        Route::resource('users', 'User\UserController', ['only' => ['index', 'update', 'destroy']]);
        Route::delete('users/destroy/all', 'User\UserController@multi_delete');
        //  countries
        Route::resource('countries', 'Country\CountryController');
        Route::delete('countries/destroy/all', 'Country\CountryController@multi_delete');
        //  cities
        Route::resource('cities', 'City\CityController');
        Route::delete('cities/destroy/all', 'City\CityController@multi_delete');
        //  states
        Route::resource('states', 'State\StateController');
        Route::delete('states/destroy/all', 'State\StateController@multi_delete');
        // departments
        Route::resource('departments', 'Department\DepartmentController');
        //  trademarks
        Route::resource('trademarks', 'Trademark\TrademarksController');
        Route::delete('trademarks/destroy/all', 'Trademark\TrademarksController@multi_delete');
        // manufactures
        Route::resource('manufactures', 'Manufacture\ManufactureController');
        Route::delete('manufactures/destroy/all', 'Manufacture\ManufactureController@multi_delete');
        // shpping
        Route::resource('shipping', 'Shipping\ShippingController');
        Route::delete('shipping/destroy/all', 'Shipping\ShippingController@multi_delete');
        // malls
        Route::resource('malls', 'Mall\MallController');
        Route::delete('malls/destroy/all', 'Mall\MallController@multi_delete');
        // colors
        Route::resource('colors', 'Color\ColorController');
        Route::delete('colors/destroy/all', 'Color\ColorController@multi_delete');
        // sizes
        Route::resource('sizes', 'Size\SizeController');
        Route::delete('sizes/destroy/all', 'Size\SizeController@multi_delete');
        // weights
        Route::resource('weights', 'Weight\WeightController');
        Route::delete('weights/destroy/all', 'Weight\WeightController@multi_delete');
        // products
        Route::resource('products', 'Product\ProductController')->except('show');
        Route::delete('products/destroy/all', 'Product\ProductController@multi_delete');
        Route::post('products/search', 'Product\ProductController@product_search');
        Route::post('upload/image/{pid}', 'Product\ProductController@upload_files'); // upload another files
        Route::post('update/image/{pid}', 'Product\ProductController@update_main_photo'); // upload main photo
        // delete product images from Dropzone
        Route::post('delete/image', 'Product\ProductController@delete_file'); // delete selected file
        Route::post('delete/product/image/{pid}', 'Product\ProductController@delete_main_image'); // delete main photo
        // load size and weight according to department in the product
        Route::post('load/weight/size', 'Product\ProductController@prepare_weight_size');
        // copy product data
        Route::post('products/copy/{pid}', 'Product\ProductController@product_copy');

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
