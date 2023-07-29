<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Lote;
use App\Models\Participante;
use App\Models\Place;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Owner;
use App\Models\State;

class EventController extends Controller
{
    public function index()
    {

        $events = DB::table('events')
            ->leftJoin('places', 'places.id', '=', 'events.place_id')
            ->leftJoin('participantes_events', 'participantes_events.event_id', '=', 'events.id')
            ->leftJoin('participantes', 'participantes.id', '=', 'participantes_events.participante_id')
            ->leftJoin('lotes', 'events.id', '=', 'lotes.event_id')
            // ->join('users', 'users.id', '=', 'events.owner_id')
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
            ->select(
                'events.*',
                'places.name as place_name',
                'participantes_events.role',
                'participantes.name as participante_name',
                'event_dates.date as event_date',
                'lotes.name as lote_name',
                DB::raw('MIN(event_dates.date) as date_event_min'),
                DB::raw('MAX(event_dates.date) as date_event_max'),
                'x.name as admin_name',
                'x.email as admin_email'
            )
            // ->where('participantes_events.role', 'admin')
            // ->select('events.*', 'places.name as place_name', 'users.name as owner_name', DB::raw('MIN(event_dates.date) as date_event_min'), DB::raw('MAX(event_dates.date) as date_event_max'))
            ->orderBy('events.name')
            ->groupBy('events.id')
            ->get();

        // $events = DB::table('events')
        // ->join('places', 'places.id', '=', 'events.place_id')
        // ->join('owners', 'owners.id', '=', 'events.owner_id')
        // ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
        // ->select('events.*', 'places.name as place_name', 'owners.name as owner_name', DB::raw('MIN(event_dates.date) as date_event_min'), DB::raw('MAX(event_dates.date) as date_event_max'))
        // ->orderBy('events.name')
        // ->groupBy('events.id')
        // ->get();

        // dd($events);

        return view('event.index', compact('events'));
    }

