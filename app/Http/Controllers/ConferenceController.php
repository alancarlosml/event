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
use App\Models\Option;
use App\Models\Order;
use App\Models\Owner;
use App\Models\User;
use App\Models\State;
use Illuminate\Support\Facades\Auth;

// require 'vendor/autoload.php';

use Moip\Moip;
use Moip\Auth\BasicAuth;

class ConferenceController extends Controller
{

    public function event(Request $request, $slug){

        // $menu = 'home';
        // $title = 'Home';
        // $url = url('/');
        // $description = 'Bilhete Mania - Venda de ingressos online';
        // $image = url('img/favicon/favicon-96x96.png');

        $event = Event::where('slug', $slug)->first();

        if($event)
        {
            // $coupon = $request->session()->get('coupon');
            // $subtotal = $request->session()->get('subtotal');
            // $coupon_subtotal = $request->session()->get('coupon_subtotal');
            // $total = $request->session()->get('total');

            $request->session()->forget('coupon');
            $request->session()->forget('subtotal');
            $request->session()->forget('coupon_subtotal');
            $request->session()->forget('total');
            $request->session()->forget('dict_lotes');

            // return view('site.event', compact('event', 'coupon', 'subtotal', 'coupon_subtotal', 'total'));
            return view('site.event', compact('event'));
        
        }else{

            $request->session()->forget('coupon');
            $request->session()->forget('subtotal');
            $request->session()->forget('coupon_subtotal');
            $request->session()->forget('total');
            $request->session()->forget('dict_lotes');

            return redirect()->back(); //view de evento não encontrado
        }
        
    }

