<?php

use Database\Seeders\SettingTableSeeder;
use Illuminate\Database\seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(CouponSeeder::class);


    }
}
