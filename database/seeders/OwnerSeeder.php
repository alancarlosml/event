<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $owners = [
            [
                'name' => 'Eventos Tech Brasil',
                'description' => 'Organizadora especializada em eventos de tecnologia e inovação.',
                'icon' => 'tech-icon.png',
                'status' => 1,
            ],
            [
                'name' => 'ShowTime Produções',
                'description' => 'Produtora de shows e eventos musicais de grande porte.',
                'icon' => 'showtime-icon.png',
                'status' => 1,
            ],
            [
                'name' => 'Academia de Eventos',
                'description' => 'Organizadora de conferências, workshops e eventos educacionais.',
                'icon' => 'academia-icon.png',
                'status' => 1,
            ],
            [
                'name' => 'Sports Eventos',
                'description' => 'Especializada em eventos esportivos e maratonas.',
                'icon' => 'sports-icon.png',
                'status' => 1,
            ],
            [
                'name' => 'Gourmet Events',
                'description' => 'Organizadora de eventos gastronômicos e festivais de comida.',
                'icon' => 'gourmet-icon.png',
                'status' => 1,
            ],
            [
                'name' => 'Business Connect',
                'description' => 'Conectando profissionais através de eventos de negócios.',
                'icon' => 'business-icon.png',
                'status' => 1,
            ],
        ];

        foreach ($owners as $owner) {
            DB::table('owners')->insert([
                'name' => $owner['name'],
                'description' => $owner['description'],
                'icon' => $owner['icon'],
                'status' => $owner['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

