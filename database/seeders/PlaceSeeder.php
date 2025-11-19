<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar cidades
        $sp = DB::table('cities')->where('name', 'São Paulo')->first();
        $rj = DB::table('cities')->where('name', 'Rio de Janeiro')->first();
        $bh = DB::table('cities')->where('name', 'Belo Horizonte')->first();
        $curitiba = DB::table('cities')->where('name', 'Curitiba')->first();
        $portoAlegre = DB::table('cities')->where('name', 'Porto Alegre')->first();
        $campinas = DB::table('cities')->where('name', 'Campinas')->first();

        $places = [
            // São Paulo
            [
                'name' => 'Centro de Convenções Anhembi',
                'address' => 'Av. Olavo Fontoura',
                'number' => 1209,
                'district' => 'Santana',
                'zip' => '02012-021',
                'complement' => 'Pavilhão de Exposições',
                'city_id' => $sp->id,
                'status' => 1,
            ],
            [
                'name' => 'Allianz Parque',
                'address' => 'Av. Francisco Matarazzo',
                'number' => 1705,
                'district' => 'Água Branca',
                'zip' => '05001-200',
                'complement' => '',
                'city_id' => $sp->id,
                'status' => 1,
            ],
            [
                'name' => 'Centro de Eventos Pro Magno',
                'address' => 'R. Samaritá',
                'number' => 230,
                'district' => 'Casa Verde',
                'zip' => '02520-000',
                'complement' => '',
                'city_id' => $sp->id,
                'status' => 1,
            ],
            
            // Rio de Janeiro
            [
                'name' => 'Riocentro',
                'address' => 'Av. Salvador Allende',
                'number' => 6555,
                'district' => 'Barra da Tijuca',
                'zip' => '22780-160',
                'complement' => '',
                'city_id' => $rj->id,
                'status' => 1,
            ],
            [
                'name' => 'Maracanã',
                'address' => 'Av. Pres. Castelo Branco',
                'number' => 126,
                'district' => 'Maracanã',
                'zip' => '20271-130',
                'complement' => '',
                'city_id' => $rj->id,
                'status' => 1,
            ],
            
            // Belo Horizonte
            [
                'name' => 'Expominas',
                'address' => 'Av. Amazonas',
                'number' => 6200,
                'district' => 'Gameleira',
                'zip' => '30510-000',
                'complement' => '',
                'city_id' => $bh->id,
                'status' => 1,
            ],
            
            // Curitiba
            [
                'name' => 'Expo Unimed Curitiba',
                'address' => 'R. Prof. Pedro Viriato Parigot de Souza',
                'number' => 5300,
                'district' => 'Campo Comprido',
                'zip' => '81280-330',
                'complement' => '',
                'city_id' => $curitiba->id,
                'status' => 1,
            ],
            
            // Porto Alegre
            [
                'name' => 'Centro de Eventos FIERGS',
                'address' => 'Av. Assis Brasil',
                'number' => 8787,
                'district' => 'Sarandi',
                'zip' => '91140-001',
                'complement' => '',
                'city_id' => $portoAlegre->id,
                'status' => 1,
            ],
            
            // Campinas
            [
                'name' => 'Expo D. Pedro',
                'address' => 'Av. Guilherme Campos',
                'number' => 500,
                'district' => 'Barão Geraldo',
                'zip' => '13084-000',
                'complement' => '',
                'city_id' => $campinas->id,
                'status' => 1,
            ],
        ];

        foreach ($places as $place) {
            DB::table('places')->insert([
                'name' => $place['name'],
                'address' => $place['address'],
                'number' => $place['number'],
                'district' => $place['district'],
                'zip' => $place['zip'],
                'complement' => $place['complement'],
                'city_id' => $place['city_id'],
                'status' => $place['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

