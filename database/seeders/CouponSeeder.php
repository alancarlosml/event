<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
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

        $coupons = [
            [
                'code' => 'TECH2024',
                'discount_type' => 1, // 1 = porcentagem, 2 = valor fixo
                'discount_value' => 10.00,
                'limit_buy' => 100,
                'limit_tickets' => 2,
                'status' => 1,
                'event_id' => $techConference->id,
            ],
            [
                'code' => 'EARLYBIRD',
                'discount_type' => 1,
                'discount_value' => 15.00,
                'limit_buy' => 50,
                'limit_tickets' => 1,
                'status' => 1,
                'event_id' => $techConference->id,
            ],
            [
                'code' => 'MPB2024',
                'discount_type' => 2, // Valor fixo
                'discount_value' => 20.00,
                'limit_buy' => 200,
                'limit_tickets' => 4,
                'status' => 1,
                'event_id' => $festivalMPB->id,
            ],
            [
                'code' => 'IASTUDENT',
                'discount_type' => 1,
                'discount_value' => 25.00,
                'limit_buy' => 30,
                'limit_tickets' => 1,
                'status' => 1,
                'event_id' => $workshopIA->id,
            ],
            [
                'code' => 'DESCONTO50',
                'discount_type' => 1,
                'discount_value' => 50.00,
                'limit_buy' => 10,
                'limit_tickets' => 1,
                'status' => 0, // Inativo
                'event_id' => $techConference->id,
            ],
        ];

        foreach ($coupons as $couponData) {
            $couponData['hash'] = md5($couponData['code'] . $couponData['event_id'] . time() . uniqid() . md5('7bc05eb02415fe73101eeea0180e258d45e8ba2b'));
            
            $coupon = Coupon::create($couponData);
            
            // Associar cupons a lotes específicos (opcional)
            if ($coupon->code === 'TECH2024' || $coupon->code === 'EARLYBIRD') {
                $lotes = DB::table('lotes')
                    ->where('event_id', $techConference->id)
                    ->get();
                
                foreach ($lotes as $lote) {
                    DB::table('coupons_lotes')->insert([
                        'coupon_id' => $coupon->id,
                        'lote_id' => $lote->id,
                    ]);
                }
            }
        }
    }
}

