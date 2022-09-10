<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Lote;
use App\Models\Participante;
use App\Models\ParticipanteLote;
use App\Models\Place;
use App\Models\Option;
use App\Models\Order;
use App\Models\Owner;
use App\Models\User;
use App\Models\State;

class EventHomeController extends Controller
{
    public function events(){

        // $menu = 'home';
        // $title = 'Home';
        // $url = url('/');
        // $description = 'Bilhete Mania - Venda de ingressos online';
        // $image = url('img/favicon/favicon-96x96.png');

        $event = new Event;
        // $event = new Event;
        // // $faq = new Faq;

        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();

        $events = $event::getEvents('', '0', '0', '0', '0');

        // dd($events);

        // $events = $event::where('status', 1)->paginate(1);
        // $events = $event->getAll();
        // // $faqs = $faq->getAll();

        // $nextevents = DB::table('events')->where('active', 1)->take(6)->orderBy('created_at', 'desc')->get();

        // $spotlights = $event->getAllSpotlights();

        // dd($events->get(0)->category);

        return view('site.events', compact('events', 'categories', 'states'));
    }

    public function getMoreEvents(Request $request) {
        $event_val = $request->event_val;
        $category_val = $request->category_val;
        $area_val = $request->area_val;
        $state_val = $request->state_val;
        $period_val = $request->period_val;
        if($request->ajax()) {
            $events = Event::getEvents($event_val, $category_val, $area_val, $state_val, $period_val);
            return view('site.events_data', compact('events'))->render();
        }
    }

    public function getCity(Request $request)
    {
        $data['cities'] = City::where("uf",$request->uf)
                    ->get(["name","id"]);
        
        return response()->json($data);
    }

    public function create_event(Request $request)
    {
        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();
        $options = Option::orderBy('id')->get();

        $event = $request->session()->get('event');
        $place = $request->session()->get('place');
        $eventDate = $request->session()->get('eventDate');

        // $menu = 'home';
        // $title = 'Home';
        // $url = url('/');
        // $description = 'Bilhete Mania - Venda de ingressos online';
        // $image = url('img/favicon/favicon-96x96.png');

        // $event = Event::where('slug', $slug)->first();

        // dd($event->event_dates);
        // $event = new Event;
        // // $faq = new Faq;

        // $events = $event->getAll();
        // // $faqs = $faq->getAll();

        // $nextevents = DB::table('events')->where('active', 1)->take(6)->orderBy('created_at', 'desc')->get();

        // $spotlights = $event->getAllSpotlights();

        // dd($events->get(0)->category);

        return view('painel_admin.create_event', compact('categories', 'states', 'options', 'event', 'place', 'eventDate'));
    }

    public function postCreateStepOne(Request $request)
    {
        // dd($request->session()->get('event'));
        
        // try {
        if(empty($request->session()->get('event'))){
            
            $validatedDataEvent = $request->validate([
                'name' => 'required',
                'hash' => 'string',
                'slug' => 'required|unique:events',
                'subtitle' => 'string',
                'description' => 'required',
                'category' => 'required',
                'area_id' => 'required',
                'max_tickets' => 'required',
                'admin_id' => 'required',
                'status' => 'string'
            ]);

            // $owner = Owner::where('email', $validatedDataEvent['owner_email'])->first();
            // $owner = User::where('email', $validatedDataEvent['owner_email'])->first();
            // $validatedDataEvent['owner_id'] = $owner->id;
            
            $validatedDataEvent['hash'] = md5($validatedDataEvent['name'] . $validatedDataEvent['description'] . md5('papainoel'));
            
            $event = new Event();
            $event->fill($validatedDataEvent);
            $event->save();
            $event_id = $event->id;
            $event->participantes()->attach([
                'event_id' => $event_id, 
                'participante_id' => $validatedDataEvent['admin_id'], 
                'role' => 'admin', 
                'created_at' => date('Y-m-d H:i:s')]
            );
            $request->session()->put('event', $event);
            $request->session()->put('event_id', $event_id);
        }else{
            // $event = $request->session()->get('event');
            $event_id = $request->session()->get('event_id');
            $event = Event::findOrFail($event_id);
            $validatedDataEvent = $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:events,slug,'.$event_id,
                'description' => 'required',
                'subtitle' => 'string',
                'category' => 'required',
                'area_id' => 'required',
                'max_tickets' => 'required',
                'admin_id' => 'required',
                'status' => 'string'
            ]);