    public function resume(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->first();
        $request->session()->put('event', $event);

        $questions = Question::orderBy('order')->where('event_id', $event->id)->get();

        $coupon = $request->session()->get('coupon');
        $subtotal = $request->session()->get('subtotal');
        $coupon_subtotal = $request->session()->get('coupon_subtotal');
        $total = $request->session()->get('total');
        $dict_lotes = $request->session()->get('dict_lotes');

        if($dict_lotes)
        {
            $array_lotes = [];
            foreach($dict_lotes as $dict){

                $quantity = $dict['lote_quantity'];
                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                if($quantity > 0){
                    if($lote->tax_servico == 0){
                        $array = array($quantity, ($lote->value + $lote->value*0.1), $lote->name);
                    }else{
                        $array = array($quantity, $lote->value, $lote->name);
                    }

                    array_push($array_lotes, $array);
                }
            }

            $request->session()->put('array_lotes', $array_lotes);

            return view('conference.resume', compact('event', 'questions', 'array_lotes', 'coupon', 'subtotal', 'coupon_subtotal', 'total'));
        
        }else{

            $request->session()->forget('coupon');
            $request->session()->forget('subtotal');
            $request->session()->forget('coupon_subtotal');
            $request->session()->forget('total');
            $request->session()->forget('dict_lotes');

            return redirect()->route('conference.index', $event->slug);
        }
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

    public function thanks(Request $request)
    {
        // dd(Auth::user()->id);
        $input = $request->all();

        // $id = 200;
        // dd($input['newfield_'.$id]);

        $input_name = '';
        $input_cpf = '';
        $input_email = '';
        foreach(array_keys($input) as $field){

            if(str_contains($field, 'newfield_')){
                $id = explode("_", $field);
                $id = $id[1];

                $question = Question::where('id', $id)->first();
                if($question->question == 'Nome'){
                    $input_name = $input['newfield_'.$id];
                }
                if($question->question == 'CPF'){
                    $input_cpf = $input['newfield_'.$id];
                    $input_cpf = str_replace('.','',$input_cpf);
                    $input_cpf = str_replace('-','',$input_cpf);
                }
                if($question->question == 'E-mail'){
                    $input_email = $input['newfield_'.$id];
                }
            }
        }

        $token = 'OQ2YC58HU5DSJMJUSDKNQAYR028QNCWT';
        $key = '9UUFJOFJPQRA3OZU36KL96CU5X9UXMBYRZYV446O';

        $moip = new Moip(new BasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);

        $event = $request->session()->get('event');
        $coupon = $request->session()->get('coupon');
        $subtotal = $request->session()->get('subtotal');
        $coupon_subtotal = $request->session()->get('coupon_subtotal');
        $total = $request->session()->get('total');
        $dict_lotes = $request->session()->get('dict_lotes');

        $event_details = $event->name;

        try {

            $customer = $moip->customers()->setOwnId(uniqid())
                ->setFullname($input['cc_name'])
                ->setEmail($input_email)
                ->setBirthDate('1988-12-30')
                ->setTaxDocument($input_cpf)
                ->setPhone(11, 66778899)
                ->addAddress('BILLING',
                    $input['cc_address'], $input['cc_number'],
                    $input['cc_district'], $input['cc_city'], $input['cc_state'],
                    $input['cc_zip'], $input['cc_address2'])
                ->create();

            $order = $moip->orders()->setOwnId(uniqid());

            foreach($dict_lotes as $dict){

                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                if($lote->tax_servico == 0){
                    $order->addItem($lote->name, 1, $lote->description, intval(($lote->value + $lote->value*0.1)*100));
                }else{
                    $order->addItem($lote->name, 1, $lote->description, intval($lote->value*100));
                }
            }

            $order->setShippingAmount(0)->setAddition(0)->setDiscount(intval($coupon_subtotal*100))
                ->setCustomer($customer)
                ->create();

            if($input['payment_form_check'] == 1){

                $mensagens = [
                    'cc_address.required' => 'O Endereço de cobrança é obrigatório!',
                    'cc_number.required' => 'O Número do Endereço é obrigatório!',
                    'cc_district.required' => 'O Bairro é obrigatório!',
                    'cc_state.required' => 'O Estado é obrigatório!',
                    'cc_city.required' => 'A Cidade é obrigatória!',
                    'cc_zip.required' => 'O CEP é obrigatório!',
                    'cc_name.required' => 'O Nome Impresso no Cartão é obrigatório!'
                ];

                $request->validate([
                    'cc_address' => 'required',
                    'cc_number' => 'required',
                    'cc_district' => 'required',
                    'cc_state' => 'required',
                    'cc_city' => 'required',
                    'cc_zip' => 'required',
                    'cc_name' => 'required'
                ], $mensagens);

                // $user = Customer::find(\Auth::user()->id);

                // $user->ddd = $request->ddd;
                // $user->phone = $request->phone;
                // $user->cc_address = $request->cc_address;
                // $user->cc_address2 = $request->cc_address2;
                // $user->cc_number = $request->cc_number;
                // $user->cc_district = $request->cc_district;
                // $user->cc_state = $request->cc_state;
                // $user->cc_city = $request->cc_city;
                // $user->cc_zip = $request->cc_zip;

                // $user->save();

                // $reference = md5((time() + 1) . $request->cpf);

                $payment_form = 'credit';
                $hash_cc = $input['encrypted_value'];    
                
                $holder = $moip->holders()->setFullname($input['cc_name'])
                        ->setBirthDate("1990-10-10")
                        ->setTaxDocument($input_cpf, 'CPF')
                        ->setPhone(11, 66778899, 55)
                        ->setAddress('BILLING',
                            $input['cc_address'], $input['cc_number'],
                            $input['cc_district'], $input['cc_city'], $input['cc_state'],
                            $input['cc_zip'], $input['cc_address2']);   

                $payment = $order->payments()
                    ->setCreditCardHash($hash_cc, $holder)
                    ->setInstallmentCount(1)
                    ->setStatementDescriptor('Pagamento ingresso: ' . $event_details)
                    ->execute();
    
            }elseif($input['payment_form_check'] == 2) {

                $payment_form = 'boleto';
                $logo_uri = 'https://cdn.moip.com.br/wp-content/uploads/2016/05/02163352/logo-moip.png';
                $expiration_date = (new \DateTime())->add(new \DateInterval('P3D'));
                $instruction_lines = ['INSTRUÇÃO 1', 'INSTRUÇÃO 2', 'INSTRUÇÃO 3'];

                // Creating payment to order
                $payment = $order->payments()
                    ->setBoleto($expiration_date, $logo_uri, $instruction_lines)
                    ->execute();
            }

            foreach($dict_lotes as $i => $dict){

                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                $participantes_lote_id = DB::table('participantes_lotes')->insertGetId([
                    'hash' => md5((time() + $i) . md5('papainoel')),
                    'number' => crc32((time() + $i) . md5('papainoel')),
                    'created_at' => now(),
                    'status' => 1,
                    // 'participante_id' => 1,
                    'participante_id' => Auth::user()->id,
                    'lote_id' => $lote->id
                ]);

                $order_id = DB::table('orders')->insertGetId([
                    'hash' => md5((time() + $i) . $participantes_lote_id),
                    'quantity' => 1,
                    'date_used' => null,
                    'gatway_hash' => md5($payment->getId()),
                    'gatway_reference' => $payment->getId(),
                    'gatway_status' => $payment->getStatus(),
                    'gatway_payment_method' => $payment_form,
                    'participante_lote_id' => $participantes_lote_id,
                    'created_at' => now()
                ]);

                if($input['payment_form_check'] == 2){

                    $boleto_detail_id = DB::table('boleto_details')->insertGetId([
                        'value' => $payment->getAmount(),
                        'href' => $payment->getHrefBoleto(),
                        'line_code' => $payment->getLineCodeBoleto(),
                        'href_print' => $payment->getHrefPrintBoleto(),
                        'order_id' => $order_id,
                        'created_at' => now()
                    ]);
                }

                if($coupon){
                    $inscricao_coupom_id = DB::table('inscricoes_coupons')->insertGetId([
                        'participante_lote_id' => $participantes_lote_id,
                        'coupon_id' => $coupon->id
                    ]);
                }

                foreach(array_keys($input) as $field){

                    if(str_contains($field, 'newfield_')){
                        $id = explode("_", $field);
                        $id = $id[1];

                        $question = Question::where('id', $id)->first();

                        if($input['newfield_'.$id] != ""){

                            $option_answer_id = DB::table('option_answers')->insertGetId([
                                'answer' => $input['newfield_'.$id],
                                'question_id' => $question->id,
                                'inscricao_id' => $participantes_lote_id,
                                'created_at' => now()
                            ]);
                        }
                    }
                }
            }

        } catch (\Moip\Exceptions\UnautorizedException $e) {
            echo $e->getMessage();
        } catch (\Moip\Exceptions\ValidationException $e) {
            printf($e->__toString());
        } catch (\Moip\Exceptions\UnexpectedException $e) {
            echo $e->getMessage();
        }
    }
}
