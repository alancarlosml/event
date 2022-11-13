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
use App\Models\Message;
use App\Models\Participante;
use App\Models\ParticipanteEvent;
use App\Models\Place;
use App\Models\Option;
use App\Models\Order;
use App\Models\Owner;
use App\Models\Question;
use App\Models\User;
use App\Models\State;
use Illuminate\Support\Facades\Auth;

class EventAdminController extends Controller
{

    public function myRegistrations(){

        $events = DB::table('orders')
            ->join('participantes_lotes', 'orders.participante_lote_id', '=', 'participantes_lotes.id')
            ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
            ->join('events', 'lotes.event_id', '=', 'events.id')
            ->select('events.*', 
                    'lotes.name as lote_name'
                )
            ->where('participantes.id', Auth::user()->id)
            ->where('participantes_events.status', 1)
            ->orderBy('events.*')
            ->get();

        return view('painel_admin.my_registrations', compact('events'));
    }

    public function myEvents(){
        
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
                            ) as x"),function($join){
                                $join->on("x.event_id","=","events.id");
            })
            ->select('events.*', 
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
            ->where('participantes.id', Auth::user()->id)
            ->where('participantes_events.status', 1)
            // ->where('participantes_events.role', 'admin')
            // ->select('events.*', 'places.name as place_name', 'users.name as owner_name', DB::raw('MIN(event_dates.date) as date_event_min'), DB::raw('MAX(event_dates.date) as date_event_max'))
            ->orderBy('events.name')
            ->groupBy('events.id')
            ->get();

        return view('painel_admin.my_events', compact('events'));
    }

    public function myEventsShow($hash){
                
        $event = Event::where('hash', $hash)->first();

        return view('painel_admin.my_events_show', compact('event'));
    }

    public function myEventsEdit(Request $request, $hash){
                
        $event = Event::where('hash', $hash)->first();
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

        $request->session()->put('dates', $dates);
        $request->session()->put('event', $event);
        $request->session()->put('event_id', $event->id);

        return view('painel_admin.my_events_edit', compact('event', 'categories', 'options', 'states', 'dates', 'questions'));
    }

    public function createEventLink(Request $request)
    {
        $request->session()->forget([
            'event', 
            'event_id', 
            'eventDate', 
            'dates', 
            'place', 
            'place_id', 
            'uf', 
            'lotes', 
            'lote_id'
        ]);

        return redirect()->route('event_home.create.step.one');
    }

    public function create_event(Request $request)
    {
        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();
        $options = Option::orderBy('id')->get();
        // $questions = Question::orderBy('order')->get();

        $event = $request->session()->get('event');
        $place = $request->session()->get('place');
        $eventDate = $request->session()->get('eventDate');

        if(isset($eventDate))
        {
            foreach($eventDate as $key => $date){
                $eventDate[$key]['date'] = date("d/m/Y", strtotime($eventDate[$key]['date']));
            }
        }
        
        $questions = "";
        if($event)
        {
            $questions = $request->session()->get('questions');
            // dd($questions);
        }

        return view('painel_admin.create_event', compact('categories', 'states', 'options', 'event', 'place', 'eventDate', 'questions'));
    }

    public function postCreateStepOne(Request $request)
    {
        /*********************************************/
        /*************** SAVE EVENTS *****************/
        /*********************************************/

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
                'admin_email' => 'required',
                'status' => 'string'
            ]);
           
            $validatedDataEvent['hash'] = md5($validatedDataEvent['name'] . $validatedDataEvent['description'] . md5('papainoel'));
            $validatedDataEvent['slug'] = Str::slug($validatedDataEvent['slug'], '-');
            $validatedDataEvent['status'] = 0;

            // dd($validatedDataEvent);
            
            $event = new Event();
            $event->fill($validatedDataEvent);
            $event->save();
            $event_id = $event->id;
            $request->session()->put('event', $event);
            $request->session()->put('event_id', $event_id);

        }else{

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
                'admin_email' => 'required',
                'status' => 'string',
                'new_field' => 'required'
            ]);
            
            $validatedDataEvent['slug'] = Str::slug($validatedDataEvent['slug'], '-');

            // if(isset($validatedDataEvent['status'])){
            //     $validatedDataEvent['status'] = 1;
            // }else{
            //     $validatedDataEvent['status'] = 0;
            // }

            $event->fill($validatedDataEvent);
            $request->session()->put('event', $event);
            $event->save();
        }

        /*********************************************/
        /*************** END SAVE EVENTS *************/
        /*********************************************/

        /*********************************************/
        /*************** SAVE PLACES *****************/
        /*********************************************/

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

        /*********************************************/
        /*************** END SAVE PLACES *************/
        /*********************************************/


        /*********************************************/
        /*************** SAVE DATES ******************/
        /*********************************************/

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

        DB::table('event_dates')->where('event_id', $event_id)->delete();
        EventDate::insert($finalArray);
        $request->session()->put('eventDate', $finalArray);

        /*********************************************/
        /*************** END SAVE DATES **************/
        /*********************************************/

        /*********************************************/
        /*************** SAVE QUESTIONS **************/
        /*********************************************/

        DB::table('questions')->where('event_id', $event_id)->delete();

        $fields = $validatedDataEvent['new_field'];

        foreach($fields as $id => $field){

            preg_match_all("/\(([^\]]*)\)/", $field, $matches);
            $result_field = $matches[1];
            $result_field = str_replace("Tipo: ", "", $result_field);

            $more_fields = explode(';', $field);

            $required = 0;
            if(strpos($field, 'Obrigatório')){
                $required = 1;
            }

            $unique = 0;
            if(strpos($field, 'Único')){
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
                'event_id' => $event_id
            ]);

            $id_question = $question->id;

            $result_regex = preg_match_all("/\[([^\]]*)\]/", $field, $matches);

            if($result_regex){
                $result_field = $matches[1];
                $result_field = str_replace("Opções: ", "", $result_field);

                $array_explode_question = explode(',', $result_field[0]);

                foreach($array_explode_question as $item_array_explode_question){
                    DB::table('option_values')->insert([
                        'value' => trim($item_array_explode_question),
                        'question_id' => $id_question
                    ]);
                }
            }
        }

        $questions = Question::orderBy('order')->where('event_id', $event_id)->get();

        $request->session()->put('questions', $questions);

        /*********************************************/
        /************* END SAVE QUESTIONS ************/
        /*********************************************/

        return redirect()->route('event_home.create.step.two');
    }

    public function createStepTwo(Request $request)
    {
        $event_id = $request->session()->get('event_id');

        if($event_id != null){

            if(empty($request->session()->get('lotes'))){
                $lotes = Lote::orderBy('order')
                    ->where('event_id', $event_id)
                    ->get();
            }else{
                $lotes = $request->session()->get('lotes');
            }

            return view('painel_admin.list_lotes', compact('lotes', 'event_id'));
        }

        return redirect()->route('home');
    }

    public function createLote()
    {
        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        return view('painel_admin.lote_create', compact('taxa_juros'));
    }

    public function storeLote(Request $request)
    {
        $input = $request->all();

        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        if($input['type'] == 0){
            $validatedData = $this->validate($request, [
                'type' => 'required|integer',
                'tax_parcelamento' => 'required|integer',
                'tax_service' => 'required|integer',
                'value' => 'required',
                'tax' => 'nullable',
                'final_value' => 'nullable',
                'form_pagamento' => 'required',
                'name' => 'required',
                'quantity' => 'required',
                'description' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gt:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'visibility' => 'required',
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
            $validatedData['tax'] = doubleval($validatedData['value']) * $taxa_juros;
            $validatedData['final_value'] = doubleval($validatedData['value']) - doubleval($validatedData['value']) * $taxa_juros;
        }

        $validatedData['hash'] = md5($validatedData['name'] . $validatedData['description'] . md5('papainoel'));

        $validatedData['form_pagamento'] = implode(",", $validatedData['form_pagamento']);

        $lote = new Lote();
        $lote->fill($validatedData);
        $lote->save();
        $lote_id = $lote->id;
        
        $lotes = Lote::orderBy('order')
                ->where('event_id', $event_id)
                ->get();

        $request->session()->put('lotes', $lotes);
        $request->session()->put('lote_id', $lote_id);

        return redirect()->route('event_home.create.step.two');
    }

    public function editLote($hash){
                
        $lote = Lote::where('hash', $hash)->first();

        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        $lote['datetime_begin'] = date('m/d/Y H:m', strtotime(str_replace('-', '/', $lote['datetime_begin'])));
        $lote['datetime_end'] = date('m/d/Y H:m', strtotime(str_replace('-', '/', $lote['datetime_end'])));

        return view('painel_admin.lote_edit', compact('lote', 'taxa_juros'));
    }

    public function updateLote(Request $request, $hash)
    {
        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        $lote = Lote::where('hash', $hash)->first();

        $input = $request->all();
        
        if($input['type'] == 0){
            $this->validate($request, [
                'type' => 'required|integer',
                'tax_parcelamento' => 'required|integer',
                'tax_service' => 'required|integer',
                'value' => 'required',
                'tax' => 'nullable',
                'final_value' => 'nullable',
                'form_pagamento' => 'required',
                'name' => 'required',
                'quantity' => 'required',
                'description' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gt:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'visibility' => 'required',
                'event_id' => 'nullable'
            ]);
        } else {

            $this->validate($request, [
                'type' => 'required|integer',
                'name' => 'required',
                'quantity' => 'required',
                'description' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gt:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'visibility' => 'required',
                'event_id' => 'nullable'
            ]);
        }

        $input['form_pagamento'] = implode(",", $input['form_pagamento']);

        $input['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_begin'])));
        $input['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_end'])));

        if($input['type'] == 0) {
            $input['tax'] = doubleval($input['value']) * $taxa_juros;
            $input['final_value'] = doubleval($input['value']) - doubleval($input['value']) * $taxa_juros;
        }

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $lote->fill($input)->save();

        return redirect()->route('event_home.create.step.two');
    }

    public function deleteLote($hash)
    {
        $lote = Lote::where('hash', $hash)->first();

        $lote->delete();
        
        return redirect()->route('event_home.create.step.two')->with('success', 'Lote deletado com sucesso!');   ;
    }

    public function save_lotes(Request $request, $hash)
    {
        $input = $request->all();

        if(isset($input['order_lote']))
        {
            foreach($input['order_lote'] as $order){
                
                $hash_order = explode('_', $order);
                $hashlote = $hash_order[0];
                $order = $hash_order[1];
                $lote = Lote::where('hash', $hashlote)->first();

                if($lote) {
                    $lote->order = $order;
                    $lote->save();
                }
            }

            return redirect()->route('event_home.create.step.three');
        } else {
            return redirect()->route('event_home.create.step.three');
        }
    }

    public function createStepThree(Request $request)
    {
        $event_id = $request->session()->get('event_id');
        
        if($event_id != null){
            
            $event = Event::findOrFail($event_id);
            $hash_event = $event->hash;

            if(empty($request->session()->get('coupons'))){
                $coupons = Coupon::where('event_id', $event_id)->orderBy('created_at')->get();
            }else{
                $lotes = $request->session()->get('coupons');
            }

            return view('painel_admin.list_coupons', compact('coupons', 'event_id', 'hash_event'));
        
        }else{

            return redirect()->route('home');
        }
    }

    public function createCoupon($hash)
    {
        $event = Event::where('hash', $hash)->first();

        $coupon_code = strtoupper(substr($event->name, 0, 2) . substr(sha1($event->id . date("Y/m/d h:i:s") . md5($event->name)), 0, 6));        

        $lotes = Lote::orderBy('order')
                ->where('event_id', $event->id)
                ->get();

        return view('painel_admin.create_coupon', compact('event', 'lotes', 'hash', 'coupon_code'));
    }
    
    public function storeCoupon(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();

        $this->validate($request, [
            'code' => 'required|unique:coupons',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'limit_buy' => 'required',
            'limit_tickets' => 'required'
        ]);

        $input = $request->all();

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $input['event_id'] = $event->id;
        $input['hash'] = md5($input['code'] . $input['event_id'] . md5('papainoel'));

        $id_coupon = Coupon::create($input)->id;

        $coupon_obj = Coupon::find($id_coupon);

        $lotes = $input['lotes'];

        foreach($lotes as $lote){
            
            $coupon_obj->lotes()->attach($lote);
        }

        $coupon_obj->fill($input)->save();

        return redirect()->route('event_home.create.step.three')->with('success', 'Cupom salvo com sucesso!');   ;
    }

    public function editCoupon($hash){

        $coupon = Coupon::where('hash', $hash)->first();

        $event = Event::find($coupon->event_id);

        $event_id = $event->id;

        $lotes = Lote::orderBy('order')
                ->where('event_id', $coupon->event_id)
                ->get();
        
        return view('painel_admin.coupon_edit', compact('event', 'coupon', 'lotes', 'hash', 'event_id'));
    }

    public function updateCoupon(Request $request, $hash)
    {
        $coupon = Coupon::where('hash', $hash)->first();

        $event = Event::find($coupon->event_id);

        $hash_event = $event->hash;

        $this->validate($request, [
            'code' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'limit_buy' => 'required',
            'limit_tickets' => 'required',
            'status' => 'nullable'
        ]);

        $input = $request->all();

        $input['event_id'] = $coupon->event_id;

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $lotes = $input['lotes'];

        $coupon->lotes()->detach();
        
        foreach($lotes as $lote){
            
            $coupon->lotes()->attach($lote);
        }
        
        $coupon->fill($input)->save();

        $event_id = $event->id;

        $hash_event = $event->hash;

        $coupons = Coupon::where('event_id', $coupon->event_id)->orderBy('created_at')->get();

        return view('painel_admin.list_coupons', compact('coupons', 'event_id', 'hash_event'));
    }

    public function destroyCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->delete();
        
        return redirect()->route('coupon.coupons')->with('success', 'Cupom removido com sucesso!');   ;
    }

    public function createStepFour(Request $request)
    {
        $event_id = $request->session()->get('event_id');

        if($event_id != null){
        
            $event = Event::findOrFail($event_id);
            $owner_id = $event->owner_id;
            $hash_event = $event->hash;

            return view('painel_admin.publish', compact('event', 'event_id', 'hash_event', 'owner_id'));
        
        }else{

            return redirect()->route('home');
        }
    }

    public function postCreateStepFour(Request $request, $hash)
    {
        $input = $request->all();

        $event = Event::where('hash', $hash)->first();
        $owner = Owner::where('id', $input['owner_id'])->first();
        
        if($event->banner){
            $validatedEvent = $request->validate([
                'theme' => 'required',
                'banner_option' => 'required',
                'status' => 'nullable'
            ]);
        }else{
            if($input['banner_option'] == 2){
                $validatedEvent = $request->validate([
                    'banner' => 'mimes:jpg,jpeg,bmp,png|max:2048',
                    'theme' => 'required',
                    'banner_option' => 'required',
                    'status' => 'nullable'
                ]);
            }else{
                $validatedEvent = $request->validate([
                    'theme' => 'required',
                    'banner_option' => 'required',
                    'status' => 'nullable'
                ]);

                $validatedEvent['banner'] = '';
            }
        }

        // dd($validatedEvent);

        if(isset($validatedEvent['status'])){
            $validatedEvent['status'] = 1;
        }else{
            $validatedEvent['status'] = 0;
        }

        if($request->file('banner')) {
            $fileName = time().'_'.$request->file('banner')->getClientOriginalName();
            $filePath = $request->file('banner')->storeAs('events', $fileName, 'public');

            if($event) {
                $validatedEvent['banner'] = $filePath;
                $event->save();
            }
        }

        $event->fill($validatedEvent);
        $event->save();

        if(isset($input['icon'])){
            $validatedOwner = $request->validate([
                'owner_name' => 'required',
                'description' => 'required',
                'status' => 'nullable'
            ]);
        }else{
            $validatedOwner = $request->validate([
                'icon' => 'mimes:jpg,jpeg,bmp,png|max:2048',
                'owner_name' => 'required',
                'description' => 'required',
                'status' => 'nullable'
            ]);
        }

        if($request->file('icon')) {
            $fileName = time().'_'.$request->file('icon')->getClientOriginalName();
            $filePath = $request->file('icon')->storeAs('owners', $fileName, 'public');
            $validatedOwner['icon'] = $filePath;
        }

        $validatedOwner['name'] = $validatedOwner['owner_name'];
        $validatedOwner['status'] = 1;

        $owner_id = "";
        if($owner){
            $owner->fill($validatedOwner);
            $owner->save();
            $owner_id = $owner->id;
        }else{
            $owner = new Owner();
            $owner->fill($validatedOwner);
            $owner->save();
            $owner_id = $owner->id;
        }
        
        Event::where('id', $event->id)->update(array('owner_id' => $owner_id));

        return redirect()->route('event_home.my_events')->with('success', 'Evento salvo com sucesso!');   ;
    }

    public function guests($hash)
    {
        $event = Event::where('hash', $hash)->first();
        
        $usuarios = Participante::orderBy('participantes_events.role')
                                        ->join('participantes_events', 'participantes_events.participante_id', '=', 'participantes.id')
                                        ->join('events', 'participantes_events.event_id', '=', 'events.id')
                                        ->select('participantes_events.id', 'participantes.name', 'participantes.email', 'participantes_events.role', 'participantes_events.status')
                                        ->where('events.hash', $hash)->get();

        // $lotes = Lote::orderBy('order')
        //         ->where('event_id', $event->id)
        //         ->get();

        return view('painel_admin.guests', compact('usuarios', 'event'));
    }

    public function addGuest(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();

        if($event != null){
            
            return view('painel_admin.guest_add', compact('event'));
        
        }else{

            return redirect()->route('home');
        }
    }

    public function storeGuest(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();

        $this->validate($request, [
            'email' => 'required'
        ]);

        $input = $request->all();

        $participante = Participante::where('email', $input['email'])->first();

        if($participante){
            if($participante->status == 0){
                //ENVIAR EMAIL SOLICITANDO REATIVAÇÃO DA CONTA
            }else{
                DB::table('participantes_events')->insert([
                    'hash' => md5($participante->name . date("Y-m-d H:i:s") . md5('papainoel')),
                    'role' => 'guest',
                    'status' => 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'participante_id' => $participante->id,
                    'event_id' => $event->id,
                ]);
            }
        }else{
            //ENVIAR EMAIL SOLICITANDO A CRIAÇÃO DA CONTA
        }

        return redirect()->route('event_home.guests', $event->hash)->with('success', 'Usuário convidado adicionado com sucesso!');   
    }

    public function editGuest($id)
    {
        $guest = Participante::join('participantes_events', 'participantes_events.participante_id', '=', 'participantes.id')
                                ->join('events', 'participantes_events.event_id', '=', 'events.id')
                                ->select('participantes_events.id', 'participantes.name', 'participantes.email', 'participantes_events.role', 'participantes_events.status')
                                ->where('participantes_events.id', $id)
                                ->first(); 

        // $guest = DB::table('participantes_events')->where('id', $id)->first();
        
        return view('painel_admin.guest_edit', compact('guest'));
    }

    public function updateGuest(Request $request, $id)
    {
        $input = $request->all();

        if(isset($input['status'])){
            $input['status'] = 1;
        }else{
            $input['status'] = 0;
        }

        $participanteEvent = ParticipanteEvent::where('id', $id)->update(array('status' => $input['status']));

        $participanteEventObj = ParticipanteEvent::where('id', $id)->first();

        $event = Event::where('id', $participanteEventObj->event_id)->first();

        return redirect()->route('event_home.guests', $event->hash)->with('success', 'Usuário convidado editado com sucesso!');   
    }

    public function destroyGuest($id)
    {
        $guest = ParticipanteEvent::findOrFail($id);

        $guest->delete();

        $event = Event::where('id', $guest->event_id)->first();
        
        return redirect()->route('event_home.guests', $event->hash)->with('success', 'Usuário convidado removido com sucesso!'); 
    }

    public function deleteFileEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // $request->file->delete(public_path('events'), $event->banner);

        $path = public_path()."/storage/".$event->banner;

        unlink($path);

        if($event) {
            $event->banner = '';
            $event->save();
        }

        return back()->with('success','Banner do evento removido com sucesso!');
    }

    public function deleteFileIcon(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);

        // $request->file->delete(public_path('events'), $event->banner);

        $path = public_path()."/storage/".$owner->icon;

        unlink($path);

        if($owner) {
            $owner->icon = '';
            $owner->save();
        }

        return back()->with('success','Banner do organizador removido com sucesso!');
    }

    public function contacts(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();

        $event_id = $event->id;

        $messages = Message::where('event_id', $event_id)->get();

        return view('painel_admin.contacts', compact('messages'));
    }

    public function reports(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();
        
        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        $resumo = DB::table('lotes')
                ->join('events', 'events.id', '=', 'lotes.event_id')
                ->leftJoin('participantes_lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                ->leftJoin('coupons_lotes', 'coupons_lotes.lote_id', '=', 'participantes_lotes.lote_id')
                ->leftJoin('coupons', 'coupons_lotes.coupon_id', '=', 'coupons.id')
                ->where('events.hash', $hash)
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
                ->join('events', 'events.id', '=', 'lotes.event_id')
                ->leftJoin('participantes_lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                ->leftJoin('coupons_lotes', 'coupons_lotes.lote_id', '=', 'participantes_lotes.lote_id')
                ->leftJoin('coupons', 'coupons_lotes.coupon_id', '=', 'coupons.id')
                ->where('events.hash', $hash)
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
                    ->join('events', 'events.id', '=', 'lotes.event_id')
                    ->leftJoin('coupons_lotes', 'coupons_lotes.lote_id', '=', 'participantes_lotes.lote_id')
                    ->leftJoin('coupons', 'coupons_lotes.coupon_id', '=', 'coupons.id')
                    ->where('events.hash', $hash)
                    ->select('lotes.name as lote_name', 'participantes_lotes.id', 'participantes_lotes.number as inscricao', 'participantes_lotes.status as situacao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
                    ->get();
        
        $situacao_participantes = Participante::orderBy('participantes.name')
                    ->join('participantes_lotes', 'participantes_lotes.participante_id', '=', 'participantes.id')
                    ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                    ->join('orders', 'participantes_lotes.participante_id', '=', 'orders.participante_id')
                    ->join('events', 'events.id', '=', 'lotes.event_id')
                    ->leftJoin('coupons_lotes', 'coupons_lotes.lote_id', '=', 'participantes_lotes.lote_id')
                    ->leftJoin('coupons', 'coupons_lotes.coupon_id', '=', 'coupons.id')
                    ->where('events.hash', $hash)
                    ->select('orders.gatway_status', 'lotes.value as lote_value','lotes.name as lote_name', 'participantes_lotes.id', 'participantes_lotes.number as inscricao', 'participantes_lotes.status as situacao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
                    ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 0 and coupons.code <> '' then lotes.value - (coupons.discount_value * lotes.value) else '' end as valor_porcentagem")
                    ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 1 and coupons.code <> '' then lotes.value - coupons.discount_value else '' end as valor_desconto")
                    ->get();

                    // dd($situacao_participantes);
        
        $payment_methods = Order::orderBy('orders.gatway_payment_method')
                    ->join('participantes_lotes', 'participantes_lotes.participante_id', '=', 'orders.participante_id')
                    ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                    ->join('events', 'events.id', '=', 'lotes.event_id')
                    ->where('events.hash', $hash)
                    ->select('orders.gatway_payment_method', DB::raw('count(*) as payment_methods_total'))
                    ->groupBy('orders.gatway_payment_method')
                    ->get();

        $situacao_coupons  = Coupon::orderBy('coupons.id')
                    ->join('coupons_lotes', 'coupons_lotes.coupon_id', '=', 'coupons.id')
                    ->join('participantes_lotes', 'participantes_lotes.lote_id', '=', 'coupons_lotes.lote_id')
                    ->join('lotes', 'participantes_lotes.lote_id', '=', 'lotes.id')
                    ->join('events', 'events.id', '=', 'lotes.event_id')
                    ->where('coupons.status', '1')
                    ->where('events.hash', $hash)
                    ->select('coupons.id', 'coupons.code','coupons.limit_buy', 'coupons.discount_type', 'coupons.discount_value',
                    DB::raw("COUNT(CASE WHEN participantes_lotes.status = 1 THEN 1 END) AS confirmado"),
                    DB::raw("COUNT(CASE WHEN participantes_lotes.status = 2 THEN 1 END) AS pendente"))
                    ->get();

        // dd($payment_methods);

        $participantes_json = response()->json($participantes);
        $payment_methods_json = response()->json($payment_methods);

        // dd($payment_methods_json);

        return view('painel_admin.reports', compact('event', 'lotes', 'resumo', 'participantes', 'participantes_json', 'config', 'situacao_participantes', 'payment_methods', 'payment_methods_json', 'situacao_coupons'));
    }
}