            // $owner = Owner::where('email', $validatedDataEvent['owner_email'])->first();
            // $owner = User::where('email', $validatedDataEvent['owner_email'])->first();
            // $validatedDataEvent['owner_id'] = $owner->id;
            
            $event->fill($validatedDataEvent);
            $request->session()->put('event', $event);
            $event->save();
        }

        // } catch (\Illuminate\Database\QueryException $e) {
        //     return back()->withError($e->getMessage());
        //     dd($e);

        // } catch (Exception $exception) {
        //     return back()->withError($exception->getMessage());
        // }

        // try {

        $validatedDataPlace = $request->validate([
            'place_id_hidden' => 'nullable',
            'place_name' => 'required',
            'address' => 'required',
            'number' => 'required',
            'district' => 'required',
            'complement' => 'nullable',
            'zip' => 'required',
            'city_id_hidden' => 'required'
        ]);

        $validatedDataPlace['name'] = $validatedDataPlace['place_name'];
        $validatedDataPlace['city_id'] = $validatedDataPlace['city_id_hidden'];
        $validatedDataPlace['place_id'] = $validatedDataPlace['place_id_hidden'];
        
        $event_id = $request->session()->get('event_id');

        if($validatedDataPlace['place_id'] != null) {
            $event = Event::where('id', $event_id)->update(array('place_id' => $validatedDataPlace['place_id']));
            $place = Place::findOrFail($validatedDataPlace['place_id']);
            $request->session()->put('place', $place);
            $request->session()->put('place_id', $validatedDataPlace['place_id']);
            $request->session()->put('uf', $place->get_city()->uf);
        } else{
            $place = new Place();
            $place->fill($validatedDataPlace);
            $place->save();
            $place_id = $place->id;
            Event::where('id', $event_id)->update(array('place_id' => $place_id));
            $request->session()->put('place', $place);
            $request->session()->put('place_id', $place_id);
        }

        // dd($validatedDataPlace);
            
            // if(empty($request->session()->get('place'))){
            //     $place = new Place();
            //     $place->fill($validatedDataPlace);
            //     $place->save();
            //     $place_id = $place->id;
            //     $request->session()->put('place', $place);
            //     $request->session()->put('place_id', $place_id);
            // }else{
            //     $place_id = $request->session()->get('place_id');
            //     $place = Place::findOrFail($place_id);
            //     $place->fill($validatedDataPlace);
            //     $request->session()->put('place', $place);
            // }

        // } catch (Exception $exception) {
        //     return back()->withError($exception->getMessage());
        // }

        // try {

        $validatedDataEventDate = $request->validate([
            'date' => 'required',
            'time_begin' => 'required',
            'time_end' => 'required'
        ]);

        $dates = $validatedDataEventDate['date'];
        $times_begin = $validatedDataEventDate['time_begin'];
        $times_end = $validatedDataEventDate['time_end'];
        $event_id = $request->session()->get('event_id');

        $finalArray = array();
        for ($i = 0; $i < count($dates); $i++) {

            $dates[$i] = str_replace('/', '-', $dates[$i]);
            array_push($finalArray, array(
                'date' => date('Y-m-d', strtotime($dates[$i])),
                'time_begin' => date('H:i', strtotime($times_begin[$i])),
                'time_end' => date('H:i', strtotime($times_end[$i])),
                'status' => 1,
                'event_id' => $event_id,
                'created_at' => date("Y-m-d H:i:s")
            ));
        }

        if(empty($request->session()->get('eventDate'))){
            DB::table('event_dates')->where('event_id', $event_id)->delete();
            EventDate::insert($finalArray);
            $request->session()->put('eventDate', $finalArray);
        }else{
            DB::table('event_dates')->where('event_id', $event_id)->delete();
            EventDate::insert($finalArray);
            $request->session()->put('eventDate', $finalArray);
        }

        // } catch (Exception $exception) {
        //     return back()->withError($exception->getMessage());
        // }

        // $finalArray = array();
        // for ($i = 0; $i < count($dates); $i++) {

        //     array_push($finalArray, array(
        //         'date' => $dates[$i],
        //         'time_begin' => $times_begin[$i],
        //         'time_end' => $times_end[$i]
        //     ));
        // }

        // dd($finalArray);
        // EventDate::insert($finalArray);   

        return redirect()->route('event_home.create.step.two');
    }

    public function createStepTwo(Request $request)
    {
        $lotes = $request->session()->get('lotes');
  
        return view('painel_admin.list_lotes', compact('lotes'));
    }

    public function createLote()
    {
        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        return view('painel_admin.list_lotes_add', compact('taxa_juros'));
    }

    public function storeLote(Request $request)
    {
        $input = $request->all();

        if($input['type'] == 0){
            $validatedData = $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required',
                'visibility' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gt:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'tax_parcelamento' => 'required|integer',
                'tax_service' => 'required|integer',
                'form_pagamento' => 'required',
                'value' => 'required',
                'final_value' => 'nullable',
                'event_id' => 'nullable'
            ]);
        } else {
            $validatedData = $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required',
                'visibility' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gt:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'event_id' => 'nullable'
            ]);
        }

        $event_id = $request->session()->get('event_id');
        $number_lotes = Lote::where("event_id", $event_id)->count();

        $validatedData['event_id'] = $event_id;

        $validatedData['order'] = $number_lotes + 1;
        $validatedData['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $validatedData['datetime_begin'])));
        $validatedData['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $validatedData['datetime_end'])));

        if($validatedData['type'] == 0) {
            $validatedData['tax_parcelamento'] = doubleval($validatedData['value']) * 0.07;
            $validatedData['final_value'] = doubleval($validatedData['value']) - doubleval($validatedData['value']) * 0.07;
        }

        $lote = new Lote();
        $validatedData['form_pagamento'] = '';
        $lote->fill($validatedData);
        $lote->save();
        $lote_id = $lote->id;
        
        $lotes = Lote::orderBy('order')
                ->where('event_id', $event_id)
                ->get();
        // dd($lotes);
        $request->session()->put('lotes', $lotes);
        $request->session()->put('lote_id', $lote_id);
        // if(empty($request->session()->get('lotes'))){
        // }else{
        //     $lote = $request->session()->get('lotes');
        //     $lote->fill($validatedData);
        //     $request->session()->put('lote', $lote);
        // }

        return redirect()->route('event_home.create.step.two');
    }



    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'banner' => 'mimes:jpg,jpeg,bmp,png|max:2048',
    //         'name' => 'required',
    //         'slug' => 'required|unique:events',
    //         'description' => 'required',
    //         'category' => 'required',
    //         'area_id' => 'required',
    //         'total' => 'required',
    //         'place_name' => 'required',
    //         'address' => 'required',
    //         'number' => 'required',
    //         'district' => 'required',
    //         'zip' => 'required',
    //         'state' => 'required',
    //         'city_id' => 'required',
    //         'status' => 'required'
    //     ]);

    //     $input = $request->all();

    //     Event::create($input);

    //     return redirect()->route('event.index');
    // }

    // public function edit($id){
                
    //     // $event = Event::find($id);
    //     $event = DB::table('events')
    //         ->join('places', 'places.id', '=', 'events.place_id')
    //         ->join('cities', 'cities.id', '=', 'places.city_id')
    //         ->join('states', 'states.uf', '=', 'cities.uf')
    //         ->join('owners', 'owners.id', '=', 'events.owner_id')
    //         ->join('areas', 'areas.id', '=', 'events.area_id')
    //         ->join('categories', 'categories.id', '=', 'areas.category_id')
    //         ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
    //         ->where('events.id', $id)
    //         ->select(
    //             'events.*', 
    //             'categories.id as category_id',
    //             'places.name as place_name', 
    //             'places.address as place_address', 
    //             'places.number as place_number', 
    //             'places.district as place_district', 
    //             'places.complement as place_complement', 
    //             'places.zip as place_zip', 
    //             'cities.id as city_id', 
    //             'states.uf as city_uf',
    //             'owners.email as owner_email'
    //             )
    //         ->first();

    //     // dd($event);

    //     $dates = DB::table('event_dates')
    //         ->join('events', 'events.id', '=', 'event_dates.event_id')
    //         ->where('events.id', $id)
    //         ->selectRaw(
    //             'event_dates.id, 
    //             DATE_FORMAT(event_dates.date, "%d/%m/%Y") as date, 
    //             DATE_FORMAT(event_dates.time_begin, "%H:%i") as time_begin,
    //             DATE_FORMAT(event_dates.time_end, "%H:%i") as time_end'
    //             )
    //         ->orderBy('event_dates.date')
    //         ->get();

    //     // dd($dates);

    //     $categories = Category::orderBy('description')->get();
    //     $states = State::orderBy('name')->get();

    //     return view('event.edit', compact('event', 'dates', 'categories', 'states'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $event = Event::findOrFail($id);

    //     if($event->banner){
    //         $request->validate([
    //             'name' => 'required',
    //             'slug' => 'required|unique:events,slug,'.$event->id,
    //             'description' => 'required',
    //             'category' => 'required',
    //             'area_id' => 'required',
    //             'max_tickets' => 'required',
    //             'place_name' => 'required',
    //             'date' => 'required',
    //             'time_begin' => 'required',
    //             'time_end' => 'required',
    //             'address' => 'required',
    //             'number' => 'required',
    //             'district' => 'required',
    //             'zip' => 'required',
    //             'state' => 'required',
    //             'city_id' => 'required'
    //         ]);
    //     }else{
    //         $request->validate([
    //             'banner' => 'mimes:jpg,jpeg,bmp,png|max:2048',
    //             'name' => 'required',
    //             'slug' => 'required|unique:events,slug,'.$event->id,
    //             'description' => 'required',
    //             'category' => 'required',
    //             'area_id' => 'required',
    //             'max_tickets' => 'required',
    //             'place_name' => 'required',
    //             'date' => 'required',
    //             'time_begin' => 'required',
    //             'time_end' => 'required',
    //             'address' => 'required',
    //             'number' => 'required',
    //             'district' => 'required',
    //             'zip' => 'required',
    //             'state' => 'required',
    //             'city_id' => 'required'
    //         ]);
    //     }

    //     $input = $request->all();

    //     // dd($input);

    //     $dates = $input['date'];
    //     $times_begin = $input['time_begin'];
    //     $times_end = $input['time_end'];

    //     $event_date = new EventDate;

    //     // $coupon->lotes()->detach();

    //     DB::table('event_dates')->where('event_id', $id)->delete();

    //     $finalArray = array();
    //     for ($i = 0; $i < count($dates); $i++) {

    //         $dates[$i] = str_replace('/', '-', $dates[$i]);
    //         // dd(date('Y-m-d', strtotime($dates[$i])));
    //         array_push($finalArray, array(
    //             'date' => date('Y-m-d', strtotime($dates[$i])),
    //             'time_begin' => date('H:i', strtotime($times_begin[$i])),
    //             'time_end' => date('H:i', strtotime($times_end[$i])),
    //             'status' => 1,
    //             'event_id' => $id,
    //             'created_at' => date("Y-m-d H:i:s")
    //         ));
    //     }
    //     EventDate::insert($finalArray);       
                
    //     $owner = Owner::where('email', $input['owner_email'])->first();

    //     if(isset($input['status'])){
    //         $input['status'] = 1;
    //     }else{
    //         $input['status'] = 0;
    //     }

    //     $input['owner_id'] = $owner->id;

    //     $event->fill($input)->save();
    
    //     $place = Place::where('name', $request->place_name)->first();
    
    //     if($place) {

    //         $place->address = $request->address;
    //         $place->number = $request->number;
    //         $place->district = $request->district;
    //         $place->zip = $request->zip;
    //         $place->complement = $request->complement;
    //         $place->city_id = $request->city_id;
    //         $place->save();

    //     } else {

    //         $place = new Place;

    //         $place->name = $request->place_name;
    //         $place->address = $request->address;
    //         $place->number = $request->number;
    //         $place->district = $request->district;
    //         $place->zip = $request->zip;
    //         $place->complement = $request->complement;
    //         $place->city_id = $request->city_id;
    //         $place->status = 1;

    //         $place->save();
    //         $id_place = $place->id;

    //         Event::where('id', $event->id)->update(array('place_id' => $id_place));
    //     }

    //     if($request->file('banner')) {
    //         $fileName = time().'_'.$request->file('banner')->getClientOriginalName();
    //         $filePath = $request->file('banner')->storeAs('events', $fileName, 'public');

    //         if($event) {
    //             $event->banner = $filePath;
    //             $event->save();
    //         }
    //     }

    //     return redirect()->route('event.index');
    // }

    // public function destroy($id)
    // {
    //     $event = Event::findOrFail($id);

    //     $event->delete();
        
    //     return redirect()->route('event.index');
    // }

    // public function show($id){
                
    //     $event = Event::find($id);

    //     return view('event.show', compact('event'));
    // }

    public function check_slug(Request $request)
    {
        $slug = Str::slug($request->title, '-');

        $slug_exists = Event::where('slug', $slug)->count();

        return response()->json(['slug' => $slug, 'slug_exists' => $slug_exists]);
    }

    // public function create_slug(Request $request)
    // {
    //     $slug = $request->title;

    //     $slug_exists = Event::where('slug', $slug)->count();

    //     return response()->json(['slug' => $slug, 'slug_exists' => $slug_exists]);
    // }

    public function autocomplete_place(Request $request)
    {
        $data = Place::join('cities', 'cities.id', '=', 'places.city_id')
                    ->join('states', 'states.uf', '=', 'cities.uf')
                    ->where('places.name', 'LIKE', '%'. $request->get('search'). '%')
                    ->select("places.name as value", "places.id", "places.address", "places.number", "places.complement", "places.district", "places.zip", "places.city_id", "states.uf")
                    ->get();
    
        return response()->json($data);
    }

    // public function lotes($id){

    //     $event = Event::find($id);

    //     $lotes = Lote::orderBy('order')
    //             ->where('event_id', $id)
    //             ->get();

    //     return view('event.lotes', compact('event', 'lotes', 'id'));
    // }

    // public function delete_file(Request $request, $id)
    // {
    //     $event = Event::findOrFail($id);

    //     // $request->file->delete(public_path('events'), $event->banner);

    //     $path = public_path()."/storage/".$event->banner;

    //     unlink($path);

    //     if($event) {
    //         $event->banner = '';
    //         $event->save();
    //     }

    //     return back()
    //         ->with('success','Arquivo removido com sucesso!');
    // }

    // public function participantes_edit($id){
                
    //     $participanteLote = DB::table('participantes_lotes')
    //                         ->join('participantes', 'participantes_lotes.participante_id', '=', 'participantes.id')
    //                         ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
    //                         ->join('events', 'events.id', '=', 'lotes.event_id')
    //                         ->where('participantes_lotes.id', $id)
    //                         ->select('events.id as event_id', 'participantes_lotes.id as participantes_lotes_id', 'participantes_lotes.number', 'participantes_lotes.status', 'participantes.id as participante_id', 'participantes.name as participante_name', 'participantes.email as participante_email', 'lotes.id as lote_id', 'lotes.name as lote_name')
    //                         ->first();
        
    //     $lotes = Lote::orderBy('order')
    //                         ->where('event_id', $participanteLote->event_id)
    //                         ->get();

    //     $event = Event::findOrFail($participanteLote->event_id);

    //     // dd($participanteLote);

    //     return view('event.edit_inscricao', compact('participanteLote', 'lotes', 'event'));
    // }

    // public function participantes_update(Request $request, $id)
    // {

    //     $participanteLote_id = DB::table('participantes_lotes')
    //                         ->join('participantes', 'participantes_lotes.participante_id', '=', 'participantes.id')
    //                         ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
    //                         ->join('events', 'events.id', '=', 'lotes.event_id')
    //                         ->where('participantes_lotes.id', $id)
    //                         ->select('events.id as event_id', 'participantes_lotes.id as participantes_lotes_id', 'participantes_lotes.number', 'participantes_lotes.status', 'participantes.id as participante_id', 'participantes.name as participante_name', 'participantes.email as participante_email', 'lotes.id as lote_id', 'lotes.name as lote_name')
    //                         ->first();

    //     $participanteLote = ParticipanteLote::findOrFail($id);

    //     $input = $request->all();

    //     $participanteLote['status'] = $input['status'];
    //     $participanteLote['lote_id'] = $input['lote_id'];

    //     // dd($participanteLote);

    //     $participanteLote->save();
    
    //     return redirect()->route('event.reports', $participanteLote_id->event_id)->withFragment('#participantes_table');
    // }

}
