<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
use App\Models\Question;
use App\Models\Message;
use App\Models\Order;
use App\Models\Owner;
use App\Models\User;
use App\Models\State;
use Illuminate\Support\Facades\Auth;


use Moip\Moip;
use Moip\Auth\BasicAuth;

use MercadoPago;

class ConferenceController extends Controller
{

    public function event(Request $request, $slug){

        // $menu = 'home';
        // $title = 'Home';
        // $url = url('/');
        // $description = 'Bilhete Mania - Venda de ingressos online';
        // $image = url('img/favicon/favicon-96x96.png');

        $event = Event::where('slug', $slug)->first();

        $request->session()->forget('coupon');
        $request->session()->forget('subtotal');
        $request->session()->forget('coupon_subtotal');
        $request->session()->forget('total');
        $request->session()->forget('dict_lotes');
        $request->session()->forget('event_date_result');

        if($event)
        {
            $total_dates = count($event->event_dates);
            $date_min = EventDate::select('id')->where('date', $event->min_event_dates())->first();
            // if($event->event_dates());
            // $coupon = $request->session()->get('coupon');
            // $subtotal = $request->session()->get('subtotal');
            // $coupon_subtotal = $request->session()->get('coupon_subtotal');
            // $total = $request->session()->get('total');

            // return view('site.event', compact('event', 'coupon', 'subtotal', 'coupon_subtotal', 'total'));
            return view('site.event', compact('event', 'total_dates', 'date_min'));
        
        }else{

            return redirect()->back(); //view de evento não encontrado
        }
    }

