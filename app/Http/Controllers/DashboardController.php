<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Event;
use App\Models\Order;

class DashboardController extends Controller
{
    public function dashboard(){
        
        $event_count = Event::orderBy('event_dates.date', 'asc')
            ->join('event_dates', 'events.id', 'event_dates.event_id')
            ->where('event_dates.date', '>', now())
            ->where('events.status', '1')
            ->select('events.id',
                DB::raw("MAX(event_dates.date) AS total_ativo"))
            ->groupBy('events.id')
            ->get();

        $ingressos_vendidos = $resumo = DB::table('lotes')
        ->join('order_items', 'lotes.id', '=', 'order_items.lote_id')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->join('events', 'events.id', '=', 'lotes.event_id')
        ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
        ->selectRaw("count(case when orders.status = 1 then 1 end) as confirmado")
        ->selectRaw("sum(case 
                                when 
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value)
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value 
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type is null and coupons.code is null then order_items.value
                                end
                            ) as total_confirmado"
                        )
        ->selectRaw("sum(case 
                                when 
                                    lotes.type = 0 and orders.status = 2 and coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value)
                                when    
                                    lotes.type = 0 and orders.status = 2 and coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value 
                                when    
                                    lotes.type = 0 and orders.status = 2 and coupons.discount_type is null and coupons.code is null then order_items.value
                                end
                            ) as total_pendente"
                        )
        ->first();
        
        // $ingressos_vendidos = Order::where('participantes.status', '1')
        //     ->join('order_items', 'order_items.order_id', '=', 'orders.id')
        //     ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
        //     ->join('events', 'events.id', '=', 'lotes.event_id')
        //     ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
        //     ->join('participantes', 'participantes.id', '=', 'orders.participante_id')
        //     ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
        //     ->selectRaw(DB::raw('max(MONTH(event_dates.date)) teste'))
        //     ->selectRaw("count(case when orders.status = 1 then 1 end) as confirmado")
        //     ->selectRaw("sum(case 
        //                         when 
        //                             lotes.type = 0 and orders.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then lotes.value - (coupons.discount_value * lotes.value)
        //                         when    
        //                             lotes.type = 0 and orders.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then lotes.value - coupons.discount_value 
        //                         end
        //                     ) as total_confirmado"
        //                 )
        //     ->first();

        // dd($ingressos_vendidos);

        return view('dashboard', compact('event_count', 'ingressos_vendidos'));
    }
}
