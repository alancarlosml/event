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

        return view('conference.payment', compact('event'));
    }

    public function thanks(Request $request)
    {

        $input = $request->all();

        MercadoPago\SDK::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN', ''));
        
        try {
            $payment = new MercadoPago\Payment();
            $payment->transaction_amount = (float)$_POST['transactionAmount'];
            $payment->token = $_POST['token'];
            $payment->description = $_POST['description'];
            $payment->installments = (int)$_POST['installments'];
            $payment->payment_method_id = $_POST['paymentMethodId'];
            $payment->issuer_id = (int)$_POST['issuer'];
            
            $payer = new MercadoPago\Payer();
            $payer->email = $_POST['email'];
            $payer->identification = array(
                "type" => $_POST['identificationType'],
                "number" => $_POST['identificationNumber']
            );
            $payment->payer = $payer;
            
            $payment->save();

            if($payment->id === null) {
                $error_message = 'Unknown error cause';
        
                if($payment->error !== null) {
                    $sdk_error_message = $payment->error->message;
                    $error_message = $sdk_error_message !== null ? $sdk_error_message : $error_message;
                }
        
                throw new Exception($error_message);
            }   
            
            $response = array(
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'id' => $payment->id
            );

            // echo json_encode($response);

            return $response;

        } catch(Exception $exception) {
            
            $response = array('error_message' => $exception->getMessage());
    
            return $response;
        }

        // $token = 'OQ2YC58HU5DSJMJUSDKNQAYR028QNCWT';
        // $key = '9UUFJOFJPQRA3OZU36KL96CU5X9UXMBYRZYV446O';

        // $moip = new Moip(new BasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);

        // $event = $request->session()->get('event');
        // $coupon = $request->session()->get('coupon');
        // $subtotal = $request->session()->get('subtotal');
        // $coupon_subtotal = $request->session()->get('coupon_subtotal');
        // $total = $request->session()->get('total');
        // $dict_lotes = $request->session()->get('dict_lotes');
        // $array_lotes_obj = $request->session()->get('array_lotes_obj');
        // $event_date_result = $request->session()->get('event_date_result');

        // $event_details = $event->name;

        // $name_info = "";
        // $email_info = "";
        // $cpf_info = "";
        // $date_born_info = "";
        // $phone_info = "";
        // $ddd = ""; 
        // $number_phone = "";
        // $address_info = "";
        // $number_info = "";
        // $district_info = "";
        // $state_info = "";
        // $city_info = "";
        // $zip_info = "";

        // if(isset($input['payment_form_check'])){

        //     if($input['payment_form_check'] == 1){

        //         $name_info = $input['cc_name_info'];
        //         $email_info = $input['cc_email_info'];
        //         $cpf_info = $input['cc_cpf_info'];
        //         $date_born_info = $input['cc_date_info'];
        //         $phone_info = $input['cc_phone_info'];
        //         $address_info = $input['cc_address'];
        //         $address2_info = $input['cc_address2'];
        //         $number_info = $input['cc_number'];
        //         $district_info = $input['cc_district'];
        //         $state_info = $input['cc_state'];
        //         $city_info = $input['cc_city'];
        //         $zip_info = $input['cc_zip'];

        //     }elseif($input['payment_form_check'] == 2){

        //         $name_info = $input['boleto_name_info'];
        //         $email_info = $input['boleto_email_info'];
        //         $cpf_info = $input['boleto_cpf_info'];
        //         $date_born_info = $input['boleto_date_info'];
        //         $phone_info = $input['boleto_phone_info'];
        //         $address_info = $input['boleto_address'];
        //         $address2_info = $input['boleto_address2'];
        //         $number_info = $input['boleto_number'];
        //         $district_info = $input['boleto_district'];
        //         $state_info = $input['boleto_state'];
        //         $city_info = $input['boleto_city'];
        //         $zip_info = $input['boleto_zip'];
        //     }

        //     if($cpf_info){
        //         $cpf_info = str_replace('.', '', $cpf_info);
        //         $cpf_info = str_replace('-', '', $cpf_info);
        //     } else {
        //         return back()->withErrors(['error' => 'Por favor, preencha o campo CPF']);
        //     }

        //     if($phone_info){
        //         $phone_info = explode(' ', $phone_info);
        //         $ddd = str_replace('(', '', $phone_info[0]);
        //         $ddd = str_replace(')', '', $ddd);

        //         $number_phone = str_replace('-', '', $phone_info[1]);
        //     } else {
        //         return back()->withErrors(['error' => 'Por favor, preencha o campo Telefone']);
        //     }

        // } else{
        //     return back()->withErrors(['error' => 'Por favor, selecione o tipo do pagamento']);
        // }

        // try {

        //     $customer = $moip->customers()->setOwnId(uniqid())
        //         ->setFullname($name_info)
        //         ->setEmail($email_info)
        //         ->setBirthDate($date_born_info)
        //         ->setTaxDocument($cpf_info)
        //         ->setPhone($ddd, $number_phone)
        //         ->addAddress('BILLING',
        //                 $address_info, $number_info,
        //                 $district_info, $city_info, $state_info,
        //                 $zip_info, $address2_info)
        //         ->create();

                
        //     $order = $moip->orders()->setOwnId(uniqid());
            
        //     foreach($array_lotes_obj as $array_obj){

        //         $lote = Lote::where('id', $array_obj['id'])->first();

        //         if($lote->tax_servico == 0){
        //             $order->addItem($lote->name, 1, $lote->description, intval(($lote->value + $lote->value*0.1)*100));
        //         }else{
        //             $order->addItem($lote->name, 1, $lote->description, intval($lote->value*100));
        //         }
        //     }

        //     $order->setShippingAmount(0)->setAddition(0)->setDiscount(intval($coupon_subtotal*100))
        //         ->setCustomer($customer)
        //         ->create();

        //     if($input['payment_form_check'] == 1){

        //         $mensagens = [
        //             'cc_name_info.required' => 'O Nome é obrigatório!',
        //             'cc_email_info.required' => 'O Email é obrigatório!',
        //             'cc_cpf_info.required' => 'O CPF é obrigatório!',
        //             'cc_date_info.required' => 'A Data de Nascimento é obrigatória!',
        //             'cc_phone_info.required' => 'O Telefone é obrigatório!',
        //             'cc_address.required' => 'O Endereço de cobrança é obrigatório!',
        //             'cc_number.required' => 'O Número do Endereço é obrigatório!',
        //             'cc_district.required' => 'O Bairro é obrigatório!',
        //             'cc_state.required' => 'O Estado é obrigatório!',
        //             'cc_city.required' => 'A Cidade é obrigatória!',
        //             'cc_zip.required' => 'O CEP é obrigatório!',
        //             'cc_name.required' => 'O Nome Impresso no Cartão é obrigatório!',
        //             'payment_form_check.required' => 'O Tipo do Pagamento é obrigatório!'
        //         ];

        //         $request->validate([
        //             'cc_name_info' => 'required',
        //             'cc_email_info' => 'required',
        //             'cc_cpf_info' => 'required',
        //             'cc_date_info' => 'required',
        //             'cc_phone_info' => 'required',
        //             'cc_address' => 'required',
        //             'cc_number' => 'required',
        //             'cc_district' => 'required',
        //             'cc_state' => 'required',
        //             'cc_city' => 'required',
        //             'cc_zip' => 'required',
        //             'cc_name' => 'required',
        //             'payment_form_check' => 'required'
        //         ], $mensagens);

        //         $payment_form = 'credit';
        //         $hash_cc = $input['encrypted_value'];    
                
        //         $holder = $moip->holders()->setFullname($name_info)
        //                 ->setBirthDate($date_born_info)
        //                 ->setTaxDocument($cpf_info, 'CPF')
        //                 ->setPhone($ddd, $number_phone, 55)
        //                 ->setAddress('BILLING',
        //                     $address_info, $number_info,
        //                     $district_info, $city_info, $state_info,
        //                     $zip_info, $address2_info);   

        //         $payment = $order->payments()
        //             ->setCreditCardHash($hash_cc, $holder)
        //             ->setInstallmentCount(1)
        //             ->setStatementDescriptor('Pagamento ingresso: ' . $event_details)
        //             ->execute();
    
        //     }elseif($input['payment_form_check'] == 2) {

        //         $mensagens = [
        //             'boleto_name_info.required' => 'O Nome é obrigatório!',
        //             'boleto_email_info.required' => 'O Email é obrigatório!',
        //             'boleto_cpf_info.required' => 'O CPF é obrigatório!',
        //             'boleto_date_info.required' => 'A Data de Nascimento é obrigatória!',
        //             'boleto_phone_info.required' => 'O Telefone é obrigatório!',
        //             'boleto_address.required' => 'O Endereço de cobrança é obrigatório!',
        //             'boleto_number.required' => 'O Número do Endereço é obrigatório!',
        //             'boleto_district.required' => 'O Bairro é obrigatório!',
        //             'boleto_state.required' => 'O Estado é obrigatório!',
        //             'boleto_city.required' => 'A Cidade é obrigatória!',
        //             'boleto_zip.required' => 'O CEP é obrigatório!',
        //         ];

        //         $request->validate([
        //             'boleto_name_info' => 'required',
        //             'boleto_email_info' => 'required',
        //             'boleto_cpf_info' => 'required',
        //             'boleto_date_info' => 'required',
        //             'boleto_phone_info' => 'required',
        //             'boleto_address' => 'required',
        //             'boleto_number' => 'required',
        //             'boleto_district' => 'required',
        //             'boleto_state' => 'required',
        //             'boleto_city' => 'required',
        //             'boleto_zip' => 'required',
        //         ], $mensagens);

        //         $payment_form = 'boleto';
        //         $logo_uri = 'https://cdn.moip.com.br/wp-content/uploads/2016/05/02163352/logo-moip.png';
        //         $expiration_date = (new \DateTime())->add(new \DateInterval('P3D'));
        //         $instruction_lines = ['INSTRUÇÃO 1', 'INSTRUÇÃO 2', 'INSTRUÇÃO 3'];

        //         dd($order);

        //         // Creating payment to order
        //         $payment = $order->payments()
        //             ->setBoleto($expiration_date, $logo_uri, $instruction_lines)
        //             ->execute();
        //     }

        //     $coupon_id = null;
        //     if($coupon){
        //         $coupon_id = $coupon->id;
        //     }

        //     $order_id = DB::table('orders')->insertGetId([
        //         'hash' => md5((time() . $payment->getId()) . md5('papainoel')),
        //         'quantity' => 1,
        //         'date_used' => null,
        //         'gatway_hash' => md5($payment->getId()),
        //         'gatway_reference' => $payment->getId(),
        //         'gatway_status' => $payment->getStatus(),
        //         'gatway_payment_method' => $payment_form,
        //         'event_date_id' => $event_date_result->id,
        //         'participante_id' => Auth::user()->id,
        //         'coupon_id' => $coupon_id,
        //         'created_at' => now()
        //     ]);

        //     if($input['payment_form_check'] == 2){

        //         $boleto_detail_id = DB::table('boleto_details')->insertGetId([
        //             'value' => $payment->getAmount()->total,
        //             'href' => $payment->getHrefBoleto(),
        //             'line_code' => $payment->getLineCodeBoleto(),
        //             'href_print' => $payment->getHrefPrintBoleto(),
        //             'order_id' => $order_id,
        //             'created_at' => now()
        //         ]);
        //     }

        //     foreach($dict_lotes as $i => $dict){

        //         $lote = Lote::where('hash', $dict['lote_hash'])->first();

        //         $order_item_id = DB::table('order_items')->insertGetId([
        //             'hash' => md5((time() + $i) . md5('papainoel')),
        //             'number' => crc32((time() + $i) . md5('papainoel')),
        //             'quantity' => 1,
        //             'value' => $lote->value,
        //             'order_id' => $order_id,
        //             'lote_id' => $lote->id,
        //             'created_at' => now()
        //         ]);

        //         foreach(array_keys($input) as $field){

        //             if(str_contains($field, 'newfield_')){
        //                 $id = explode("_", $field);
        //                 $id = $id[2];

        //                 $question = Question::where('id', $id)->first();

        //                 if($input['newfield_'. $k+1 . '_'. $id] != ""){

        //                     $option_answer_id = DB::table('option_answers')->insertGetId([
        //                         'answer' => $input['newfield_'. $k+1 . '_'. $id],
        //                         'question_id' => $question->id,
        //                         'order_item_id' => $order_item_id,
        //                         'created_at' => now()
        //                     ]);
        //                 }
        //             }
        //         }

        //     }





        //     // if($coupon){
        //     //     $inscricao_coupom_id = DB::table('orders_coupons')->insertGetId([
        //     //         'order_id' => $order_id,
        //     //         'coupon_id' => $coupon->id
        //     //     ]);
        //     // }

        //     // $array_participantes = [];
        //     // foreach($dict_lotes as $i => $dict){

        //     //     $lote = Lote::where('hash', $dict['lote_hash'])->first();

        //     //     $participantes_lote_id = DB::table('participantes_lotes')->insertGetId([
        //     //         'hash' => md5((time() + $i) . md5('papainoel')),
        //     //         'number' => crc32((time() + $i) . md5('papainoel')),
        //     //         'created_at' => now(),
        //     //         'status' => 1,
        //     //         'participante_id' => 1,
        //     //         // 'participante_id' => Auth::user()->id,
        //     //         'lote_id' => $lote->id
        //     //     ]);

        //     //     array_push($array_participantes, $participantes_lote_id);

        //     //     $order_item_id = DB::table('order_items')->insertGetId([
        //     //         'quantity' => 1,
        //     //         'value' => $lote->value,
        //     //         'order_id' => $order_id,
        //     //         'participante_lote_id' => $participantes_lote_id,
        //     //         'created_at' => now()
        //     //     ]);
        //     // }

        //     // foreach($array_participantes as $k => $participante_id){

        //     //     foreach(array_keys($input) as $field){

        //     //         if(str_contains($field, 'newfield_')){
        //     //             $id = explode("_", $field);
        //     //             $id = $id[2];

        //     //             $question = Question::where('id', $id)->first();

        //     //             if($input['newfield_'. $k+1 . '_'. $id] != ""){

        //     //                 $option_answer_id = DB::table('option_answers')->insertGetId([
        //     //                     'answer' => $input['newfield_'. $k+1 . '_'. $id],
        //     //                     'question_id' => $question->id,
        //     //                     'participante_lote_id' => $participante_id,
        //     //                     'order_id' => $order_id,
        //     //                     'created_at' => now()
        //     //                 ]);
        //     //             }
        //     //         }
        //     //     }
        //     // }

        // } catch (\Moip\Exceptions\UnautorizedException $e) {
        //     return back()->withErrors(['error' => 'Compra não autorizada.']);
        // } catch (\Moip\Exceptions\ValidationException $e) {
        //     return back()->withErrors(['error' => 'Compra não autorizada.']);
        // } catch (\Moip\Exceptions\UnexpectedException $e) {
        //     return back()->withErrors(['error' => 'Compra não autorizada.']);
        // }
    }
}
