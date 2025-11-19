<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            ['name' => 'Acre', 'uf' => 'AC', 'ibge' => 12, 'ddd' => '68'],
            ['name' => 'Alagoas', 'uf' => 'AL', 'ibge' => 27, 'ddd' => '82'],
            ['name' => 'Amapá', 'uf' => 'AP', 'ibge' => 16, 'ddd' => '96'],
            ['name' => 'Amazonas', 'uf' => 'AM', 'ibge' => 13, 'ddd' => '92,97'],
            ['name' => 'Bahia', 'uf' => 'BA', 'ibge' => 29, 'ddd' => '71,73,74,75,77'],
            ['name' => 'Ceará', 'uf' => 'CE', 'ibge' => 23, 'ddd' => '85,88'],
            ['name' => 'Distrito Federal', 'uf' => 'DF', 'ibge' => 53, 'ddd' => '61'],
            ['name' => 'Espírito Santo', 'uf' => 'ES', 'ibge' => 32, 'ddd' => '27,28'],
            ['name' => 'Goiás', 'uf' => 'GO', 'ibge' => 52, 'ddd' => '62,64'],
            ['name' => 'Maranhão', 'uf' => 'MA', 'ibge' => 21, 'ddd' => '98,99'],
            ['name' => 'Mato Grosso', 'uf' => 'MT', 'ibge' => 51, 'ddd' => '65,66'],
            ['name' => 'Mato Grosso do Sul', 'uf' => 'MS', 'ibge' => 50, 'ddd' => '67'],
            ['name' => 'Minas Gerais', 'uf' => 'MG', 'ibge' => 31, 'ddd' => '31,32,33,34,35,37,38'],
            ['name' => 'Pará', 'uf' => 'PA', 'ibge' => 15, 'ddd' => '91,93,94'],
            ['name' => 'Paraíba', 'uf' => 'PB', 'ibge' => 25, 'ddd' => '83'],
            ['name' => 'Paraná', 'uf' => 'PR', 'ibge' => 41, 'ddd' => '41,42,43,44,45,46'],
            ['name' => 'Pernambuco', 'uf' => 'PE', 'ibge' => 26, 'ddd' => '81,87'],
            ['name' => 'Piauí', 'uf' => 'PI', 'ibge' => 22, 'ddd' => '86,89'],
            ['name' => 'Rio de Janeiro', 'uf' => 'RJ', 'ibge' => 33, 'ddd' => '21,22,24'],
            ['name' => 'Rio Grande do Norte', 'uf' => 'RN', 'ibge' => 24, 'ddd' => '84'],
            ['name' => 'Rio Grande do Sul', 'uf' => 'RS', 'ibge' => 43, 'ddd' => '51,53,54,55'],
            ['name' => 'Rondônia', 'uf' => 'RO', 'ibge' => 11, 'ddd' => '69'],
            ['name' => 'Roraima', 'uf' => 'RR', 'ibge' => 14, 'ddd' => '95'],
            ['name' => 'Santa Catarina', 'uf' => 'SC', 'ibge' => 42, 'ddd' => '47,48,49'],
            ['name' => 'São Paulo', 'uf' => 'SP', 'ibge' => 35, 'ddd' => '11,12,13,14,15,16,17,18,19'],
            ['name' => 'Sergipe', 'uf' => 'SE', 'ibge' => 28, 'ddd' => '79'],
            ['name' => 'Tocantins', 'uf' => 'TO', 'ibge' => 17, 'ddd' => '63'],
        ];

        foreach ($states as $state) {
            DB::table('states')->insert([
                'name' => $state['name'],
                'uf' => $state['uf'],
                'ibge' => $state['ibge'],
                'ddd' => $state['ddd'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

