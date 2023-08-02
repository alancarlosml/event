<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Event;
use App\Models\Order;

class DashboardController extends Controller
{
    public function dashboard()
    {

        $event_count = Event::orderBy('event_dates.date', 'asc')
            ->leftJoin('lotes', 'lotes.event_id', '=', 'events.id')
            ->leftJoin('places', 'places.id', '=', 'events.place_id')
            ->leftJoin('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->leftJoin(DB::raw("(SELECT participantes.name,
                                        participantes.email, 
                                        participantes_events.event_id
                                from participantes
                                inner join participantes_events on participantes.id = participantes_events.participante_id
                                where participantes_events.role = 'admin' and participantes_events.status = 1
                                ) as x"), function ($join) {
                    $join->on('x.event_id', '=', 'events.id');
                })
            ->where('events.status', '1')
            ->select(
                'events.*',
                'places.name as place_name',
                'lotes.name as lote_name',
                'event_dates.date as event_date',
                'x.name as participante_name',
                DB::raw('MIN(event_dates.date) as date_event_min'),
                DB::raw('MAX(event_dates.date) as date_event_max'),
                'x.name as admin_name',
                'x.email as admin_email'
            )
            ->groupBy('events.id')
            ->get();

        $ingressos_confirmados = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->where('orders.status', 1)
            ->select('orders.id as order_id', 'orders.status as situacao', 'events.name as event_name', 'orders.hash as order_hash', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.name as participante_name', 'participantes.email as participante_email', 'participantes.cpf as participante_cpf')
            ->orderBy('orders.created_at', 'desc')
            ->groupBy('orders.id')
            ->get();

        $ingressos_pendentes = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->where('orders.status', 2)
            ->select('orders.id as order_id', 'orders.status as situacao', 'events.name as event_name', 'orders.hash as order_hash', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.name as participante_name', 'participantes.email as participante_email', 'participantes.cpf as participante_cpf')
            ->orderBy('orders.created_at', 'desc')
            ->groupBy('orders.id')
            ->get();

        $ingressos_cancelados = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->where('orders.status', 3)
            ->select('orders.id as order_id', 'orders.status as situacao', 'events.name as event_name', 'orders.hash as order_hash', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.name as participante_name', 'participantes.email as participante_email', 'participantes.cpf as participante_cpf')
            ->orderBy('orders.created_at', 'desc')
            ->groupBy('orders.id')
            ->get();

        $total_confirmado = DB::table('lotes')
            ->join('order_items', 'lotes.id', '=', 'order_items.lote_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->where('lotes.type', 0)
            ->where('orders.status', 1)
            ->selectRaw(
                "sum(case 
                    when coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value)
                    when coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value 
                    when coupons.discount_type is null and coupons.code is null then order_items.value
                    end
                ) as total_confirmado"
            )
            ->first();

        $total_pendente = DB::table('lotes')
            ->join('order_items', 'lotes.id', '=', 'order_items.lote_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->where('lotes.type', 0)
            ->where('orders.status', 2)
            ->selectRaw(
                "sum(case 
                    when coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value)
                    when coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value 
                    when coupons.discount_type is null and coupons.code is null then order_items.value
                    end
                ) as total_pendente"
            )
            ->first();

        return view('dashboard', compact('event_count', 'ingressos_confirmados', 'ingressos_cancelados', 'ingressos_pendentes', 'total_confirmado', 'total_pendente'));
    }
}
