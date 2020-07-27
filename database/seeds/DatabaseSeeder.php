<?php

use App\Admin;
use App\User;
use App\Model\City;
use App\Model\Color;
use App\Model\Country;
use App\Model\Department;
use App\Model\File;
use App\Model\Mall;
use App\Model\Manufacture;
use App\Model\Product;
use App\Model\ProductMall;
use App\Model\RelatedProduct;
use App\Model\Setting;
use App\Model\Shipping;
use App\Model\Size;
use App\Model\State;
use App\Model\TradeMarks;
use App\Model\Weight;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        // $this->call(UserSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Admin::truncate();
        User::truncate();
        Setting::truncate();
        Country::truncate();
        City::truncate();
        State::truncate();
        Department::truncate();
        TradeMarks::truncate();
        Manufacture::truncate();
        Shipping::truncate();
        Mall::truncate();
        Color::truncate();
        Size::truncate();
        Weight::truncate();
        Product::truncate();
        File::truncate();
        ProductMall::truncate();

        factory(Admin::class, 10)->create();
        factory(User::class, 500)->create();
        factory(Setting::class, 1)->create();
        factory(Country::class, 50)->create();
        factory(City::class, 100)->create();
        factory(State::class, 200)->create();
        factory(Department::class, 25)->create();
        factory(TradeMarks::class, 25)->create();
        factory(Manufacture::class, 50)->create();
        factory(Shipping::class, 50)->create();
        factory(Mall::class, 50)->create();
        factory(Color::class, 20)->create();
        factory(Size::class, 100)->create();
        factory(Weight::class, 20)->create();
        factory(Product::class, 300)->create();
        factory(File::class, 20)->create();
        factory(ProductMall::class, 30)->create();


        /* foreach ($users as $user) {
            $courses_ids = [];
            $courses_ids[] = Course::all()->where('status', 1)->random()->id;
            $courses_ids[] = Course::all()->where('status', 1)->random()->id;
            $courses_ids[] = Course::all()->where('status', 1)->random()->id;
            $user->courses()->sync($courses_ids);
        } */
    }
}
