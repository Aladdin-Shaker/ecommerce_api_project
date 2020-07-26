<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin;
use App\Model\City;
use App\Model\Color;
use App\Model\Country;
use App\Model\Department;
use App\Model\File;
use App\Model\Mall;
use App\Model\Manufacture;
use App\Model\OtherData;
use App\Model\Product;
use App\Model\ProductMall;
use App\Model\RelatedProduct;
use App\Model\Setting;
use App\Model\Shipping;
use App\Model\Size;
use App\Model\State;
use App\Model\TradeMarks;
use App\Model\Weight;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});


$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});


$factory->define(Setting::class, function (Faker $faker) {
    return [
        'sitename_ar' => $faker->name,
        'sitename_en' => $faker->name,
        'logo' => 'logo.png',
        'icon' => 'icon.png',
        'email' => $faker->unique()->safeEmail,
        'main_lang' => 'ar',
        'description' => $faker->text,
        'keywords' => $faker->text,
        'status' => 'open',
        'message_maintenance' => $faker->sentence(20)
    ];
});

$factory->define(File::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'size' => $faker->randomNumber,
        'file' => $faker->name,
        'path' => 'products/29',
        'full_file' => 'products/29/pChv06dmpyR15XzPW1aICOfQUPVgOgENsXlFcVur.jpeg',
        'mime_type' => $faker->mimeType,
        'file_type' => 'product',
        'relation_id' => $faker->randomNumber,
    ];
});

$factory->define(Country::class, function (Faker $faker) {
    return [
        'country_name_ar' => $faker->name,
        'country_name_en' => $faker->name,
        'mob' => '0931814580',
        'code' => 'SY',
        'currency' => 'Lera',
        'logo' => 'logo.png',
    ];
});

$factory->define(City::class, function (Faker $faker) {
    return [
        'city_name_ar' => $faker->name,
        'city_name_en' => $faker->name,
        'country_id' => Country::pluck('id')->random(),
    ];
});

$factory->define(State::class, function (Faker $faker) {
    return [
        'state_name_ar' => $faker->name,
        'state_name_en' => $faker->name,
        'country_id' => Country::pluck('id')->random(),
        'city_id' => City::pluck('id')->random(),
    ];
});

$factory->define(Department::class, function (Faker $faker) {
    return [
        'dep_name_ar' => $faker->name,
        'dep_name_en' => $faker->name,
        'icon' => 'icon.png',
        'description' => $faker->paragraph(1),
        'keyword' => $faker->sentence(1),
        'parent' => $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
    ];
});

$factory->define(TradeMarks::class, function (Faker $faker) {
    return [
        'name_ar' => $faker->name,
        'name_en' => $faker->name,
        'logo' => 'icon.png',
    ];
});

$factory->define(Manufacture::class, function (Faker $faker) {
    return [
        'name_ar' => $faker->name,
        'name_en' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'mobile' => '0931814580',
        'address' => $faker->address,
        'facebook' => $faker->url,
        'twitter' => $faker->url,
        'website' => $faker->url,
        'contact_name' => $faker->name,
        'lat' => '234234324.432423',
        'lng' => '4324234234.234235',
        'icon' => 'icon.png',
    ];
});

$factory->define(Shipping::class, function (Faker $faker) {
    return [
        'name_ar' => $faker->name,
        'name_en' => $faker->name,
        'user_id' => User::pluck('id')->random(),
        'lat' => '',
        'lng' => '',
        'icon' => 'icon.png',
    ];
});

$factory->define(Mall::class, function (Faker $faker) {
    return [
        'name_ar' => $faker->name,
        'name_en' => $faker->name,
        'country_id' => Country::pluck('id')->random(),
        'email' => $faker->unique()->safeEmail,
        'mobile' => '0931814580',
        'address' => $faker->address,
        'facebook' => $faker->url,
        'twitter' => $faker->url,
        'website' => $faker->url,
        'contact_name' => $faker->name,
        'lat' => '',
        'lng' => '',
        'icon' => 'icon.png',
    ];
});

$factory->define(Color::class, function (Faker $faker) {
    return [
        'name_ar' => $faker->name,
        'name_en' => $faker->name,
        'color' => $faker->hexcolor,
    ];
});

$factory->define(Size::class, function (Faker $faker) {
    return [
        'name_ar' => $faker->name,
        'name_en' => $faker->name,
        'is_public' => $faker->randomElement(['yes', 'no']),
        'department_id' => Department::pluck('id')->random(),
    ];
});

$factory->define(Weight::class, function (Faker $faker) {
    return [
        'name_ar' => $faker->name,
        'name_en' => $faker->name,
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'photo' => 'photo.png',
        'content' => $faker->sentence(1),
        'weight' => $faker->randomDigit,
        'size' => $faker->randomDigit,
        'color' => $faker->hexcolor,
        'price' => $faker->randomNumber(2),
        'stock' => $faker->randomNumber(2),
        'status' => $faker->randomElement(['pending', 'refused', 'active']),
        'reason' => $faker->paragraph(1),
        'start_at' => $faker->date,
        'end_at' => $faker->date,
        'start_offer_at' => $faker->date,
        'end_offer_at' => $faker->date,
        'other_data' => '',
        'department_id' => Department::pluck('id')->random(),
        'trade_id' => TradeMarks::pluck('id')->random(),
        'manu_id' => Manufacture::pluck('id')->random(),
        'color_id' => Color::pluck('id')->random(),
        'size_id' => Size::pluck('id')->random(),
        'weight_id' => Weight::pluck('id')->random(),
        'currency_id' => Country::pluck('id')->random(),
    ];
});

$factory->define(OtherData::class, function (Faker $faker) {
    return [
        'product_id' => Product::pluck('id')->random(),
        'data_key' => $faker->text,
        'data_value' => $faker->text,
    ];
});

$factory->define(ProductMall::class, function (Faker $faker) {
    return [
        'product_id' => Product::pluck('id')->random(),
        'mall_id' => Mall::pluck('id')->random(),
    ];
});

$factory->define(RelatedProduct::class, function (Faker $faker) {
    return [
        'product_id' => Product::pluck('id')->random(),
        'related_product' => $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8]),
    ];
});
