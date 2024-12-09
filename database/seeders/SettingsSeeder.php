<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=array(
            'description'=>"Franciscan not only gives you a chance to buy merchandise online, it also gives you a chance to book a facility.",
            'short_des'=>"Everything Franciscan.",
            'photo'=>"/storage/photos/1/Category/mini-banner1.jpg",
            'logo'=>'/storage/photos/3/axumlogo1.png',
            'address'=>"Ngong Road, Nairobi",
            'email'=>"support@franciscanagencies.com",
            'phone'=>"+254727991993",
        );
        DB::table('settings')->insert($data);
    }
}
