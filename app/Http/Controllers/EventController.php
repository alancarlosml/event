<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\Lote;
use App\Models\Participante;
use App\Models\ParticipanteLote;
use App\Models\Place;
use App\Models\Order;
use App\Models\Owner;
use App\Models\State;

class EventController extends Controller
{
    public function index(){
        
        $events = DB::table('events')
        ->join('places', 'places.id', '=', 'events.place_id')
        ->join('owners', 'owners.id', '=', 'events.owner_id')
        ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
        ->select('events.*', 'places.name as place_name', 'owners.name as owner_name', DB::raw('MIN(event_dates.date) as date_event_min'), DB::raw('MAX(event_dates.date) as date_event_max'))
        ->orderBy('events.name')
        ->groupBy('events.id')
        ->get();

        // dd($events);

        return view('event.index', compact('events'));
    }

    public function create(){

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
            'status' => 'required'
        ]);

        $input = $request->all();

        Event::create($input);

        return redirect()->route('event.index');
    }

    public function edit($id){
                
        // $event = Event::find($id);
        $event = DB::table('events')
            ->join('places', 'places.id', '=', 'events.place_id')
            ->join('cities', 'cities.id', '=', 'places.city_id')
            ->join('states', 'states.uf', '=', 'cities.uf')
            ->join('owners', 'owners.id', '=', 'events.owner_id')
            ->join('areas', 'areas.id', '=', 'events.area_id')
            ->join('categories', 'categories.id', '=', 'areas.category_id')
            ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->where('events.id', $id)
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
                'owners.email as owner_email'
                )
            ->first();

        // dd($event);

        $dates = DB::table('event_times')
            ->join('event_dates', 'event_dates.id', '=', 'event_times.event_dates_id')
            ->join('events', 'events.id', '=', 'event_dates.event_id')
            ->where('events.id', $id)
            ->select(
                'event_dates.date', 
                'event_times.time_begin',
                'event_times.time_end'
                )
            ->get();

        // dd($dates);

        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();

        return view('event.edit', compact('event', 'dates', 'categories', 'states'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if($event->banner){
            $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:events,slug,'.$event->id,
                'description' => 'required',
                'category' => 'required',
                'area_id' => 'required',
                'max_tickets' => 'required',
                'place_name' => 'required',
                'address' => 'required',
                'number' => 'required',
                'district' => 'required',
                'zip' => 'required',
                'state' => 'required',
                'city_id' => 'required'
            ]);
        }else{
            $request->validate([
                'banner' => 'mimes:jpg,jpeg,bmp,png|max:2048',
                'name' => 'required',
                'slug' => 'required|unique:events,slug,'.$event->id,
                'description' => 'required',
                'category' => 'required',
                'area_id' => 'required',
                'max_tickets' => 'required',
                'place_name' => 'required',
                'address' => 'required',
                'number' => 'required',
                'district' => 'required',
                'zip' => 'required',
                'state' => 'required',
                'city_id' => 'required'
            ]);
        }

        $input = $request->all();

        // dd($input);
        
        $owner = Owner::where('email', $input['owner_email'])->first();

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $input['owner_id'] = $owner->id;

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

            $place = new Place;

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

            Event::where('id', $event->id)->update(array('place_id' => $id_place));
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

    public function show($id){
                
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
                    ->join('states', 'states.uf', '=', 'cities.uf')
                    ->where('places.name', 'LIKE', '%'. $request->get('search'). '%')
                    ->select("places.name as value", "places.id", "places.address", "places.number", "places.complement", "places.district", "places.zip", "places.city_id", "states.uf")
                    ->get();
    
        return response()->json($data);
    }

    public function lotes($id){

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

        $path = public_path()."/storage/".$event->banner;

        unlink($path);

        if($event) {
            $event->banner = '';
            $event->save();
        }

        return back()
            ->with('success','Arquivo removido com sucesso!');
    }

    public function reports(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        $resumo = DB::table('lotes')
                ->leftJoin('participantes_lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                ->leftJoin('inscricoes_coupons', 'inscricoes_coupons.participante_lote_id', '=', 'participantes_lotes.id')
                ->leftJoin('coupons', 'inscricoes_coupons.coupon_id', '=', 'coupons.id')
                ->where('lotes.event_id', $id)
                ->selectRaw('count(*) as total')
                ->selectRaw("count(case when participantes_lotes.status = 1 then 1 end) as confirmado")
                ->selectRaw("count(case when participantes_lotes.status = 2 then 1 end) as pendente")
                ->selectRaw("count(case when ((participantes_lotes.status = 1 or participantes_lotes.status = 2)) then 1 end) as geral")
                // ->selectRaw("sum(case when participantes_lotes.status = 1 then 1 * lotes.value end) as total_confirmado")
                ->selectRaw("sum(case 
                                when 
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then lotes.value - (coupons.discount_value * lotes.value)
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then lotes.value - coupons.discount_value 
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type is null and coupons.code is null then lotes.value 
                                end
                            ) as total_confirmado"
                        )
                // ->selectRaw("sum(case when participantes_lotes.status = 2 then 1 * lotes.value end) as total_pendente")
                ->selectRaw("sum(case 
                                when 
                                    lotes.type = 0 and participantes_lotes.status = 2 and coupons.discount_type = 0 and coupons.code <> '' then lotes.value - (coupons.discount_value * lotes.value)
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 2 and coupons.discount_type = 1 and coupons.code <> '' then lotes.value - coupons.discount_value 
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 2 and coupons.discount_type is null and coupons.code is null then lotes.value 
                                end
                            ) as total_pendente"
                        )
                ->selectRaw("sum(case when ((participantes_lotes.status = 1 or participantes_lotes.status = 2)) then 1 * lotes.value end) as total_geral")
                // ->selectRaw("sum(CASE WHEN participantes_lotes.status = 1 THEN 1 * lotes.value * $taxa_juros END) as total_taxa")
                ->selectRaw("sum(case 
                                when 
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then (lotes.value - (coupons.discount_value * lotes.value)) * $taxa_juros
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then (lotes.value - coupons.discount_value) * $taxa_juros
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type is null and coupons.code is null then lotes.value * $taxa_juros
                                end
                            ) as total_taxa"
                        )
                ->selectRaw("sum(case 
                                when 
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then (lotes.value - (coupons.discount_value * lotes.value)) - ((lotes.value - (coupons.discount_value * lotes.value)) * $taxa_juros)
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then (lotes.value - coupons.discount_value) - ((lotes.value - coupons.discount_value) * $taxa_juros)
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type is null and coupons.code is null then lotes.value - (lotes.value * $taxa_juros)
                                end
                            ) as total_liquido"
                        )
                // ->selectRaw("sum((CASE WHEN participantes_lotes.status = 1 THEN 1 END) * lotes.value) - (sum(CASE WHEN participantes_lotes.status = 1 THEN 1 * lotes.value * $taxa_juros END)) as total_liquido")
                ->first();

        $lotes = Lote::orderBy('order')
                ->leftJoin('participantes_lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                ->leftJoin('inscricoes_coupons', 'inscricoes_coupons.participante_lote_id', '=', 'participantes_lotes.id')
                ->leftJoin('coupons', 'inscricoes_coupons.coupon_id', '=', 'coupons.id')
                ->where('lotes.event_id', $id)
                ->select('lotes.id', 'lotes.name', 'lotes.quantity',
                    DB::raw("COUNT(CASE WHEN participantes_lotes.status = 1 THEN 1 END) AS confirmado"),
                    DB::raw("COUNT(CASE WHEN participantes_lotes.status = 2 THEN 1 END) AS pendente"),
                    DB::raw("COUNT(CASE WHEN participantes_lotes.status = 3 THEN 1 END) AS cancelado"),
                    DB::raw("lotes.quantity - COUNT(CASE WHEN (participantes_lotes.status = 1 or participantes_lotes.status = 2) THEN 1 END) AS restante"),
                    // DB::raw("(COUNT(CASE WHEN participantes_lotes.status = 1 THEN 1 END) * lotes.value) AS total_confirmado"))
                    DB::raw("sum(case 
                                when 
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 0 and coupons.code <> '' then lotes.value - (coupons.discount_value * lotes.value)
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type = 1 and coupons.code <> '' then lotes.value - coupons.discount_value 
                                when    
                                    lotes.type = 0 and participantes_lotes.status = 1 and coupons.discount_type is null and coupons.code is null then lotes.value 
                                end
                            ) as total_confirmado"
                        ))
                ->groupBy('lotes.id')
                ->get();

        $participantes = Participante::orderBy('participantes.name')
                    ->join('participantes_lotes', 'participantes_lotes.participante_id', '=', 'participantes.id')
                    ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                    ->leftJoin('inscricoes_coupons', 'inscricoes_coupons.participante_lote_id', '=', 'participantes_lotes.id')
                    ->leftJoin('coupons', 'inscricoes_coupons.coupon_id', '=', 'coupons.id')
                    ->select('lotes.name as lote_name', 'participantes_lotes.id', 'participantes_lotes.number as inscricao', 'participantes_lotes.status as situacao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
                    ->get();
        
        $situacao_participantes = Participante::orderBy('participantes.name')
                    ->join('participantes_lotes', 'participantes_lotes.participante_id', '=', 'participantes.id')
                    ->join('orders', 'participantes_lotes.id', '=', 'orders.participante_lote_id')
                    ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                    ->leftJoin('inscricoes_coupons', 'inscricoes_coupons.participante_lote_id', '=', 'participantes_lotes.id')
                    ->leftJoin('coupons', 'inscricoes_coupons.coupon_id', '=', 'coupons.id')
                    ->select('orders.gatway_status', 'lotes.value as lote_value','lotes.name as lote_name', 'participantes_lotes.id', 'participantes_lotes.number as inscricao', 'participantes_lotes.status as situacao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
                    ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 0 and coupons.code <> '' then lotes.value - (coupons.discount_value * lotes.value) else '' end as valor_porcentagem")
                    ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 1 and coupons.code <> '' then lotes.value - coupons.discount_value else '' end as valor_desconto")
                    ->get();

                    // dd($situacao_participantes);
        
        $payment_methods = Order::orderBy('orders.gatway_payment_method')
                    ->select('orders.gatway_payment_method', DB::raw('count(*) as payment_methods_total'))
                    ->groupBy('orders.gatway_payment_method')
                    ->get();

        $situacao_coupons  = Coupon::orderBy('coupons.id')
                    ->join('inscricoes_coupons', 'inscricoes_coupons.coupon_id', '=', 'coupons.id')
                    ->join('participantes_lotes', 'participantes_lotes.id', '=', 'inscricoes_coupons.participante_lote_id')
                    ->where('coupons.status', '1')
                    ->select('coupons.id', 'coupons.code','coupons.limit_buy', 'coupons.discount_type', 'coupons.discount_value',
                    DB::raw("COUNT(CASE WHEN participantes_lotes.status = 1 THEN 1 END) AS confirmado"),
                    DB::raw("COUNT(CASE WHEN participantes_lotes.status = 2 THEN 1 END) AS pendente"))
                    ->get();

        // dd($payment_methods);

        $participantes_json = response()->json($participantes);
        $payment_methods_json = response()->json($payment_methods);

        // dd($payment_methods_json);

        return view('event.reports', compact('event', 'lotes', 'resumo', 'participantes', 'id', 'participantes_json', 'config', 'situacao_participantes', 'payment_methods', 'payment_methods_json', 'situacao_coupons'));
    }

    public function participantes_edit($id){
                
        $participanteLote = DB::table('participantes_lotes')
                            ->join('participantes', 'participantes_lotes.participante_id', '=', 'participantes.id')
                            ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                            ->join('events', 'events.id', '=', 'lotes.event_id')
                            ->where('participantes_lotes.id', $id)
                            ->select('events.id as event_id', 'participantes_lotes.id as participantes_lotes_id', 'participantes_lotes.number', 'participantes_lotes.status', 'participantes.id as participante_id', 'participantes.name as participante_name', 'participantes.email as participante_email', 'lotes.id as lote_id', 'lotes.name as lote_name')
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

        $participanteLote_id = DB::table('participantes_lotes')
                            ->join('participantes', 'participantes_lotes.participante_id', '=', 'participantes.id')
                            ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                            ->join('events', 'events.id', '=', 'lotes.event_id')
                            ->where('participantes_lotes.id', $id)
                            ->select('events.id as event_id', 'participantes_lotes.id as participantes_lotes_id', 'participantes_lotes.number', 'participantes_lotes.status', 'participantes.id as participante_id', 'participantes.name as participante_name', 'participantes.email as participante_email', 'lotes.id as lote_id', 'lotes.name as lote_name')
                            ->first();

        $participanteLote = ParticipanteLote::findOrFail($id);

        $input = $request->all();

        $participanteLote['status'] = $input['status'];
        $participanteLote['lote_id'] = $input['lote_id'];

        // dd($participanteLote);

        $participanteLote->save();
    
        return redirect()->route('event.reports', $participanteLote_id->event_id)->withFragment('#participantes_table');
    }

}
