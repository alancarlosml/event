<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['description' => 'Conferências', 'slug' => 'conferencias', 'status' => 1],
            ['description' => 'Workshops', 'slug' => 'workshops', 'status' => 1],
            ['description' => 'Seminários', 'slug' => 'seminarios', 'status' => 1],
            ['description' => 'Festivais', 'slug' => 'festivais', 'status' => 1],
            ['description' => 'Shows', 'slug' => 'shows', 'status' => 1],
            ['description' => 'Teatro', 'slug' => 'teatro', 'status' => 1],
            ['description' => 'Esportes', 'slug' => 'esportes', 'status' => 1],
            ['description' => 'Gastronomia', 'slug' => 'gastronomia', 'status' => 1],
            ['description' => 'Tecnologia', 'slug' => 'tecnologia', 'status' => 1],
            ['description' => 'Negócios', 'slug' => 'negocios', 'status' => 1],
            ['description' => 'Educação', 'slug' => 'educacao', 'status' => 1],
            ['description' => 'Saúde e Bem-estar', 'slug' => 'saude-bem-estar', 'status' => 1],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'description' => $category['description'],
                'slug' => $category['slug'],
                'status' => $category['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

