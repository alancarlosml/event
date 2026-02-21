<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Lote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestEventSeeder extends Seeder
{
    public function run()
    {
        $ownerId = DB::table('owners')->first()?->id;
        if (!$ownerId) {
            $ownerId = DB::table('owners')->insertGetId([
                'name' => 'Organizador Teste',
                'document' => '12345678901',
                'email' => 'teste@teste.com',
                'phone' => '11999999999',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $categoryId = DB::table('categories')->first()?->id;
        if (!$categoryId) {
            $categoryId = DB::table('categories')->insertGetId([
                'name' => 'Teste',
                'description' => 'Categoria Teste',
                'slug' => 'teste',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $areaId = DB::table('areas')->first()?->id;
        if (!$areaId) {
            $areaId = DB::table('areas')->insertGetId([
                'name' => 'Teste',
                'slug' => 'teste',
                'category_id' => $categoryId,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $cityId = DB::table('cities')->first()?->id;
        if (!$cityId) {
            $stateId = DB::table('states')->first()?->id;
            if (!$stateId) {
                $stateId = DB::table('states')->insertGetId([
                    'uf' => 'SP',
                    'name' => 'São Paulo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $cityId = DB::table('cities')->insertGetId([
                'name' => 'São Paulo',
                'state_id' => $stateId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $placeId = DB::table('places')->first()?->id;
        if (!$placeId) {
            $placeId = DB::table('places')->insertGetId([
                'name' => 'Local Teste',
                'address' => 'Rua Teste',
                'number' => 100,
                'district' => 'Centro',
                'zip' => '01000-000',
                'city_id' => $cityId,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $event = Event::create([
            'name' => 'Evento Teste - Compra',
            'subtitle' => 'Evento para testar o fluxo de compra',
            'slug' => 'evento-teste-compra-' . Str::random(5),
            'description' => 'Evento de teste criado para validar o fluxo de compra de ingressos.',
            'banner' => 'banner-teste.jpg',
            'banner_option' => 1,
            'config_tax' => 0.07,
            'max_tickets' => 100,
            'theme' => 'default',
            'contact' => 'teste@teste.com',
            'status' => 1,
            'owner_id' => $ownerId,
            'place_id' => $placeId,
            'area_id' => $areaId,
            'paid' => 1,
        ]);

        $eventId = $event->id;

        DB::table('event_dates')->insert([
            'event_id' => $eventId,
            'date' => now()->addDays(30)->format('Y-m-d'),
            'time_begin' => '19:00:00',
            'time_end' => '22:00:00',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $loteData = [
            'name' => 'Ingresso Teste',
            'description' => 'Lote para teste de compra',
            'value' => 50.00,
            'tax' => 3.50,
            'final_value' => 53.50,
            'type' => 1,
            'quantity' => 100,
            'visibility' => 1,
            'tax_parcelamento' => 0,
            'tax_service' => 0,
            'form_pagamento' => 'credit_card,pix,boleto',
            'limit_min' => 1,
            'limit_max' => 5,
            'datetime_begin' => now()->subDays(1)->format('Y-m-d H:i:s'),
            'datetime_end' => now()->addDays(29)->format('Y-m-d H:i:s'),
            'order' => 1,
            'event_id' => $eventId,
            'hash' => md5('teste' . time() . uniqid() . '7bc05eb02415fe73101eeea0180e258d45e8ba2b'),
        ];

        Lote::create($loteData);

        echo "Evento de teste criado: {$event->slug}\n";
        echo "Lote: Ingresso Teste - R$ 53,50\n";
    }
}
