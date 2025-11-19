<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar categorias
        $conferencias = DB::table('categories')->where('slug', 'conferencias')->first();
        $workshops = DB::table('categories')->where('slug', 'workshops')->first();
        $tecnologia = DB::table('categories')->where('slug', 'tecnologia')->first();
        $negocios = DB::table('categories')->where('slug', 'negocios')->first();
        $educacao = DB::table('categories')->where('slug', 'educacao')->first();
        $shows = DB::table('categories')->where('slug', 'shows')->first();
        $esportes = DB::table('categories')->where('slug', 'esportes')->first();
        $gastronomia = DB::table('categories')->where('slug', 'gastronomia')->first();

        $areas = [
            // Áreas de Tecnologia
            ['name' => 'Desenvolvimento de Software', 'slug' => 'desenvolvimento-software', 'category_id' => $tecnologia->id, 'status' => '1'],
            ['name' => 'Inteligência Artificial', 'slug' => 'inteligencia-artificial', 'category_id' => $tecnologia->id, 'status' => '1'],
            ['name' => 'Segurança da Informação', 'slug' => 'seguranca-informacao', 'category_id' => $tecnologia->id, 'status' => '1'],
            ['name' => 'Cloud Computing', 'slug' => 'cloud-computing', 'category_id' => $tecnologia->id, 'status' => '1'],
            
            // Áreas de Negócios
            ['name' => 'Empreendedorismo', 'slug' => 'empreendedorismo', 'category_id' => $negocios->id, 'status' => '1'],
            ['name' => 'Marketing Digital', 'slug' => 'marketing-digital', 'category_id' => $negocios->id, 'status' => '1'],
            ['name' => 'Gestão de Projetos', 'slug' => 'gestao-projetos', 'category_id' => $negocios->id, 'status' => '1'],
            
            // Áreas de Educação
            ['name' => 'Ensino Superior', 'slug' => 'ensino-superior', 'category_id' => $educacao->id, 'status' => '1'],
            ['name' => 'Formação Profissional', 'slug' => 'formacao-profissional', 'category_id' => $educacao->id, 'status' => '1'],
            
            // Áreas de Shows
            ['name' => 'Música Popular', 'slug' => 'musica-popular', 'category_id' => $shows->id, 'status' => '1'],
            ['name' => 'Rock', 'slug' => 'rock', 'category_id' => $shows->id, 'status' => '1'],
            ['name' => 'Sertanejo', 'slug' => 'sertanejo', 'category_id' => $shows->id, 'status' => '1'],
            ['name' => 'Eletrônica', 'slug' => 'eletronica', 'category_id' => $shows->id, 'status' => '1'],
            
            // Áreas de Esportes
            ['name' => 'Futebol', 'slug' => 'futebol', 'category_id' => $esportes->id, 'status' => '1'],
            ['name' => 'Corrida', 'slug' => 'corrida', 'category_id' => $esportes->id, 'status' => '1'],
            ['name' => 'Ciclismo', 'slug' => 'ciclismo', 'category_id' => $esportes->id, 'status' => '1'],
            
            // Áreas de Gastronomia
            ['name' => 'Culinária Brasileira', 'slug' => 'culinaria-brasileira', 'category_id' => $gastronomia->id, 'status' => '1'],
            ['name' => 'Culinária Internacional', 'slug' => 'culinaria-internacional', 'category_id' => $gastronomia->id, 'status' => '1'],
            
            // Áreas de Conferências
            ['name' => 'Conferências Científicas', 'slug' => 'conferencias-cientificas', 'category_id' => $conferencias->id, 'status' => '1'],
            ['name' => 'Conferências de Negócios', 'slug' => 'conferencias-negocios', 'category_id' => $conferencias->id, 'status' => '1'],
            
            // Áreas de Workshops
            ['name' => 'Workshops Técnicos', 'slug' => 'workshops-tecnicos', 'category_id' => $workshops->id, 'status' => '1'],
            ['name' => 'Workshops Criativos', 'slug' => 'workshops-criativos', 'category_id' => $workshops->id, 'status' => '1'],
        ];

        foreach ($areas as $area) {
            DB::table('areas')->insert([
                'name' => $area['name'],
                'slug' => $area['slug'],
                'category_id' => $area['category_id'],
                'status' => $area['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

