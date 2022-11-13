<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParticipanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('participantes')->insert([
            'name' => 'Participante 1',
            'email' => 'participante1@gmail.com',
            'password' => Hash::make('password'),
            'cpf' => '02587763520',
            'phone' => '8955554544',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('participantes')->insert([
            'name' => 'Participante 2',
            'email' => 'participante2@gmail.com',
            'password' => Hash::make('password'),
            'cpf' => '02587763520',
            'phone' => '8955554544',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('participantes')->insert([
            'name' => 'Participante 3',
            'email' => 'participante3@gmail.com',
            'password' => Hash::make('password'),
            'cpf' => '02587763520',
            'phone' => '8955554544',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('participantes')->insert([
            'name' => 'Participante 4',
            'email' => 'participante4@gmail.com',
            'password' => Hash::make('password'),
            'cpf' => '02587763520',
            'phone' => '8955554544',
            'status' => 0,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('participantes')->insert([
            'name' => 'Participante 5',
            'email' => 'participante5@gmail.com',
            'password' => Hash::make('password'),
            'cpf' => '02587763520',
            'phone' => '8955554544',
            'status' => 0,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
