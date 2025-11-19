<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Primeiro, precisamos pegar os IDs dos estados
        $sp = DB::table('states')->where('uf', 'SP')->first();
        $rj = DB::table('states')->where('uf', 'RJ')->first();
        $mg = DB::table('states')->where('uf', 'MG')->first();
        $rs = DB::table('states')->where('uf', 'RS')->first();
        $pr = DB::table('states')->where('uf', 'PR')->first();
        $sc = DB::table('states')->where('uf', 'SC')->first();
        $ba = DB::table('states')->where('uf', 'BA')->first();
        $go = DB::table('states')->where('uf', 'GO')->first();
        $pe = DB::table('states')->where('uf', 'PE')->first();
        $ce = DB::table('states')->where('uf', 'CE')->first();

        $cities = [
            // São Paulo
            ['name' => 'São Paulo', 'uf' => $sp->id, 'ibge' => 3550308],
            ['name' => 'Campinas', 'uf' => $sp->id, 'ibge' => 3509502],
            ['name' => 'Guarulhos', 'uf' => $sp->id, 'ibge' => 3518800],
            ['name' => 'São Bernardo do Campo', 'uf' => $sp->id, 'ibge' => 3548708],
            ['name' => 'Santo André', 'uf' => $sp->id, 'ibge' => 3547809],
            ['name' => 'Osasco', 'uf' => $sp->id, 'ibge' => 3534401],
            ['name' => 'Ribeirão Preto', 'uf' => $sp->id, 'ibge' => 3543402],
            ['name' => 'Sorocaba', 'uf' => $sp->id, 'ibge' => 3552205],
            
            // Rio de Janeiro
            ['name' => 'Rio de Janeiro', 'uf' => $rj->id, 'ibge' => 3304557],
            ['name' => 'Niterói', 'uf' => $rj->id, 'ibge' => 3303302],
            ['name' => 'Duque de Caxias', 'uf' => $rj->id, 'ibge' => 3301702],
            ['name' => 'Nova Iguaçu', 'uf' => $rj->id, 'ibge' => 3303500],
            
            // Minas Gerais
            ['name' => 'Belo Horizonte', 'uf' => $mg->id, 'ibge' => 3106200],
            ['name' => 'Uberlândia', 'uf' => $mg->id, 'ibge' => 3170206],
            ['name' => 'Contagem', 'uf' => $mg->id, 'ibge' => 3118601],
            
            // Rio Grande do Sul
            ['name' => 'Porto Alegre', 'uf' => $rs->id, 'ibge' => 4314902],
            ['name' => 'Caxias do Sul', 'uf' => $rs->id, 'ibge' => 4305108],
            
            // Paraná
            ['name' => 'Curitiba', 'uf' => $pr->id, 'ibge' => 4106902],
            ['name' => 'Londrina', 'uf' => $pr->id, 'ibge' => 4113700],
            ['name' => 'Maringá', 'uf' => $pr->id, 'ibge' => 4115200],
            
            // Santa Catarina
            ['name' => 'Florianópolis', 'uf' => $sc->id, 'ibge' => 4205407],
            ['name' => 'Joinville', 'uf' => $sc->id, 'ibge' => 4209102],
            ['name' => 'Blumenau', 'uf' => $sc->id, 'ibge' => 4202404],
            
            // Bahia
            ['name' => 'Salvador', 'uf' => $ba->id, 'ibge' => 2927408],
            ['name' => 'Feira de Santana', 'uf' => $ba->id, 'ibge' => 2910800],
            
            // Goiás
            ['name' => 'Goiânia', 'uf' => $go->id, 'ibge' => 5208707],
            ['name' => 'Aparecida de Goiânia', 'uf' => $go->id, 'ibge' => 5201405],
            
            // Pernambuco
            ['name' => 'Recife', 'uf' => $pe->id, 'ibge' => 2611606],
            ['name' => 'Jaboatão dos Guararapes', 'uf' => $pe->id, 'ibge' => 2607901],
            
            // Ceará
            ['name' => 'Fortaleza', 'uf' => $ce->id, 'ibge' => 2304400],
            ['name' => 'Caucaia', 'uf' => $ce->id, 'ibge' => 2303709],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'name' => $city['name'],
                'uf' => $city['uf'],
                'ibge' => $city['ibge'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

