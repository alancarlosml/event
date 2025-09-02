<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Lote;
use App\Models\Option;
use App\Models\Participante;
use App\Models\Place;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Question;
use App\Models\State;

class EventController extends Controller
{
    public function index()
    {
        try {
            // Optimized with eager loading to reduce N+1 queries
            $events = Event::with([
                'place.city.state',
                'event_dates',
                'lotes',
                'owner',
                'participantes' => function($query) {
                    $query->where('participantes_events.role', 'admin')
                          ->where('participantes_events.status', 1);
                }
            ])
            ->select([
                'events.*',
                DB::raw('MIN(event_dates.date) as date_event_min'),
                DB::raw('MAX(event_dates.date) as date_event_max')
            ])
            ->leftJoin('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->leftJoin('places', 'places.id', '=', 'events.place_id')
            ->leftJoin('participantes_events', 'participantes_events.event_id', '=', 'events.id')
            ->leftJoin('participantes', 'participantes.id', '=', 'participantes_events.participante_id')
            ->where(function($query) {
                $query->where('participantes_events.role', 'admin')
                      ->orWhereNull('participantes_events.role');
            })
            ->orderBy('events.created_at', 'desc')
            ->groupBy('events.id')
            ->get();

            return view('event.index', compact('events'));

        } catch (\Exception $e) {
            Log::error('Error loading events index: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar eventos. Tente novamente.');
        }
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
            'contact' => 'required',
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
        // $event = DB::table('events')
        //     ->leftJoin('places', 'places.id', '=', 'events.place_id')
        //     ->leftJoin('cities', 'cities.id', '=', 'places.city_id')
        //     ->leftJoin('states', 'states.id', '=', 'cities.uf')
        //     ->leftJoin('participantes_events', 'participantes_events.event_id', '=', 'events.id')
        //     ->leftJoin('participantes', 'participantes.id', '=', 'participantes_events.participante_id')
        //     ->leftJoin('areas', 'areas.id', '=', 'events.area_id')
        //     ->leftJoin('categories', 'categories.id', '=', 'areas.category_id')
        //     ->leftJoin('event_dates', 'event_dates.event_id', '=', 'events.id')
        //     ->where('events.id', $id)
        //     ->where('participantes_events.role', 'admin')
        //     ->select(
        //         'events.*',
        //         'categories.id as category_id',
        //         'places.name as place_name',
        //         'places.address as place_address',
        //         'places.number as place_number',
        //         'places.district as place_district',
        //         'places.complement as place_complement',
        //         'places.zip as place_zip',
        //         'cities.id as city_id',
        //         'states.uf as city_uf',
        //         'participantes.email as participante_email'
        //     )
        //     ->first();

        // $dates = DB::table('event_dates')
        //     ->join('events', 'events.id', '=', 'event_dates.event_id')
        //     ->where('events.id', $id)
        //     ->selectRaw(
        //         'event_dates.id, 
        //         DATE_FORMAT(event_dates.date, "%d/%m/%Y") as date, 
        //         DATE_FORMAT(event_dates.time_begin, "%H:%i") as time_begin,
        //         DATE_FORMAT(event_dates.time_end, "%H:%i") as time_end'
        //     )
        //     ->orderBy('event_dates.date')
        //     ->get();

        // $categories = Category::orderBy('description')->get();
        // $states = State::orderBy('name')->get();

        $event = Event::where('id', $id)->first();
        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();
        $options = Option::orderBy('id')->get();
        $questions = Question::orderBy('order')->where('event_id', $event->id)->get();

        $dates = DB::table('event_dates')
            ->join('events', 'events.id', '=', 'event_dates.event_id')
            ->where('events.hash', $event->hash)
            ->selectRaw(
                'event_dates.id, 
                DATE_FORMAT(event_dates.date, "%d/%m/%Y") as date, 
                DATE_FORMAT(event_dates.time_begin, "%H:%i") as time_begin,
                DATE_FORMAT(event_dates.time_end, "%H:%i") as time_end'
            )
            ->orderBy('event_dates.date')
            ->get();

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
                'contact' => 'required',
                'place_name' => 'required',
                'date.*' => 'required',
                'time_begin.*' => 'required',
                'time_end.*' => 'required',
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
                'contact' => 'required',
                'place_name' => 'required',
                'date.*' => 'required',
                'time_begin.*' => 'required',
                'time_end.*' => 'required',
                'date_id.*' => 'nullable',
                'address' => 'required',
                'number' => 'required',
                'district' => 'required',
                'zip' => 'required',
                'state' => 'required',
                'city_id' => 'required',
            ]);
        }

        $input = $request->all();

        $date_ids = $input['date_id'];
        $dates = $input['date'];
        $times_begin = $input['time_begin'];
        $times_end = $input['time_end'];

        $date_ids_db = EventDate::where('event_id', $id)
            ->where('status', '1')
            ->get()
            ->map
            ->only('id')
            ->toArray();

        foreach($date_ids_db as $date_id_db) {

            if( ! in_array($date_id_db['id'], $date_ids)) {
                DB::table('event_dates')->where('id', $date_id_db['id'])->delete();
            }
        }

        $date_events_array = [];
        for ($i = 0; $i < count($dates); $i++) {
            $date = str_replace('/', '-', $dates[$i]);
            if( ! isset($date_ids[$i])) {
                array_push($date_events_array, [
                    'date_id' => null,
                    'date' => date('Y-m-d', strtotime($date)),
                    'time_begin' => date('H:i', strtotime($times_begin[$i])),
                    'time_end' => date('H:i', strtotime($times_end[$i])),
                ]);
            } else {
                array_push($date_events_array, [
                    'date_id' => $date_ids[$i],
                    'date' => date('Y-m-d', strtotime($date)),
                    'time_begin' => date('H:i', strtotime($times_begin[$i])),
                    'time_end' => date('H:i', strtotime($times_end[$i])),
                ]);
            }
        }

        foreach($date_events_array as $arr_date) {

            if($arr_date['date_id'] == null) {
                EventDate::create([
                    'date' => date('Y-m-d', strtotime(str_replace('/', '-', $arr_date['date']))),
                    'time_begin' => date('H:i', strtotime($arr_date['time_begin'])),
                    'time_end' => date('H:i', strtotime($arr_date['time_end'])),
                    'status' => 1,
                    'event_id' => $id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                // $dates[$i] = str_replace('/', '-', $dates[$i]);
                $arr_dates = [
                    'date' => date('Y-m-d', strtotime(str_replace('/', '-', $arr_date['date']))),
                    'time_begin' => date('H:i', strtotime($arr_date['time_begin'])),
                    'time_end' => date('H:i', strtotime($arr_date['time_end'])),
                ];

                // array_push($finalArray, $arr_dates);
                EventDate::where('id', $arr_date['date_id'])->update($arr_dates);
            }
        }

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
            ->select('places.name as value', 'places.id', 'places.address', 'places.number', 'places.complement', 'places.district', 'places.zip', 'places.city_id', 'states.id as uf')
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

    public function questions($id){

        $event = Event::find($id);

        $questions = Question::orderBy('order')->where('event_id', $event->id)->get();
        $options = Option::orderBy('id')->get();

        return view('event.questions', compact('event', 'questions', 'options', 'id'));
    }

    public function create_questions(Request $request, $id){

        $event = Event::find($id);

        $validatedDataQuestion = $request->validate([
            'new_field' => 'required',
            'new_field_id' => 'nullable',
        ],[
            'new_field.required' => 'As perguntas são obrigatórias.',
        ]);

        $fields = $validatedDataQuestion['new_field'];
        $field_ids = $validatedDataQuestion['new_field_id'];
        $event_id = $event->id;

        // Verifica se está faltando um id para excluir no banco
        $questions_ids_db = Question::where('event_id', $event_id)
            ->where('status', '1')
            ->get()
            ->map
            ->only('id')
            ->toArray();

        foreach($questions_ids_db as $questions_ids_db) {

            if( ! in_array($questions_ids_db['id'], $field_ids)) {
                DB::table('questions')->where('id', $questions_ids_db['id'])->delete();
            }
        }
        //fim

        $questions_array = [];
        for ($i = 0; $i < count($fields); $i++) {
            if( ! isset($field_ids[$i])) {
                array_push($questions_array, [
                    'question_id' => null,
                    'question' => $fields[$i],
                ]);
            } else {
                array_push($questions_array, [
                    'question_id' => $field_ids[$i],
                    'question' => $fields[$i],
                ]);
            }
        }

        // dd($questions_array);

        foreach($questions_array as $id => $field) {

            if($field['question_id'] == null) {

                preg_match_all("/\(([^\]]*)\)/", $field['question'], $matches);
                $result_field = $matches[1];
                $result_field = str_replace('Tipo: ', '', $result_field);

                $more_fields = explode(';', $field['question']);

                $required = 0;
                if(strpos($field['question'], 'Obrigatório')) {
                    $required = 1;
                }

                $unique = 0;
                if(strpos($field['question'], 'Único')) {
                    $unique = 1;
                }

                $option = Option::where('option', $result_field[0])->first();
                $question = Question::create([
                    // DB::table('questions')->insert([
                    'question' => $more_fields[0],
                    'order' => $id + 1,
                    'required' => $required,
                    'unique' => $unique,
                    'status' => 1,
                    'option_id' => $option->id,
                    'event_id' => $event_id,
                ]);

                $id_question = $question->id;

                $result_regex = preg_match_all("/\[([^\]]*)\]/", $field['question'], $matches);

                if($result_regex) {
                    $result_field = $matches[1];
                    $result_field = str_replace('Opções: ', '', $result_field);

                    $array_explode_question = explode(',', $result_field[0]);

                    foreach($array_explode_question as $item_array_explode_question) {
                        DB::table('option_values')->insert([
                            'value' => trim($item_array_explode_question),
                            'question_id' => $id_question,
                        ]);
                    }
                }
            }
        }

        return back()->with('success', 'Questionário salvo com sucesso');

        #$questions = Question::orderBy('order')->where('event_id', $event_id)->get();

        #$request->session()->put('questions', $questions);
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
        try {
            $event = Event::with(['place.city.state', 'owner'])->findOrFail($id);

            $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;
        if($event->config_tax != 0.0) {
            $taxa_juros = $event->config_tax;
        }

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

        // Buscar métodos de pagamento incluindo vendas gratuitas
        $payment_methods = Order::orderBy('orders.gatway_payment_method')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->where('events.id', $id)
            ->where(function($query) {
                $query->where('lotes.type', 0) // Lotes pagos
                      ->orWhere('lotes.type', 1) // Lotes gratuitos
                      ->orWhere('order_items.value', 0); // Valor zero (gratuito)
            })
            ->select('orders.gatway_payment_method', DB::raw('count(*) as payment_methods_total'))
            ->groupBy('orders.gatway_payment_method')
            ->get();

        // Se não há métodos de pagamento mas há vendas, criar entrada para vendas gratuitas
        if ($payment_methods->isEmpty()) {
            $total_free_orders = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
                ->join('events', 'events.id', '=', 'lotes.event_id')
                ->where('events.id', $id)
                ->where(function($query) {
                    $query->where('lotes.type', 1) // Lotes gratuitos
                          ->orWhere('order_items.value', 0); // Valor zero
                })
                ->count();

            if ($total_free_orders > 0) {
                $payment_methods = collect([
                    (object) [
                        'gatway_payment_method' => 'free',
                        'payment_methods_total' => $total_free_orders
                    ]
                ]);
            }
        }

        // Debug: Log payment methods data
        Log::info('Payment methods data for event ' . $id, [
            'count' => $payment_methods->count(),
            'data' => $payment_methods->toArray()
        ]);

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

        } catch (\Exception $e) {
            Log::error('Error loading event reports: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar relatórios. Tente novamente.');
        }
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
