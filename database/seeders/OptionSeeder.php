<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('options')->insert([
            'option' => 'Texto (Até 200 caracteres)',
            'value' => '',
            'tag' => 'text',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Seleção',
            'value' => '',
            'tag' => 'select',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Marcação',
            'value' => '',
            'tag' => 'radio',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Múltipla escolha',
            'value' => '',
            'tag' => 'checkbox',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'CPF',
            'value' => '',
            'tag' => 'cpf',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'CNPJ',
            'value' => '',
            'tag' => 'cnpj',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Data',
            'value' => '',
            'tag' => 'date',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Telefone',
            'value' => '',
            'tag' => 'phone',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Número inteiro',
            'value' => '',
            'tag' => 'number',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Número decimal',
            'value' => '',
            'tag' => 'decimal',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Arquivo',
            'value' => '',
            'tag' => 'file',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Textarea (+ de 200 caracteres)',
            'value' => '',
            'tag' => 'textarea',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'E-mail',
            'value' => '',
            'tag' => 'email',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('options')->insert([
            'option' => 'Estados (BRA)',
            'value' => 'AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO',
            'tag' => 'select',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
