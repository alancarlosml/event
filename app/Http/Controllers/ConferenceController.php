<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\OrderMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Lote;
use App\Models\Question;
use App\Models\Message;
use App\Models\OptionAnswer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use MercadoPago;

class ConferenceController extends Controller
{
    public function event(Request $request, $slug)
    {

        $event = Event::where('slug', $slug)->first();

        $request->session()->forget('coupon');
        $request->session()->forget('subtotal');
        $request->session()->forget('coupon_subtotal');
        $request->session()->forget('total');
        $request->session()->forget('dict_lotes');
        $request->session()->forget('event_date_result');

        if($event) {
            $total_dates = count($event->event_dates);
            $date_min = EventDate::select('id')->where('date', $event->min_event_dates())->first();
 
            return view('site.event', compact('event', 'slug', 'total_dates', 'date_min'));

        } else {

            return redirect()->back(); //view de evento não encontrado
        }
    }

    public function send(Request $request, $hash)
    {

        $event = Event::where('hash', $hash)->first();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|string',
            'subject' => 'required',
            'text' => 'required',
            'g-recaptcha-response' => 'required|recaptchav3:register,0.5',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'subject.required' => 'O campo assunto é obrigatório.',
            'text.required' => 'O campo mensagem é obrigatório.',
            'g-recaptcha-response.required' => 'O campo captcha é obrigatório.',
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

        if($dict_lotes) {
            $array_lotes = [];
            $array_lotes_obj = [];
            foreach($dict_lotes as $dict) {

                $quantity = $dict['lote_quantity'];
                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                if($quantity > 0) {
                    if($lote->tax_service == 0) {
                        $array = ['id' => $lote->id, 'quantity' => $quantity, 'value' => ($lote->value + $lote->value * 0.1), 'name' => $lote->name];
                    } else {
                        $array = ['id' => $lote->id, 'quantity' => $quantity,  'value' => $lote->value, 'name' => $lote->name];
                    }

                    array_push($array_lotes, $array);
                }

                if($quantity > 0) {
                    for($j = 0; $j < $quantity; $j++) {
                        if($lote->tax_service == 0) {
                            $array_obj = ['id' => $lote->id, 'quantity' => 1, 'value' => ($lote->value + $lote->value * 0.1), 'name' => $lote->name];
                        } else {
                            $array_obj = ['id' => $lote->id, 'quantity' => 1,  'value' => $lote->value, 'name' => $lote->name];
                        }
                        array_push($array_lotes_obj, $array_obj);
                    }
                }
            }

            $request->session()->put('event_date', $eventDate);
            $request->session()->put('array_lotes', $array_lotes);
            $request->session()->put('array_lotes_obj', $array_lotes_obj);

            return view('conference.resume', compact('event', 'questions', 'array_lotes', 'array_lotes_obj', 'coupon', 'subtotal', 'coupon_subtotal', 'total', 'eventDate'));

        } else {

            $request->session()->forget('coupon');
            $request->session()->forget('subtotal');
            $request->session()->forget('coupon_subtotal');
            $request->session()->forget('total');
            $request->session()->forget('dict_lotes');
            $request->session()->forget('event_date_result');

            return redirect()->route('conference.index', $event->slug);
        }
    }

