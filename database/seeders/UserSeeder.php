<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'status' => 1
        ]);

        DB::table('users')->insert([
            'name' => 'Alan',
            'email' => 'alan@gmail.com',
            'password' => Hash::make('12345678'),
            'status' => 1
        ]);

        DB::table('users')->insert([
            'name' => 'Visitante',
            'email' => 'visitante@gmail.com',
            'password' => Hash::make('12345678'),
            'status' => 1
        ]);

        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'status' => 0
        ]);
    }
}
