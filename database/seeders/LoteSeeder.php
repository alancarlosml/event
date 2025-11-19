<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Lote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar eventos
        $techConference = DB::table('events')->where('slug', 'tech-conference-2024')->first();
        $festivalMPB = DB::table('events')->where('slug', 'festival-musica-popular-2024')->first();
        $workshopIA = DB::table('events')->where('slug', 'workshop-ia-2024')->first();
        $conferenciaEmp = DB::table('events')->where('slug', 'conferencia-empreendedorismo-2024')->first();

        $lotes = [
            // Lotes para Tech Conference
            [
                'name' => 'Lote 1 - Promocional',
                'description' => 'Primeiro lote com desconto especial',
                'value' => 299.00,
                'tax' => 20.93,
                'final_value' => 319.93,
                'type' => 1,
                'quantity' => 1000,
                'visibility' => 1,
                'tax_parcelamento' => 0,
                'tax_service' => 0,
                'form_pagamento' => 'credit_card,pix,boleto',
                'limit_min' => 1,
                'limit_max' => 5,
                'datetime_begin' => now()->subDays(10)->format('Y-m-d H:i:s'),
                'datetime_end' => now()->addDays(15)->format('Y-m-d H:i:s'),
                'order' => 1,
                'event_id' => $techConference->id,
            ],
            [
                'name' => 'Lote 2 - Regular',
                'description' => 'Segundo lote com preço regular',
                'value' => 399.00,
                'tax' => 27.93,
                'final_value' => 426.93,
                'type' => 1,
                'quantity' => 2000,
                'visibility' => 1,
                'tax_parcelamento' => 0,
                'tax_service' => 0,
                'form_pagamento' => 'credit_card,pix,boleto',
                'limit_min' => 1,
                'limit_max' => 5,
                'datetime_begin' => now()->addDays(16)->format('Y-m-d H:i:s'),
                'datetime_end' => now()->addDays(30)->format('Y-m-d H:i:s'),
                'order' => 2,
                'event_id' => $techConference->id,
            ],
            [
                'name' => 'Lote 3 - Última Chance',
                'description' => 'Último lote disponível',
                'value' => 499.00,
                'tax' => 34.93,
                'final_value' => 533.93,
                'type' => 1,
                'quantity' => 2000,
                'visibility' => 1,
                'tax_parcelamento' => 0,
                'tax_service' => 0,
                'form_pagamento' => 'credit_card,pix,boleto',
                'limit_min' => 1,
                'limit_max' => 5,
                'datetime_begin' => now()->addDays(31)->format('Y-m-d H:i:s'),
                'datetime_end' => now()->addDays(45)->format('Y-m-d H:i:s'),
                'order' => 3,
                'event_id' => $techConference->id,
            ],
            
            // Lotes para Festival MPB
            [
                'name' => 'Pista',
                'description' => 'Ingresso para pista',
                'value' => 150.00,
                'tax' => 15.00,
                'final_value' => 165.00,
                'type' => 1,
                'quantity' => 5000,
                'visibility' => 1,
                'tax_parcelamento' => 0,
                'tax_service' => 0,
                'form_pagamento' => 'credit_card,pix,boleto',
                'limit_min' => 1,
                'limit_max' => 10,
                'datetime_begin' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'datetime_end' => now()->addDays(40)->format('Y-m-d H:i:s'),
                'order' => 1,
                'event_id' => $festivalMPB->id,
            ],
            [
                'name' => 'Camarote',
                'description' => 'Ingresso para camarote VIP',
                'value' => 350.00,
                'tax' => 35.00,
                'final_value' => 385.00,
                'type' => 1,
                'quantity' => 500,
                'visibility' => 1,
                'tax_parcelamento' => 0,
                'tax_service' => 0,
                'form_pagamento' => 'credit_card,pix,boleto',
                'limit_min' => 1,
                'limit_max' => 4,
                'datetime_begin' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'datetime_end' => now()->addDays(40)->format('Y-m-d H:i:s'),
                'order' => 2,
                'event_id' => $festivalMPB->id,
            ],
            
            // Lotes para Workshop IA
            [
                'name' => 'Workshop Completo',
                'description' => 'Acesso completo ao workshop',
                'value' => 199.00,
                'tax' => 9.95,
                'final_value' => 208.95,
                'type' => 1,
                'quantity' => 200,
                'visibility' => 1,
                'tax_parcelamento' => 0,
                'tax_service' => 0,
                'form_pagamento' => 'credit_card,pix',
                'limit_min' => 1,
                'limit_max' => 3,
                'datetime_begin' => now()->subDays(3)->format('Y-m-d H:i:s'),
                'datetime_end' => now()->addDays(17)->format('Y-m-d H:i:s'),
                'order' => 1,
                'event_id' => $workshopIA->id,
            ],
            
            // Lotes para Conferência Empreendedorismo
            [
                'name' => 'Early Bird',
                'description' => 'Desconto para compra antecipada',
                'value' => 249.00,
                'tax' => 17.43,
                'final_value' => 266.43,
                'type' => 1,
                'quantity' => 500,
                'visibility' => 1,
                'tax_parcelamento' => 0,
                'tax_service' => 0,
                'form_pagamento' => 'credit_card,pix,boleto',
                'limit_min' => 1,
                'limit_max' => 5,
                'datetime_begin' => now()->subDays(7)->format('Y-m-d H:i:s'),
                'datetime_end' => now()->addDays(20)->format('Y-m-d H:i:s'),
                'order' => 1,
                'event_id' => $conferenciaEmp->id,
            ],
            [
                'name' => 'Regular',
                'description' => 'Preço regular',
                'value' => 349.00,
                'tax' => 24.43,
                'final_value' => 373.43,
                'type' => 1,
                'quantity' => 1500,
                'visibility' => 1,
                'tax_parcelamento' => 0,
                'tax_service' => 0,
                'form_pagamento' => 'credit_card,pix,boleto',
                'limit_min' => 1,
                'limit_max' => 5,
                'datetime_begin' => now()->addDays(21)->format('Y-m-d H:i:s'),
                'datetime_end' => now()->addDays(50)->format('Y-m-d H:i:s'),
                'order' => 2,
                'event_id' => $conferenciaEmp->id,
            ],
        ];

        foreach ($lotes as $loteData) {
            $loteData['hash'] = md5($loteData['name'] . $loteData['description'] . $loteData['event_id'] . time() . uniqid() . md5('7bc05eb02415fe73101eeea0180e258d45e8ba2b'));
            
            Lote::create($loteData);
        }
    }
}

