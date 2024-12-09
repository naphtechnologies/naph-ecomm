<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserCredSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=array(
            array(
                'name'=>'Admin',
                'email'=>'admin@gmail.com',
                'password'=>Hash::make('fr@nc1sC4N'),
                'role'=>'admin',
                'status'=>'active'
            ),
            array(
                'name'=>'User',
                'email'=>'user@gmail.com',
                'password'=>Hash::make('fr@nc1sC4N'),
                'role'=>'user',
                'status'=>'active'
            ),
        );

        DB::table('users')->insert($data);
    }
}