    public function send(Request $request, $hash){

        $event = Event::where('hash', $hash)->first();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|string',
            'subject' => 'required',
            'text' => 'required',
            'g-recaptcha-response' => 'required|recaptchav3:register,0.5'
        ]);

        $input = $request->all();

        $input['event_id'] = $event->id;
        $input['read'] = 0;

        $message = Message::create($input);

        return response()->json(['ok' => 'OK']);
    }

    public function resume(Request $request, $slug)
    {
        
        $coupon = $request->session()->get('coupon');
        $subtotal = $request->session()->get('subtotal');
        $coupon_subtotal = $request->session()->get('coupon_subtotal');
        $total = $request->session()->get('total');
        $dict_lotes = $request->session()->get('dict_lotes');
        $event_date_result = $request->session()->get('event_date_result');

        $event = Event::where('slug', $slug)->first();
        $request->session()->put('event', $event);
        $questions = Question::orderBy('order')->where('event_id', $event->id)->get();
        $eventDate = EventDate::where('id', $event_date_result)->first();

        if($dict_lotes)
        {
            $array_lotes = [];
            $array_lotes_obj = [];
            foreach($dict_lotes as $dict){

                $quantity = $dict['lote_quantity'];
                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                if($quantity > 0){
                    if($lote->tax_servico == 0){
                        $array = array('id' => $lote->id, 'quantity' => $quantity, 'value' => ($lote->value + $lote->value*0.1), 'name' => $lote->name);
                    }else{
                        $array = array('id' => $lote->id, 'quantity' => $quantity,  'value' => $lote->value, 'name' => $lote->name);
                    }

                    array_push($array_lotes, $array);
                }

                if($quantity > 0){
                    for($j = 0; $j < $quantity; $j++){
                        if($lote->tax_servico == 0){
                            $array_obj = array('id' => $lote->id, 'quantity' => 1, 'value' => ($lote->value + $lote->value*0.1), 'name' => $lote->name);
                        }else{
                            $array_obj = array('id' => $lote->id, 'quantity' => 1,  'value' => $lote->value, 'name' => $lote->name);
                        }
                        array_push($array_lotes_obj, $array_obj);
                    }
                }
            }

            $request->session()->put('event_date', $eventDate);
            $request->session()->put('array_lotes', $array_lotes);
            $request->session()->put('array_lotes_obj', $array_lotes_obj);

            return view('conference.resume', compact('event', 'questions', 'array_lotes', 'array_lotes_obj', 'coupon', 'subtotal', 'coupon_subtotal', 'total', 'eventDate'));
        
        }else{

            $request->session()->forget('coupon');
            $request->session()->forget('subtotal');
            $request->session()->forget('coupon_subtotal');
            $request->session()->forget('total');
            $request->session()->forget('dict_lotes');
            $request->session()->forget('event_date_result');

            return redirect()->route('conference.index', $event->slug);
        }
    }

    public function setEventDate(Request $request){

        $data = $request->all();

        $event_date_result = $data['event_date_result'];

        $request->session()->put('event_date_result', $event_date_result);
        
        if($event_date_result){

            return response()->json(['success'=>'Ajax request submitted successfully']);
        
        }else{

            return redirect()->back();
        }
        
        return redirect()->back();
    }

    public function getSubTotal(Request $request){

        $data = $request->all();

        $dicts = $data['dict'];

        // $subtotal = 0;
        // $coupon_subtotal = 0;
        // $total = 0;

        $subtotal = 0;
        $coupon_subtotal = 0;
        $total = 0;

        $request->session()->put('dict_lotes', $dicts);
        
        if($dicts){
            foreach($dicts as $dict){

                $quantity = $dict['lote_quantity'];
                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                if($lote->type == 0){

                    if($lote->tax_servico == 0){
                        $subtotal += ($lote->value + $lote->value*0.1) * $quantity;
                    }else{
                        $subtotal += $lote->value * $quantity;
                    }
                    
                    $coupon = $request->session()->get('coupon');

                    if($coupon){
                        $couponBelongs = false;
                        foreach($lote->coupons as $lote_cupom){
                            if($coupon[0]['code'] == $lote_cupom->code){
                                $couponBelongs = true;
                            }
                        }

                        if($couponBelongs){

                            $coupon_code = $coupon[0]['code'];
                            $coupon_type = $coupon[0]['type'];
                            $coupon_value = $coupon[0]['value'];

                            if($coupon_type == 0){
                                $coupon_subtotal = $subtotal*$coupon_value;
                            }else if($coupon_type == 1){
                                $coupon_subtotal = $coupon_value;
                            }

                            $total = $subtotal - $coupon_subtotal;
                        }

                    }else{
                        $total = $subtotal;
                    }
                }
            }

            $request->session()->put('subtotal', $subtotal);
            $request->session()->put('coupon_subtotal', $coupon_subtotal);
            $request->session()->put('total', $total);

            return response()->json(['success'=>'Ajax request submitted successfully', 'subtotal' => 'R$ '.number_format($subtotal, 2, ',', '.'), 'coupon_subtotal' => 'R$ '.number_format($coupon_subtotal, 2, ',', '.'), 'total' => 'R$ '.number_format($total, 2, ',', '.')]);
        
        }else{

            return redirect()->back();
        }
        
        return redirect()->back();
    }

    public function getCoupon(Request $request){

        $data = $request->all();

        $eventHash = $data['eventHash'];
        $couponCode = $data['couponCode'];

        // $evento = Ticket::where('hash_id', $hashIdTicket)->first()->sector()->first()->date()->first()->event()->first();
        $evento = Event::where('hash', $eventHash)->first();

        $coupon = Coupon::where('code', $couponCode)->where('status', '1')->where('event_id', $evento->id)->first();
        $coupon_session = $request->session()->get('coupon');

        if($coupon_session){
            return response()->json(['alert'=>'Cupom já aplicado!']);
        }else{
            if($coupon != null){

                $subtotal = $request->session()->get('subtotal');

                if($coupon->discount_type == 0){
                    $coupon_discount = $subtotal*$coupon->discount_value;
                }else if($coupon->discount_type == 1){
                    $coupon_discount = $coupon->discount_value;
                }

                $coupon = array(array('code' => $coupon->code, 'type' => $coupon->discount_type, 'value' => $coupon->discount_value));
                $total = $subtotal - $coupon_discount;

                $request->session()->put('coupon', $coupon);
                $request->session()->put('coupon_discount', $coupon_discount);
                $request->session()->put('total', $total);

                return response()->json(['success'=>'Cupom adicionado com sucesso!', 'coupon' => $coupon, 'coupon_discount' => $coupon_discount, 'total' => $total]);
            
            }else{

                return response()->json(['error'=>'Cupom inválido.']);
            }
        }
    }

    public function removeCoupon(Request $request, $slug){

        $request->session()->forget('coupon');
        $subtotal = $request->session()->get('subtotal');

        return response()->json(['success'=>'Cupom removido com sucesso.', 'subtotal' => $subtotal]);
    }

    public function payment(Request $request)
    {
        $input = $request->all();

        $event = $request->session()->get('event');
        $coupon = $request->session()->get('coupon');
        $subtotal = $request->session()->get('subtotal');
        $coupon_subtotal = $request->session()->get('coupon_subtotal');
        $total = $request->session()->get('total');
        $dict_lotes = $request->session()->get('dict_lotes');
        $array_lotes_obj = $request->session()->get('array_lotes_obj');
        $event_date = $request->session()->get('event_date');

        $coupon_id = null;
        if($coupon){
            $coupon_id = $coupon->id;
        }

        $order_id = DB::table('orders')->insertGetId([
            'hash' => md5(time() . uniqid() . md5('papainoel')),
            'status' => 2,
            'gatway_hash' => null,
            'gatway_reference' => null,
            'gatway_status' => null,
            'gatway_payment_method' => null,
            'event_date_id' => $event_date->id,
            'participante_id' => Auth::user()->id,
            'coupon_id' => $coupon_id,
            'created_at' => now()
        ]);

        $request->session()->put('order_id', $order_id);

        foreach($dict_lotes as $i => $dict){

            $lote = Lote::where('hash', $dict['lote_hash'])->first();

            $order_item_id = DB::table('order_items')->insertGetId([
                'hash' => md5((time() . uniqid() . $i) . md5('papainoel')),
                'number' => intval(crc32(md5(time() . uniqid() . $i) . md5('papainoel')), 36),
                'quantity' => 1,
                'value' => $lote->value,
                'date_use' => null,
                'status' => 2,
                'order_id' => $order_id,
                'lote_id' => $lote->id,
                'created_at' => now()
            ]);
            
            foreach(array_keys($input) as $field){

                if(str_contains($field, 'newfield_')){
                    $id = explode("_", $field);
                    $k = $id[1];
                    $id = $id[2];

                    $question = Question::where('id', $id)->first();

                    if($input['newfield_'. $k . '_'. $id] != ""){

                        $option_answer_id = DB::table('option_answers')->insertGetId([
                            'answer' => $input['newfield_'. $k . '_'. $id],
                            'question_id' => $question->id,
                            'order_item_id' => $order_item_id,
                            'created_at' => now()
                        ]);
                    }
                }
            }
        }

        // return view('conference.payment', compact('event', 'order_id', 'total'));
        return redirect()->route('conference.payment', $event->slug);
    }

    public function paymentView(Request $request)
    {
        $event = $request->session()->get('event');
        $total = $request->session()->get('total');

        return view('conference.payment', compact('event', 'total'));
    }

    public function thanks(Request $request)
    {

        // echo $request->getContent();
        // dd($request->getContent());
        // return $request;
        
        $input = json_decode($request->getContent());
        // var_dump($input);
        // dd($input);
        // return $input;

        MercadoPago\SDK::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN', ''));

        $order_id = $request->session()->get('order_id');
        $event = $request->session()->get('event');
        $total = $request->session()->get('total');

        $order = Order::findOrFail($order_id);

        $first_name = Str::of(Auth::user()->name)->explode(' ')[0];
        $tmp_explode = Str::of(Auth::user()->name)->explode(' ');
        $last_name = end($tmp_explode);
        
        try {

            if($input->paymentType == 'credit_card') {

                $payment = new MercadoPago\Payment();
                $payment->transaction_amount = (float)$total;
                $payment->token = $input->formData->token;
                $payment->description = "Ingresso Ticket DZ6: " . $event->name;
                $payment->installments = (int)$input->formData->installments;
                $payment->payment_method_id = $input->formData->payment_method_id;
                $payment->issuer_id = (int)$input->formData->issuer_id;
                // $payment->notification_url = 'http://requestbin.fullcontact.com/1ogudgk1';
                
                $payer = new MercadoPago\Payer();
                $payer->email = $input->formData->payer->email;
                $payer->first_name = $first_name;
                $payer->last_name = $last_name;
                $payer->identification = array(
                    "type" => $input->formData->payer->identification->type,
                    "number" => $input->formData->payer->identification->type
                );

                $payment->payer = $payer;
            
            } elseif($input->paymentType == 'bank_transfer'){

                $payment = new MercadoPago\Payment();
                $payment->transaction_amount = (float)$total;
                $payment->description = 'Ingresso Ticket DZ6: ' . $event->name;
                $payment->payment_method_id = $input->formData->payment_method_id;
                $payment->payer = array(
                    "email" => $input->formData->payer->email,
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "identification" => array(
                        "type" => "CPF",
                        "number" => Auth::user()->cpf
                    ),
                    // "address"=>  array(
                    //     "zip_code" => "06233200",
                    //     "street_name" => "Av. das Nações Unidas",
                    //     "street_number" => "3003",
                    //     "neighborhood" => "Bonfim",
                    //     "city" => "Osasco",
                    //     "federal_unit" => "SP"
                    // )
                );

                // var_dump($payment);
                // dd($payment);

            } elseif($input->paymentType == 'ticket') { // Boleto

                $payment = new MercadoPago\Payment();
                $payment->transaction_amount = (float) $total;
                $payment->description = 'Ingresso Ticket DZ6: ' . $event->name;
                $payment->payment_method_id = $input->formData->payment_method_id;
                $payment->payer = array(
                    "email" => $input->formData->payer->email,
                    "first_name" => $input->formData->payer->first_name,
                    "last_name" => $input->formData->payer->last_name,
                    "identification" => array(
                        "type" => $input->formData->payer->identification->type,
                        "number" => $input->formData->payer->identification->number
                    ),
                    "address"=>  array(
                        "zip_code" => $input->formData->payer->address->zip_code,
                        "street_name" => $input->formData->payer->address->street_name,
                        "street_number" => $input->formData->payer->address->street_number,
                        "neighborhood" => $input->formData->payer->address->neighborhood,
                        "city" => $input->formData->payer->address->city,
                        "federal_unit" => $input->formData->payer->address->federal_unit,
                    )
                );
            }
            
            if ($payment->save()){
                // Sucesso

                $responseArray = $payment->toArray();
                $result = json_encode($responseArray);

                $output = json_decode($result);

                // var_dump($output);
                // dd($output);

                DB::table('orders')
                    ->where('id', $order_id)
                    ->update([
                            'status' => 1, 
                            'gatway_hash' => $output->id, 
                            'gatway_status' => $output->status, 
                            'gatway_payment_method' => $output->payment_type_id, 
                            'gatway_date_status' => $output->date_created
                        ]);

                if($input->paymentType == 'credit_card') {

                    $total_amount_tax = 0;

                    $total_amount_tax = $total_amount_tax + ((float)$output->transaction_details->total_paid_amount - (float)$output->transaction_details->net_received_amount);

                    // SALVAR INFORMACOES DA TABELA CREDIT_CARD_DETAILS
                    $credit_detail_id = DB::table('credit_details')->insertGetId([
                        'token' => $output->token,
                        'installments' => $output->installments,
                        'value' => $output->transaction_details->total_paid_amount,
                        'installment_amount' => $output->transaction_details->installment_amount,
                        'total_paid_amount' => $output->transaction_details->total_paid_amount,
                        'net_received_amount' => $output->transaction_details->net_received_amount,
                        'total_amount_tax' => $total_amount_tax,
                        'payment_method_id' => $output->payment_method_id,
                        'order_id' => $order_id,
                        'created_at' => now()
                    ]);
                    // MANDAR EMAIL COM COMPRA REALIZADA COM SUCESSO
                    
                } elseif($input->paymentType == 'bank_transfer') {

                    // SALVAR INFORMACOES DA TABELA PIX_DETAILS
                    $pix_detail_id = DB::table('pix_details')->insertGetId([
                        'value' => $output->transaction_details->total_paid_amount,
                        'qr_code' => $output->point_of_interaction->transaction_data->qr_code,
                        'qr_code_base64' => $output->point_of_interaction->transaction_data->qr_code_base64,
                        'ticket_url' => $output->point_of_interaction->transaction_data->ticket_url,
                        'expiration_date' => $output->date_of_expiration,
                        'order_id' => $order_id,
                        'created_at' => now()
                    ]);
                    // MANDAR EMAIL COM INFORMAÇÕES DA COMPRA PENDENTE E DETALHES DA CHAVE PIX
                    
                } elseif($input->paymentType == 'ticket') {
                    
                    // SALVAR INFORMACOES DA TABELA BOLETO_DETAILS
                    $boleto_detail_id = DB::table('boleto_details')->insertGetId([
                        'value' => $output->transaction_details->total_paid_amount,
                        'href' => $output->transaction_details->external_resource_url,
                        'line_code' => $output->barcode->content,
                        'expiration_date' => $output->date_of_expiration,
                        'order_id' => $order_id,
                        'created_at' => now()
                    ]);
                    // MANDAR EMAIL COM INFORMAÇÕES DA COMPRA PENDENTE E DETALHES DO BOLETO

                }

                return $output;
              
              }else {
                //Falha
                $errorArray = (array) $payment->error;
                $result =  json_encode($errorArray);

                $output = json_decode($result);

                // var_dump($output);
                // dd($output);

                date_default_timezone_set("America/Fortaleza");
                $curr_date = date("Y-m-d H:i:s");

                if($input->paymentType == 'credit_card') {

                    DB::table('orders')
                    ->where('id', $order_id)
                    ->update([
                            'status' => 3, 
                            'gatway_status' => $output->status, 
                            'gatway_payment_method' => 'credit_card', 
                            'gatway_date_status' => $curr_date,
                            'gatway_description' => $output->message
                        ]);

                    // MANDAR EMAIL COM COMPRA NÃO REALIZADA
                    
                } elseif($input->paymentType == 'bank_transfer') {

                    // MANDAR EMAIL COM COMPRA NÃO REALIZADA
                    
                } elseif($input->paymentType == 'ticket') {

                    // MANDAR EMAIL COM COMPRA NÃO REALIZADA
                }
                
                return $result;
              }

        } catch(Exception $exception) {

            return $exception;
        }

    }
}
