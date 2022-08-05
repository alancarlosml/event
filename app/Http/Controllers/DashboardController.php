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
        
        $ingressos_vendidos = Order::where('participantes.status', '1')
            // ->where(DB::raw('max(MONTH(event_dates.date))'), 'MONTH(now())')
            ->join('participantes_lotes', 'participantes_lotes.id', '=', 'orders.participante_lote_id')
            ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->join('participantes', 'participantes.id', '=', 'participantes_lotes.participante_id')
            ->leftJoin('inscricoes_coupons', 'inscricoes_coupons.participante_lote_id', '=', 'participantes_lotes.id')
            ->leftJoin('coupons', 'inscricoes_coupons.coupon_id', '=', 'coupons.id')
            ->selectRaw(DB::raw('max(MONTH(event_dates.date)) teste'))
            ->selectRaw("count(case when participantes_lotes.status = 1 then 1 end) as confirmado")
            ->selectRaw("sum(case 
                                when 
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then lotes.value - (coupons.discount_value * lotes.value)
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then lotes.value - coupons.discount_value 
                                end
                            ) as total_confirmado"
                        )
            // ->having('teste', '=', 'MONTH(now())')
            // ->groupBy('event_dates.date')
            ->first();

        // dd($ingressos_vendidos);

        return view('dashboard', compact('event_count', 'ingressos_vendidos'));
    }
}