    public function create()
    {

        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();

        return view('event.add', compact('categories', 'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'banner' => 'mimes:jpg,jpeg,bmp,png|max:2048',
            'name' => 'required',
            'slug' => 'required|unique:events',
            'description' => 'required',
            'category' => 'required',
            'area_id' => 'required',
            'total' => 'required',
            'place_name' => 'required',
            'address' => 'required',
            'number' => 'required',
            'district' => 'required',
            'zip' => 'required',
            'state' => 'required',
            'city_id' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();

        Event::create($input);

        return redirect()->route('event.index');
    }

    public function edit($id)
    {

        // $event = Event::find($id);
        $event = DB::table('events')
            ->join('places', 'places.id', '=', 'events.place_id')
            ->join('cities', 'cities.id', '=', 'places.city_id')
            ->join('states', 'states.id', '=', 'cities.uf')
            ->join('participantes_events', 'participantes_events.event_id', '=', 'events.id')
            ->join('participantes', 'participantes.id', '=', 'participantes_events.participante_id')
            ->join('areas', 'areas.id', '=', 'events.area_id')
            ->join('categories', 'categories.id', '=', 'areas.category_id')
            ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->where('events.id', $id)
            ->where('participantes_events.role', 'admin')
            ->select(
                'events.*',
                'categories.id as category_id',
                'places.name as place_name',
                'places.address as place_address',
                'places.number as place_number',
                'places.district as place_district',
                'places.complement as place_complement',
                'places.zip as place_zip',
                'cities.id as city_id',
                'states.uf as city_uf',
                'participantes.email as participante_email'
            )
            ->first();

        // dd($event);

        $dates = DB::table('event_dates')
            ->join('events', 'events.id', '=', 'event_dates.event_id')
            ->where('events.id', $id)
            ->selectRaw(
                'event_dates.id, 
                DATE_FORMAT(event_dates.date, "%d/%m/%Y") as date, 
                DATE_FORMAT(event_dates.time_begin, "%H:%i") as time_begin,
                DATE_FORMAT(event_dates.time_end, "%H:%i") as time_end'
            )
            ->orderBy('event_dates.date')
            ->get();

        // dd($dates);

        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();

        return view('event.edit', compact('event', 'dates', 'categories', 'states'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if($event->banner) {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:events,slug,'.$event->id,
                'description' => 'required',
                'category' => 'required',
                'area_id' => 'required',
                'max_tickets' => 'required',
                'place_name' => 'required',
                'date' => 'required',
                'time_begin' => 'required',
                'time_end' => 'required',
                'address' => 'required',
                'number' => 'required',
                'district' => 'required',
                'zip' => 'required',
                'state' => 'required',
                'city_id' => 'required',
            ]);
        } else {
            $request->validate([
                'banner' => 'mimes:jpg,jpeg,bmp,png|max:2048',
                'name' => 'required',
                'slug' => 'required|unique:events,slug,'.$event->id,
                'description' => 'required',
                'category' => 'required',
                'area_id' => 'required',
                'max_tickets' => 'required',
                'place_name' => 'required',
                'date' => 'required',
                'time_begin' => 'required',
                'time_end' => 'required',
                'address' => 'required',
                'number' => 'required',
                'district' => 'required',
                'zip' => 'required',
                'state' => 'required',
                'city_id' => 'required',
            ]);
        }

        $input = $request->all();

        // dd($input);

        $dates = $input['date'];
        $times_begin = $input['time_begin'];
        $times_end = $input['time_end'];

        $event_date = new EventDate();

        // $coupon->lotes()->detach();

        DB::table('event_dates')->where('event_id', $id)->delete();

        $finalArray = [];
        for ($i = 0; $i < count($dates); $i++) {

            $dates[$i] = str_replace('/', '-', $dates[$i]);
            // dd(date('Y-m-d', strtotime($dates[$i])));
            array_push($finalArray, [
                'date' => date('Y-m-d', strtotime($dates[$i])),
                'time_begin' => date('H:i', strtotime($times_begin[$i])),
                'time_end' => date('H:i', strtotime($times_end[$i])),
                'status' => 1,
                'event_id' => $id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        EventDate::insert($finalArray);

        // $participante = Participante::where('email', $input['participante_email'])->first();

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        // $input['owner_id'] = $owner->id;

        $event->fill($input)->save();

        $place = Place::where('name', $request->place_name)->first();

        if($place) {

            $place->address = $request->address;
            $place->number = $request->number;
            $place->district = $request->district;
            $place->zip = $request->zip;
            $place->complement = $request->complement;
            $place->city_id = $request->city_id;
            $place->save();

        } else {

            $place = new Place();

            $place->name = $request->place_name;
            $place->address = $request->address;
            $place->number = $request->number;
            $place->district = $request->district;
            $place->zip = $request->zip;
            $place->complement = $request->complement;
            $place->city_id = $request->city_id;
            $place->status = 1;

            $place->save();
            $id_place = $place->id;

            Event::where('id', $event->id)->update(['place_id' => $id_place]);
        }

        if($request->file('banner')) {
            $fileName = time().'_'.$request->file('banner')->getClientOriginalName();
            $filePath = $request->file('banner')->storeAs('events', $fileName, 'public');

            if($event) {
                $event->banner = $filePath;
                $event->save();
            }
        }

        return redirect()->route('event.index');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return redirect()->route('event.index');
    }

    public function show($id)
    {

        $event = Event::find($id);

        return view('event.show', compact('event'));
    }

    public function check_slug(Request $request)
    {
        $slug = Str::slug($request->title, '-');

        $slug_exists = Event::where('slug', $slug)->count();

        return response()->json(['slug' => $slug, 'slug_exists' => $slug_exists]);
    }

    public function create_slug(Request $request)
    {
        $slug = $request->title;

        $slug_exists = Event::where('slug', $slug)->count();

        return response()->json(['slug' => $slug, 'slug_exists' => $slug_exists]);
    }

    public function autocomplete_place(Request $request)
    {
        $data = Place::join('cities', 'cities.id', '=', 'places.city_id')
            ->join('states', 'states.id', '=', 'cities.uf')
            ->where('places.name', 'LIKE', '%'. $request->get('search'). '%')
            ->select('places.name as value', 'places.id', 'places.address', 'places.number', 'places.complement', 'places.district', 'places.zip', 'places.city_id', 'states.uf')
            ->get();

        return response()->json($data);
    }

    public function lotes($id)
    {

        $event = Event::find($id);

        $lotes = Lote::orderBy('order')
            ->where('event_id', $id)
            ->get();

        return view('event.lotes', compact('event', 'lotes', 'id'));
    }

    public function delete_file(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // $request->file->delete(public_path('events'), $event->banner);

        $path = public_path().'/storage/'.$event->banner;

        unlink($path);

        if($event) {
            $event->banner = '';
            $event->save();
        }

        return back()
            ->with('success', 'Arquivo removido com sucesso!');
    }

    public function reports(Request $request, $id)
    {
        $event = Event::where('id', $id)->first();

        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        $resumo = DB::table('lotes')
            ->join('order_items', 'lotes.id', '=', 'order_items.lote_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->where('events.id', $id)
            ->selectRaw('count(*) as total')
            ->selectRaw('count(case when orders.status = 1 then 1 end) as confirmado')
            ->selectRaw('count(case when orders.status = 2 then 1 end) as pendente')
            ->selectRaw('count(case when ((orders.status = 1 or orders.status = 2)) then 1 end) as geral')
                // ->selectRaw("sum(case when orders.status = 1 then 1 * lotes.value end) as total_confirmado")
            ->selectRaw(
                "sum(case 
                                when 
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value)
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value 
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type is null and coupons.code is null then order_items.value
                                end
                            ) as total_confirmado"
            )
                // ->selectRaw("sum(case when orders.status = 2 then 1 * lotes.value end) as total_pendente")
            ->selectRaw(
                "sum(case 
                                when 
                                    lotes.type = 0 and orders.status = 2 and coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value)
                                when    
                                    lotes.type = 0 and orders.status = 2 and coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value 
                                when    
                                    lotes.type = 0 and orders.status = 2 and coupons.discount_type is null and coupons.code is null then order_items.value 
                                end
                            ) as total_pendente"
            )
            ->selectRaw('sum(case when ((orders.status = 1 or orders.status = 2)) then 1 * order_items.value end) as total_geral')
                // ->selectRaw("sum(CASE WHEN orders.status = 1 THEN 1 * order_items.value * $taxa_juros END) as total_taxa")
            ->selectRaw(
                "sum(case 
                                when 
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then (order_items.value - (coupons.discount_value * order_items.value)) * $taxa_juros
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then (order_items.value - coupons.discount_value) * $taxa_juros
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type is null and coupons.code is null then order_items.value * $taxa_juros
                                end
                            ) as total_taxa"
            )
            ->selectRaw(
                "sum(case 
                                when 
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then (order_items.value - (coupons.discount_value * order_items.value)) - ((order_items.value - (coupons.discount_value * order_items.value)) * $taxa_juros)
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then (order_items.value - coupons.discount_value) - ((order_items.value - coupons.discount_value) * $taxa_juros)
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type is null and coupons.code is null then order_items.value - (order_items.value * $taxa_juros)
                                end
                            ) as total_liquido"
            )
                // ->selectRaw("sum((CASE WHEN orders.status = 1 THEN 1 END) * lotes.value) - (sum(CASE WHEN orders.status = 1 THEN 1 * lotes.value * $taxa_juros END)) as total_liquido")
            ->first();

        $lotes = Lote::orderBy('order')
            ->join('order_items', 'lotes.id', '=', 'order_items.lote_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->where('events.id', $id)
            ->select(
                'lotes.id',
                'lotes.name',
                'lotes.quantity',
                DB::raw('COUNT(CASE WHEN orders.status = 1 THEN 1 END) AS confirmado'),
                DB::raw('COUNT(CASE WHEN orders.status = 2 THEN 1 END) AS pendente'),
                DB::raw('COUNT(CASE WHEN orders.status = 3 THEN 1 END) AS cancelado'),
                DB::raw('lotes.quantity - COUNT(CASE WHEN (orders.status = 1 or orders.status = 2) THEN 1 END) AS restante'),
                // DB::raw("(COUNT(CASE WHEN orders.status = 1 THEN 1 END) * lotes.value) AS total_confirmado"))
                DB::raw(
                    "sum(case 
                                when 
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value)
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value 
                                when    
                                    lotes.type = 0 and orders.status = 1 and coupons.discount_type is null and coupons.code is null then order_items.value 
                                end
                            ) as total_confirmado"
                )
            )
            ->groupBy('lotes.id')
            ->get();