    public function setEventDate(Request $request)
    {

        $data = $request->all();

        $event_date_result = $data['event_date_result'];

        $request->session()->put('event_date_result', $event_date_result);

        if($event_date_result) {

            return response()->json(['success' => 'Ajax request submitted successfully']);

        } else {

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function getSubTotal(Request $request)
    {

        $data = $request->all();

        $dicts = $data['dict'];

        $subtotal = 0;
        $coupon_subtotal = 0;
        $total = 0;

        $request->session()->put('dict_lotes', $dicts);

        if($dicts) {
            foreach($dicts as $dict) {

                $quantity = $dict['lote_quantity'];
                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                if($lote->type == 0) {

                    if($lote->tax_service == 0) {
                        $valor_calculado = ($lote->value + $lote->value * 0.1) * $quantity;
                        $subtotal += $valor_calculado;
                    } else {
                        $valor_calculado = $lote->value * $quantity;
                        $subtotal += $valor_calculado;
                    }

                    $coupon = $request->session()->get('coupon');

                    if($coupon) {
                        $couponBelongs = false;
                        foreach($lote->coupons as $lote_cupom) {
                            if($coupon[0]['code'] == $lote_cupom->code) {
                                $couponBelongs = true;
                            }
                        }

                        if($couponBelongs) {

                            $coupon_code = $coupon[0]['code'];
                            $coupon_type = $coupon[0]['type'];
                            $coupon_value = $coupon[0]['value'];

                            if($coupon_type == 0) {
                                $coupon_subtotal = $subtotal * $coupon_value;
                            } elseif($coupon_type == 1) {
                                $coupon_subtotal = $coupon_value;
                            }

                            $total = $subtotal - $coupon_subtotal;
                        }

                    } else {
                        $total = $subtotal;
                    }
                }
            }

            $request->session()->put('subtotal', $subtotal);
            $request->session()->put('coupon_subtotal', $coupon_subtotal);
            $request->session()->put('total', $total);

            return response()->json(['success' => 'Ajax request submitted successfully', 'subtotal' => 'R$ '.number_format($subtotal, 2, ',', '.'), 'coupon_subtotal' => 'R$ '.number_format($coupon_subtotal, 2, ',', '.'), 'total' => 'R$ '.number_format($total, 2, ',', '.')]);

        } else {

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function getCoupon(Request $request)
    {

        $data = $request->all();

        $eventHash = $data['eventHash'];
        $couponCode = $data['couponCode'];

        $evento = Event::where('hash', $eventHash)->first();

        // $coupon = Coupon::where('code', $couponCode)->where('status', '1')->where('event_id', $evento->id)->first();

        // VERIFICA O LIMIT_BUY PARA O CUPOM QUE ESTÁ SENDO ADICIONADO
        $coupon = Coupon::select('coupons.*')
                    ->selectSub(function ($query) {
                        $query->selectRaw('count(*)')
                            ->from('orders_coupons')
                            ->whereColumn('coupons.id', 'orders_coupons.coupon_id');
                    }, 'usage_count')
                    ->where('code', $couponCode)
                    ->where('status', '1')
                    ->where('event_id', $evento->id)
                    ->havingRaw('usage_count < limit_buy OR limit_buy IS NULL')
                    ->first();

        $coupon_session = $request->session()->get('coupon');

        if($coupon_session) {
            return response()->json(['alert' => 'Cupom já aplicado!']);
        } else {
            if($coupon != null) {

                $subtotal = $request->session()->get('subtotal');

                if($coupon->discount_type == 0) {
                    $coupon_discount = $subtotal * $coupon->discount_value;
                } elseif($coupon->discount_type == 1) {
                    $coupon_discount = $coupon->discount_value;
                }

                $coupon = [['code' => $coupon->code, 'type' => $coupon->discount_type, 'value' => $coupon->discount_value]];
                $total = $subtotal - $coupon_discount;

                $request->session()->put('coupon', $coupon);
                $request->session()->put('coupon_discount', $coupon_discount);
                $request->session()->put('total', $total);

                return response()->json(['success' => 'Cupom adicionado com sucesso!', 'coupon' => $coupon, 'coupon_discount' => $coupon_discount, 'total' => $total]);

            } else {

                return response()->json(['error' => 'Cupom inválido ou Indisponível.']);
            }
        }
    }

    public function removeCoupon(Request $request, $slug)
    {

        $request->session()->forget('coupon');
        $subtotal = $request->session()->get('subtotal');

        return response()->json(['success' => 'Cupom removido com sucesso.', 'subtotal' => $subtotal]);
    }

    // public function payment(Request $request)
    // {
    //     $coupon = $request->session()->get('coupon');

    //     $order = Order::create([
    //         'hash' => md5(time() . uniqid() . md5('papainoel')),
    //         'status' => 2,
    //         'event_date_id' => $request->session()->get('event_date')->id,
    //         'participante_id' => Auth::user()->id,
    //         'coupon_id' => $coupon ? $coupon->id : null,
    //     ]);

    //     $request->session()->put('order_id', $order->id);

    //     $this->createOrderItems($order, $request->all());

    //     return redirect()->route('conference.payment', $request->session()->get('event')->slug);
    // }

    // private function createOrderItems(Order $order, array $input)
    // {
    //     $dict_lotes = session('dict_lotes');

    //     foreach($dict_lotes as $i => $dict) {
    //         $lote = Lote::where('hash', $dict['lote_hash'])->first();

    //         $orderItem = $order->items()->create([
    //             'hash' => md5((time() . uniqid() . $i) . md5('papainoel')),
    //             'number' => intval(crc32(md5(time() . uniqid() . $i) . md5('papainoel')), 36),
    //             'quantity' => 1,
    //             'value' => $lote->value,
    //             'status' => 2,
    //             'lote_id' => $lote->id,
    //         ]);

    //         $this->createOptionAnswers($orderItem, $input);
    //     }
    // }

    // private function createOptionAnswers(OrderItem $orderItem, array $input)
    // {
    //     foreach(array_keys($input) as $field) {
    //         if(!str_contains($field, 'newfield_')) continue;

    //         list(, $k, $id) = explode('_', $field);
    //         $question = Question::find($id);
    //         $answer = $input['newfield_' . $k . '_' . $id];

    //         if ($answer) {
    //             OptionAnswer::create([
    //                 'answer' => $answer,
    //                 'question_id' => $question->id,
    //                 'order_item_id' => $orderItem->id,
    //             ]);
    //         }
    //     }
    // }

    public function payment(Request $request)
    {
        $input = $request->all();

        $event = $request->session()->get('event');
        $coupon = $request->session()->get('coupon');
        $subtotal = $request->session()->get('subtotal');
        $coupon_subtotal = $request->session()->get('coupon_subtotal');
        $total = $request->session()->get('total');
        $dict_lotes = $request->session()->get('dict_lotes');
        $dict_lotes = $request->session()->get('dict_lotes');
        $array_lotes_obj = $request->session()->get('array_lotes_obj');
        $event_date = $request->session()->get('event_date');

        $coupon_id = null;
        if($coupon) {
            $coupon_id = $coupon->id;
        }

        $order_id = DB::table('orders')->insertGetId([
            'hash' => md5(time() . uniqid() . md5('papainoel')),
            'status' => 2,
            'gatway_hash' => null,
            'gatway_reference' => null,
            'gatway_status' => null,
            'gatway_payment_method' => null,
            'event_id' => $event->id,
            'event_date_id' => $event_date->id,
            'participante_id' => Auth::user()->id,
            'coupon_id' => $coupon_id,
            'created_at' => now(),
        ]);

        $request->session()->put('order_id', $order_id);

        // foreach($dict_lotes as $i => $dict) {

        //     $lote = Lote::where('hash', $dict['lote_hash'])->first();

        //     $order_item_id = DB::table('order_items')->insertGetId([
        //         'hash' => md5((time() . uniqid() . $i) . md5('papainoel')),
        //         'number' => intval(crc32(md5(time() . uniqid() . $i) . md5('papainoel')), 36),
        //         'quantity' => 1,
        //         'value' => $lote->value,
        //         'date_use' => null,
        //         'status' => 2,
        //         'order_id' => $order_id,
        //         'lote_id' => $lote->id,
        //         'created_at' => now(),
        //     ]);

        //     foreach(array_keys($input) as $field) {

        //         if(str_contains($field, 'newfield_')) {
        //             $id = explode('_', $field);
        //             $k = $id[1];
        //             $id = $id[2];

        //             $question = Question::where('id', $id)->first();

        //             if($input['newfield_'. $k . '_'. $id] != '') {

        //                 $option_answer_id = DB::table('option_answers')->insertGetId([
        //                     'answer' => $input['newfield_'. $k . '_'. $id],
        //                     'question_id' => $question->id,
        //                     'order_item_id' => $order_item_id,
        //                     'created_at' => now(),
        //                 ]);
        //             }
        //         }
        //     }
        // }

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
        // Validar se o usuário está autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        // Validar se há dados de pagamento
        if (!$request->getContent()) {
            return response()->json(['error' => 'Dados de pagamento não fornecidos'], 400);
        }

        $input = json_decode($request->getContent());
        
        // Validar estrutura dos dados
        if (!$input || !isset($input->paymentType) || !isset($input->formData)) {
            return response()->json(['error' => 'Dados de pagamento inválidos'], 400);
        }

        // Validar se as credenciais do Mercado Pago estão configuradas
        $accessToken = env('MERCADO_PAGO_ACCESS_TOKEN', '');
        if (empty($accessToken)) {
            Log::error('Mercado Pago Access Token não configurado');
            return response()->json(['error' => 'Configuração de pagamento não encontrada'], 500);
        }

        MercadoPago\SDK::setAccessToken($accessToken);

        $order_id = $request->session()->get('order_id');
        $event = $request->session()->get('event');
        $total = $request->session()->get('total');

        $order = Order::findOrFail($order_id);

        $first_name = Str::of(Auth::user()->name)->explode(' ')[0];
        $tmp_explode = Str::of(Auth::user()->name)->explode(' ');
        $last_name = end($tmp_explode);

        $config = Configuration::findOrFail(1);

        $taxa_juros = $config->tax;

        if($event->config_tax != 0.0) {
            $taxa_juros = $event->config_tax;
        }

        // Calcular a taxa como porcentagem do valor total
        $application_fee = ($total * $taxa_juros) / 100;

        try {

            if($input->paymentType == 'credit_card') {

                $payment = new MercadoPago\Payment();
                $payment->transaction_amount = (float) $total;
                $payment->token = $input->formData->token;
                $payment->description = 'Ingresso Ticket DZ6: ' . $event->name;
                $payment->installments = (int) $input->formData->installments;
                $payment->payment_method_id = $input->formData->payment_method_id;
                $payment->issuer_id = (int) $input->formData->issuer_id;
                $payment->marketplace = env('MERCADO_PAGO_ACCESS_TOKEN', '');
                $payment->application_fee = $application_fee;
                // $payment->notification_url = 'http://requestbin.fullcontact.com/1ogudgk1';

                $payer = new MercadoPago\Payer();
                $payer->email = $input->formData->payer->email;
                $payer->first_name = $first_name;
                $payer->last_name = $last_name;
                $payer->identification = [
                    'type' => $input->formData->payer->identification->type,
                    'number' => $input->formData->payer->identification->number,
                ];

                $payment->payer = $payer;

            } elseif($input->paymentType == 'bank_transfer') {

                $payment = new MercadoPago\Payment();
                $payment->transaction_amount = (float) $total;
                $payment->description = 'Ingresso Ticket DZ6: ' . $event->name;
                $payment->payment_method_id = $input->formData->payment_method_id;
                $payment->marketplace = env('MERCADO_PAGO_ACCESS_TOKEN', '');
                $payment->application_fee = $application_fee;
                $payment->payer = [
                    'email' => $input->formData->payer->email,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'identification' => [
                        'type' => 'CPF',
                        'number' => Auth::user()->cpf,
                    ],
                    // "address"=>  array(
                    //     "zip_code" => "06233200",
                    //     "street_name" => "Av. das Nações Unidas",
                    //     "street_number" => "3003",
                    //     "neighborhood" => "Bonfim",
                    //     "city" => "Osasco",
                    //     "federal_unit" => "SP"
                    // )
                ];

                // var_dump($payment);
                // dd($payment);

            } elseif($input->paymentType == 'ticket') { // Boleto

                $payment = new MercadoPago\Payment();
                $payment->transaction_amount = (float) $total;
                $payment->description = 'Ingresso Ticket DZ6: ' . $event->name;
                $payment->payment_method_id = $input->formData->payment_method_id;
                $payment->marketplace = env('MERCADO_PAGO_ACCESS_TOKEN', '');
                $payment->application_fee = $application_fee;
                $payment->payer = [
                    'email' => $input->formData->payer->email,
                    'first_name' => $input->formData->payer->first_name,
                    'last_name' => $input->formData->payer->last_name,
                    'identification' => [
                        'type' => $input->formData->payer->identification->type,
                        'number' => $input->formData->payer->identification->number,
                    ],
                    'address' => [
                        'zip_code' => $input->formData->payer->address->zip_code,
                        'street_name' => $input->formData->payer->address->street_name,
                        'street_number' => $input->formData->payer->address->street_number,
                        'neighborhood' => $input->formData->payer->address->neighborhood,
                        'city' => $input->formData->payer->address->city,
                        'federal_unit' => $input->formData->payer->address->federal_unit,
                    ],
                ];
            }

            if ($payment->save()) {
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
                        'gatway_date_status' => $output->date_created,
                    ]);

                if($input->paymentType == 'credit_card') {

                    $total_amount_tax = 0;

                    $total_amount_tax = $total_amount_tax + ((float) $output->transaction_details->total_paid_amount - (float) $output->transaction_details->net_received_amount);

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
                        'created_at' => now(),
                    ]);
                    // MANDAR EMAIL COM COMPRA REALIZADA COM SUCESSO
                    Mail::to(Auth::user()->email)->send(new OrderMail($order, 'Compra realizada com sucesso'));

                } elseif($input->paymentType == 'bank_transfer') {

                    // SALVAR INFORMACOES DA TABELA PIX_DETAILS
                    $pix_detail_id = DB::table('pix_details')->insertGetId([
                        'value' => $output->transaction_details->total_paid_amount,
                        'qr_code' => $output->point_of_interaction->transaction_data->qr_code,
                        'qr_code_base64' => $output->point_of_interaction->transaction_data->qr_code_base64,
                        'ticket_url' => $output->point_of_interaction->transaction_data->ticket_url,
                        'expiration_date' => $output->date_of_expiration,
                        'order_id' => $order_id,
                        'created_at' => now(),
                    ]);
                    // MANDAR EMAIL COM INFORMAÇÕES DA COMPRA PENDENTE E DETALHES DA CHAVE PIX
                    $pixDetails = DB::table('pix_details')->where('id', $pix_detail_id)->first();
                    Mail::to(Auth::user()->email)->send(new \App\Mail\PixPendingMail($order, $pixDetails));

                } elseif($input->paymentType == 'ticket') {

                    // SALVAR INFORMACOES DA TABELA BOLETO_DETAILS
                    $boleto_detail_id = DB::table('boleto_details')->insertGetId([
                        'value' => $output->transaction_details->total_paid_amount,
                        'href' => $output->transaction_details->external_resource_url,
                        'line_code' => $output->barcode->content,
                        'expiration_date' => $output->date_of_expiration,
                        'order_id' => $order_id,
                        'created_at' => now(),
                    ]);
                    // MANDAR EMAIL COM INFORMAÇÕES DA COMPRA PENDENTE E DETALHES DO BOLETO
                    $boletoDetails = DB::table('boleto_details')->where('id', $boleto_detail_id)->first();
                    Mail::to(Auth::user()->email)->send(new \App\Mail\BoletoPendingMail($order, $boletoDetails));

                }

                return $output;

            } else {
                //Falha
                $errorArray = (array) $payment->error;
                $result = json_encode($errorArray);

                $output = json_decode($result);

                date_default_timezone_set('America/Fortaleza');
                $curr_date = date('Y-m-d H:i:s');

                // Atualizar status do pedido para rejeitado
                DB::table('orders')
                    ->where('id', $order_id)
                    ->update([
                        'status' => 3, // Rejeitado
                        'gatway_status' => $output->status ?? 'rejected',
                        'gatway_payment_method' => $input->paymentType,
                        'gatway_date_status' => $curr_date,
                        'gatway_description' => $output->message ?? 'Payment failed',
                        'updated_at' => now()
                    ]);

                // Log do erro para debugging
                Log::error('Mercado Pago Payment Failed', [
                    'order_id' => $order_id,
                    'payment_type' => $input->paymentType,
                    'error' => $output
                ]);

                // MANDAR EMAIL COM COMPRA NÃO REALIZADA
                Mail::to(Auth::user()->email)->send(new OrderMail($order, 'Falha no pagamento'));
            }

            return $result;

        } catch(Exception $exception) {
            Log::error('Mercado Pago Exception: ' . $exception->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }

    }

    public function oauth(Request $request){
     
        $code = $request->input('code');

        // Configuração da solicitação cURL
        $apiEndpoint = 'https://api.mercadopago.com/oauth/token';
        $requestData = [
            'client_id' => env('MERCADO_PAGO_CLIENT_ID', ''),
            'client_secret' => env('MERCADO_PAGO_CLIENT_SECRET', ''),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => env('MERCADO_PAGO_REDIRECT_URI', ''),
        ];

        // Inicia o cliente Guzzle
        $client = new Client();

        try {
            // Envia a solicitação POST para a API do MercadoPago
            $response = $client->post($apiEndpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestData,
            ]);

            // Obtém o corpo da resposta
            // $responseData = json_decode($response->getBody(), true);
            $responseData = json_decode($response->getBody()->getContents(), true);

            
            return response()->json($responseData);
        } catch (Exception $e) {
            // Manipule erros, se necessário
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