        // $participantes = Participante::orderBy('orders.created_at', 'asc')
        //             ->join('orders', 'orders.participante_id', '=', 'participantes.id')
        //             ->join('order_items', 'orders.id', '=', 'order_items.order_id')
        //             ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
        //             ->join('events', 'events.id', '=', 'lotes.event_id')
        //             ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
        //             ->where('events.id', $id)
        //             ->select('lotes.name as lote_name', 'orders.id', 'orders.status as situacao', 'order_items.id as order_item_id', 'order_items.number as inscricao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
        //             ->get();

        $all_orders = Order::orderBy('orders.created_at', 'asc')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->where('event_dates.event_id', $id)
                    // ->select('lotes.name as lote_name', 'orders.id', 'orders.status as situacao', 'order_items.id as order_item_id', 'order_items.number as inscricao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
            ->select('orders.id as order_id', 'orders.status as situacao', 'orders.hash as order_hash', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.name as participante_name', 'participantes.email as participante_email', 'participantes.cpf as participante_cpf')
            ->get();

        $situacao_participantes = Participante::orderBy('participantes.name')
            ->join('orders', 'orders.participante_id', '=', 'participantes.id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->where('events.id', $id)
            ->select('orders.gatway_status', 'order_items.value as lote_value', 'lotes.name as lote_name', 'orders.id', 'order_items.number as inscricao', 'orders.status as situacao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
            ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value) else '' end as valor_porcentagem")
            ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value else '' end as valor_desconto")
            ->get();

        $situacao_participantes_lotes = OrderItem::orderBy('order_items.created_at', 'desc')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
                    // ->join('option_answers', 'option_answers.order_item_id', '=', 'order_items.id')
            ->where('events.id', $id)
            ->select('order_items.id as id', 'order_items.number', 'order_items.status as status_item', 'lotes.name as lote_name')
            ->get();

        // dd($situacao_participantes_lotes);

        $payment_methods = Order::orderBy('orders.gatway_payment_method')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->where('events.id', $id)
            ->where('lotes.type', 0)
            ->select('orders.gatway_payment_method', DB::raw('count(*) as payment_methods_total'))
            ->groupBy('orders.gatway_payment_method')
            ->get();

        $situacao_coupons = Coupon::orderBy('coupons.id')
            ->join('orders', 'orders.coupon_id', '=', 'coupons.id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->where('coupons.status', '1')
            ->where('events.id', $id)
            ->select(
                'coupons.id',
                'coupons.code',
                'coupons.limit_buy',
                'coupons.discount_type',
                'coupons.discount_value',
                DB::raw('COUNT(CASE WHEN orders.status = 1 THEN 1 END) AS confirmado'),
                DB::raw('COUNT(CASE WHEN orders.status = 2 THEN 1 END) AS pendente')
            )
            ->groupBy('coupons.id', 'coupons.code', 'coupons.limit_buy', 'coupons.discount_type', 'coupons.discount_value')
            ->havingRaw('confirmado > 0 OR pendente > 0')
            ->get();

        // dd($payment_methods);

        $participantes_json = response()->json($all_orders);
        $payment_methods_json = response()->json($payment_methods);

        // dd($payment_methods_json);

        return view('event.reports', compact('event', 'lotes', 'resumo', 'all_orders', 'participantes_json', 'config', 'situacao_participantes', 'situacao_participantes_lotes', 'payment_methods', 'payment_methods_json', 'situacao_coupons'));
    }

    public function participantes_edit($id)
    {

        $participanteLote = Order::orderBy('orders.created_at', 'asc')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->where('order_items.id', $id)
            ->select('events.id as event_id', 'orders.id as order_id', 'orders.status', 'order_items.id as order_item_id', 'order_items.number', 'participantes.id as participante_id', 'participantes.name as participante_name', 'participantes.email as participante_email', 'lotes.id as lote_id', 'lotes.name as lote_name')
            ->first();

        $lotes = Lote::orderBy('order')
            ->where('event_id', $participanteLote->event_id)
            ->get();

        $event = Event::findOrFail($participanteLote->event_id);

        // dd($participanteLote);

        return view('event.edit_inscricao', compact('participanteLote', 'lotes', 'event'));
    }

    public function participantes_update(Request $request, $id)
    {
        $orderItemObj = Order::orderBy('orders.created_at', 'asc')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->where('order_items.id', $id)
            ->select('events.id as event_id', 'orders.id as order_id', 'order_items.number', 'orders.status', 'participantes.id as participante_id', 'participantes.name as participante_name', 'participantes.email as participante_email', 'lotes.id as lote_id', 'lotes.name as lote_name')
            ->first();

        // dd($participanteLote_id);

        $order = Order::findOrFail($orderItemObj->order_id);
        $orderItem = OrderItem::findOrFail($id);

        $input = $request->all();

        $order['status'] = $input['status'];
        $orderItem['lote_id'] = $input['lote_id'];

        // dd($orderItem);

        $order->save();
        $orderItem->save();

        return redirect()->route('event.reports', $orderItemObj->event_id)->withFragment('#participantes_table');
    }

    public function order_details(Request $request, $id)
    {
        $order = Order::orderBy('orders.created_at', 'asc')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->where('orders.id', $id)
            ->select('events.id as event_id', 'orders.id as order_id', 'orders.hash as order_hash', 'orders.status as situacao', 'orders.gatway_hash as gatway_hash', 'orders.gatway_reference as gatway_reference', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.id as participante_id', 'participantes.name as participante_name', 'participantes.email as participante_email', 'lotes.id as lote_id', 'lotes.name as lote_name')
            ->first();

        return view('event.order_detail', compact('order'));
    }
}
