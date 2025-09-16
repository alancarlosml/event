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
use Illuminate\Support\Facades\Schema;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;


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
        $request->session()->forget('event_date');

        if($event) {
            $total_dates = count($event->event_dates);
            // Se há apenas uma data, use a primeira data disponível do evento
            $date_min = $total_dates == 1 ? $event->event_dates->first() : EventDate::select('id')->where('date', $event->min_event_dates())->first();
 
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
        Log::info('Accessing resume page.', ['slug' => $slug, 'session_data' => $request->session()->all()]);

        $coupon = $request->session()->get('coupon');
        $subtotal = $request->session()->get('subtotal');
        $total = $request->session()->get('total');
        $dict_lotes = $request->session()->get('dict_lotes');
        $event_date_result = $request->session()->get('event_date_result');

        $event = Event::where('slug', $slug)->first();
        $request->session()->put('event', $event);
        $questions = Question::orderBy('order')->where('event_id', $event->id)->get();

        // Verificar se event_date_result existe na sessão; se não, tentar obter do request (GET ou POST)
        if (!$event_date_result) {
            Log::info('resume: event_date_result missing from session, trying request param', ['slug' => $slug, 'query_event_date_result' => $request->input('event_date_result')]);
            $requestEventDateResult = $request->input('event_date_result');
            if ($requestEventDateResult) {
                $eventDate = EventDate::where('id', $requestEventDateResult)->first();
                if ($eventDate) {
                    $request->session()->put('event_date_result', $requestEventDateResult);
                    $event_date_result = $requestEventDateResult;
                    Log::info('resume: event_date_result set from request param', ['event_date_result' => $event_date_result]);
                } else {
                    Log::warning('resume: event_date_result from request not found in DB', ['event_date_result' => $requestEventDateResult]);
                    return redirect()->route('conference.index', $event->slug)->withErrors(['error' => 'Data do evento não foi encontrada.']);
                }
            } else {
                Log::warning('resume: no event_date_result in session nor request');
                return redirect()->route('conference.index', $event->slug)->withErrors(['error' => 'Data do evento não foi selecionada.']);
            }
        }

        $eventDate = EventDate::where('id', $event_date_result)->first();
        if (!$eventDate) {
            Log::warning('resume: eventDate record not found', ['event_date_result' => $event_date_result]);
            return redirect()->route('conference.index', $event->slug)->withErrors(['error' => 'Data do evento não foi encontrada.']);
        }

        Log::info('resume: proceeding with data check', [
            'has_dict_lotes' => (bool) $dict_lotes,
            'dict_lotes' => $dict_lotes,
            'event_date_result' => $event_date_result,
        ]);

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

            Log::info('resume: rendering conference.resume view');
            return view('conference.resume', compact('event', 'questions', 'array_lotes', 'array_lotes_obj', 'coupon', 'subtotal', 'total', 'eventDate'));

        } else {

            $request->session()->forget('coupon');
            $request->session()->forget('subtotal');
            $request->session()->forget('coupon_subtotal');
            $request->session()->forget('total');
            $request->session()->forget('dict_lotes');
            $request->session()->forget('event_date_result');
            $request->session()->forget('event_date');
            Log::warning('resume: dict_lotes missing, redirecting back to conference.index');
            return redirect()->route('conference.index', $event->slug);
        }
    }

    public function setEventDate(Request $request)
    {

        $data = $request->all();

        $event_date_result = $data['event_date_result'];

        // Validar se o event_date_result foi fornecido
        if (!$event_date_result) {
            return response()->json(['error' => 'Data do evento não foi fornecida'], 400);
        }

        // Verificar se a data do evento existe no banco
        $eventDate = EventDate::where('id', $event_date_result)->first();
        if (!$eventDate) {
            return response()->json(['error' => 'Data do evento não foi encontrada'], 400);
        }

        $request->session()->put('event_date_result', $event_date_result);

        return response()->json(['success' => 'Data do evento selecionada com sucesso']);

    }

    public function getSubTotal(Request $request)
    {
        // Add comprehensive validation
        $request->validate([
            'dict' => 'required|array|min:1',
            'dict.*.lote_hash' => 'required|string|exists:lotes,hash',
            'dict.*.lote_quantity' => 'required|integer|min:0|max:999'
        ]);

        // Add rate limiting to prevent abuse
        $key = 'subtotal:' . $request->ip();
        $cacheEntry = DB::table('cache')->where('key', $key)->first();

        if ($cacheEntry && $cacheEntry->expiration > time()) {
            return response()->json(['error' => 'Muitas solicitações. Tente novamente em alguns segundos.'], 429);
        }

        // Armazenar timestamp atual + 1 minuto
        DB::table('cache')->updateOrInsert(
            ['key' => $key],
            [
                'value' => time(),
                'expiration' => time() + 120
            ]
        );

        $data = $request->all();
        $dicts = $data['dict'];

        $subtotal = 0;
        $coupon_subtotal = 0;
        $total = 0;

        $request->session()->put('dict_lotes', $dicts);

        Log::info('getSubTotal called', ['dicts' => $dicts, 'subtotal' => $subtotal]);

        if($dicts) {
            foreach($dicts as $dict) {

                $quantity = $dict['lote_quantity'];
                $lote = Lote::where('hash', $dict['lote_hash'])->first();

                // VALIDATION 1: Check if lot exists
                if (!$lote) {
                    return response()->json(['error' => 'Lote não encontrado.'], 400);
                }

                // VALIDATION 2: Check if lot is currently available for sale
                $now = now();
                if ($lote->datetime_begin > $now || $lote->datetime_end < $now) {
                    return response()->json(['error' => 'Este lote não está disponível para venda no momento.'], 400);
                }

                // VALIDATION 3: Check quantity limits per user
                if ($quantity < $lote->limit_min || $quantity > $lote->limit_max) {
                    return response()->json(['error' => "Quantidade deve ser entre {$lote->limit_min} e {$lote->limit_max} ingressos."], 400);
                }

                // VALIDATION 4: Check if lot has enough available tickets
                $soldTickets = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.lote_id', $lote->id)
                    ->where('orders.status', '!=', 3) // Exclude cancelled orders
                    ->sum('order_items.quantity');
                
                $availableTickets = $lote->quantity - $soldTickets;
                if ($quantity > $availableTickets) {
                    return response()->json(['error' => "Apenas {$availableTickets} ingressos disponíveis neste lote."], 400);
                }

                // VALIDATION 5: Check if user already purchased tickets for this lot
                if (Auth::check()) {
                    $userPurchases = DB::table('order_items')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->where('order_items.lote_id', $lote->id)
                        ->where('orders.participante_id', Auth::user()->id)
                        ->where('orders.status', '!=', 3)
                        ->sum('order_items.quantity');
                    
                    if (($userPurchases + $quantity) > $lote->limit_max) {
                        return response()->json(['error' => "Você já comprou {$userPurchases} ingressos deste lote. Limite máximo: {$lote->limit_max}."], 400);
                    }
                }

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

        // VALIDATION 1: Check if event exists
        if (!$evento) {
            return response()->json(['error' => 'Evento não encontrado.'], 400);
        }

        // VALIDATION 2: Check if coupon code is provided
        if (empty($couponCode)) {
            return response()->json(['error' => 'Código do cupom é obrigatório.'], 400);
        }

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

        // VALIDATION 3: Check if coupon exists and is valid
        if (!$coupon) {
            return response()->json(['error' => 'Cupom inválido ou indisponível.'], 400);
        }

        // VALIDATION 4: Check if coupon is currently active
        $now = now();
        if (isset($coupon->valid_from) && $coupon->valid_from > $now) {
            return response()->json(['error' => 'Este cupom ainda não está disponível.'], 400);
        }
        if (isset($coupon->valid_until) && $coupon->valid_until < $now) {
            return response()->json(['error' => 'Este cupom expirou.'], 400);
        }

        // VALIDATION 5: Check if user already used this coupon
        if (Auth::check()) {
            $userCouponUsage = DB::table('orders')
                ->where('coupon_id', $coupon->id)
                ->where('participante_id', Auth::user()->id)
                ->where('status', '!=', 3) // Exclude cancelled orders
                ->count();
            
            if ($userCouponUsage > 0) {
                return response()->json(['error' => 'Você já utilizou este cupom.'], 400);
            }
        }

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

    public function payment(Request $request, $slug)
    {
        Log::info('=== PAYMENT METHOD START ===', [
            'slug' => $slug, 
            'method' => $request->method(),
            'session_data' => $request->session()->all()
        ]);
        
        $input = $request->all();

        // Validar dados da sessão
        $event = $request->session()->get('event');
        $coupon = $request->session()->get('coupon');
        $total = $request->session()->get('total');
        $dict_lotes = $request->session()->get('dict_lotes');
        $array_lotes_obj = $request->session()->get('array_lotes_obj');
        $event_date = $request->session()->get('event_date');

        // Validações obrigatórias
        if (!$event_date) {
            return redirect()->back()->withErrors(['error' => 'Data do evento não foi selecionada.']);
        }

        if (!$dict_lotes || empty($dict_lotes)) {
            return redirect()->back()->withErrors(['error' => 'Nenhum ingresso foi selecionado.']);
        }

        if (!$total || $total < 0) {
            return redirect()->back()->withErrors(['error' => 'Valor total inválido.']);
        }

        // FINAL VALIDATION: Re-validate all lots before payment
        foreach ($dict_lotes as $dict) {
            $quantity = $dict['lote_quantity'];
            $lote = Lote::where('hash', $dict['lote_hash'])->first();

            if (!$lote) {
                return redirect()->back()->withErrors(['error' => 'Lote não encontrado.']);
            }

            // Verificar período de vendas
            $now = now();
            if ($lote->datetime_begin > $now || $lote->datetime_end < $now) {
                return redirect()->back()->withErrors(['error' => 'Este lote não está disponível para venda no momento.']);
            }

            // Verificar limites de quantidade
            if ($quantity < $lote->limit_min || $quantity > $lote->limit_max) {
                return redirect()->back()->withErrors(['error' => "Quantidade deve ser entre {$lote->limit_min} e {$lote->limit_max} ingressos."]);
            }

            // Verificar disponibilidade
            $soldTickets = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('order_items.lote_id', $lote->id)
                ->where('orders.status', '!=', 3)
                ->sum('order_items.quantity');
            
            $availableTickets = $lote->quantity - $soldTickets;
            if ($quantity > $availableTickets) {
                return redirect()->back()->withErrors(['error' => "Apenas {$availableTickets} ingressos disponíveis neste lote."]);
            }

            // Verificar limite por usuário
            if (Auth::check()) {
                $userPurchases = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.lote_id', $lote->id)
                    ->where('orders.participante_id', Auth::user()->id)
                    ->where('orders.status', '!=', 3)
                    ->sum('order_items.quantity');
                
                if (($userPurchases + $quantity) > $lote->limit_max) {
                    return redirect()->back()->withErrors(['error' => "Você já comprou {$userPurchases} ingressos deste lote. Limite máximo: {$lote->limit_max}."]);
                }
            }
        }

        // Bypass para eventos gratuitos
        if ((float) $total <= 0) {
            return $this->processFreeTicker($request, $event, $event_date, $coupon, $input);
        }

        // Criar pedido para pagamento
        $coupon_id = $coupon ? ($coupon->id ?? (is_array($coupon) && isset($coupon[0]['id']) ? $coupon[0]['id'] : null)) : null;

        $order_id = DB::table('orders')->insertGetId([
            'hash' => md5(time() . uniqid() . md5('7bc05eb02415fe73101eeea0180e258d45e8ba2b')),
            'status' => 2, // pendente
            'event_id' => $event->id,
            'event_date_id' => $event_date->id,
            'participante_id' => Auth::user()->id,
            'coupon_id' => $coupon_id,
            'created_at' => now(),
        ]);

        $request->session()->put('order_id', $order_id);

        // Criar order_items corretamente
        $this->createOrderItems($order_id, $request);

        return redirect()->route('conference.paymentView', $event->slug);
    }

    private function processFreeTicker($request, $event, $event_date, $coupon, $input)
    {
        $coupon_id = $coupon ? ($coupon->id ?? (is_array($coupon) && isset($coupon[0]['id']) ? $coupon[0]['id'] : null)) : null;

        $order_id = DB::table('orders')->insertGetId([
            'hash' => md5(time() . uniqid() . md5('7bc05eb02415fe73101eeea0180e258d45e8ba2b')),
            'status' => 1, // concluído
            'gatway_payment_method' => 'free',
            'gatway_status' => '1',
            'event_id' => $event->id,
            'event_date_id' => $event_date->id,
            'participante_id' => Auth::user()->id,
            'coupon_id' => $coupon_id,
            'created_at' => now(),
        ]);

        $this->createOrderItems($order_id, $request);

        try {
            $order = Order::find($order_id);
            if ($order) {
                Mail::to(Auth::user()->email)->send(new OrderMail($order, 'Inscrição gratuita realizada com sucesso'));
            }
        } catch (\Throwable $e) {
            Log::warning('Falha ao enviar email de confirmação', ['error' => $e->getMessage()]);
        }

        // Limpar sessão
        $request->session()->forget(['coupon', 'subtotal', 'coupon_subtotal', 'total', 'dict_lotes', 'event_date_result', 'event_date', 'array_lotes', 'array_lotes_obj', 'order_id']);

        return redirect()->route('event_home.my_registrations')
            ->with('success', 'Inscrição realizada com sucesso!');
    }

    private function createOrderItems($order_id, $request)
    {
        $array_lotes_obj = $request->session()->get('array_lotes_obj');
        $input = $request->all();

        if ($array_lotes_obj && is_array($array_lotes_obj)) {
            $kIndex = 1;
            
            foreach ($array_lotes_obj as $i => $entry) {
                $lote = Lote::find($entry['id']);
                if (!$lote) continue;

                $order_item_id = DB::table('order_items')->insertGetId([
                    'hash' => md5((time() . uniqid() . $i) . md5('7bc05eb02415fe73101eeea0180e258d45e8ba2b')),
                    'number' => abs(crc32(md5(time() . uniqid() . $i))),
                    'quantity' => 1,
                    'value' => $entry['value'] ?? $lote->value,
                    'status' => 2, // pendente
                    'order_id' => $order_id,
                    'lote_id' => $lote->id,
                    'created_at' => now(),
                ]);

                // Salvar respostas dos campos personalizados
                foreach (array_keys($input) as $field) {
                    if (!str_contains($field, 'newfield_')) continue;

                    $parts = explode('_', $field);
                    if (count($parts) !== 3) continue;

                    $kField = (int) $parts[1];
                    $questionId = (int) $parts[2];
                    
                    if ($kField !== $kIndex) continue;

                    $answer = $input[$field];
                    if ($answer === null || $answer === '') continue;

                    $question = Question::find($questionId);
                    if (!$question) continue;

                    DB::table('option_answers')->insert([
                        'answer' => $answer,
                        'question_id' => $question->id,
                        'order_item_id' => $order_item_id,
                        'created_at' => now(),
                    ]);
                }

                $kIndex++;
            }
        }
}

    public function paymentView(Request $request)
    {
        $event = $request->session()->get('event');
        $total = $request->session()->get('total');

        return view('conference.payment', compact('event', 'total'));
    }

    public function thanks(Request $request)
    {
        // Log request details for debugging
        Log::info('Payment request received', [
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'has_content' => !empty($request->getContent()),
            'user_id' => Auth::check() ? Auth::id() : null
        ]);

        // Validar se o usuário está autenticado
        if (!Auth::check()) {
            Log::warning('Unauthenticated payment attempt');
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        // Validar CSRF token para requests web
        if ($request->expectsJson() && !$request->header('X-CSRF-TOKEN')) {
            Log::warning('Missing CSRF token in payment request');
            return response()->json(['error' => 'Token CSRF obrigatório'], 403);
        }

        // Validar se há dados de pagamento
        $content = $request->getContent();
        if (empty($content)) {
            Log::warning('Empty payment request content');
            return response()->json(['error' => 'Dados de pagamento não fornecidos'], 400);
        }

        try {
            $input = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \JsonException('Invalid JSON: ' . json_last_error_msg());
            }
        } catch (\JsonException $e) {
            Log::error('JSON decode error', ['error' => $e->getMessage(), 'content' => $content]);
            return response()->json(['error' => 'Formato JSON inválido'], 400);
        }

        // Validar estrutura dos dados
        if (!$input || !isset($input['paymentType']) || !isset($input['formData'])) {
            Log::warning('Invalid payment data structure', ['input' => $input]);
            return response()->json(['error' => 'Dados de pagamento inválidos'], 400);
        }

        // Rate limiting por usuário
        $userId = Auth::id();
        $cacheKey = "payment_attempt:{$userId}";
        $attempts = cache()->get($cacheKey, 0);
        
        if ($attempts >= 3) {
            Log::warning('Rate limit exceeded for user', ['user_id' => $userId, 'attempts' => $attempts]);
            return response()->json(['error' => 'Muitas tentativas de pagamento. Tente novamente em 5 minutos.'], 429);
        }

        cache()->put($cacheKey, $attempts + 1, now()->addMinutes(5));

        // Validar se as credenciais do Mercado Pago estão configuradas
        $accessToken = env('MERCADO_PAGO_ACCESS_TOKEN', 'APP_USR-5198507811366797-070210-65a9d5b969881e63a60b563a8e3fb8b5-618667986');
        if (empty($accessToken)) {
            Log::error('Mercado Pago Access Token not configured');
            return response()->json(['error' => 'Configuração de pagamento não encontrada'], 500);
        }

        // Validar dados da sessão
        $order_id = $request->session()->get('order_id');
        $event = $request->session()->get('event');
        $total = $request->session()->get('total');

        if (!$order_id || !$event || !$total) {
            Log::warning('Missing session data', [
                'has_order_id' => (bool) $order_id,
                'has_event' => (bool) $event,
                'has_total' => (bool) $total,
                'user_id' => $userId
            ]);
            return response()->json(['error' => 'Sessão expirada. Reinicie o processo de compra.'], 400);
        }

        // Validar se a ordem existe e pertence ao usuário
        $order = Order::where('id', $order_id)
                    ->where('participante_id', Auth::id())
                    ->where('status', 2) // apenas ordens pendentes
                    ->first();

        if (!$order) {
            Log::warning('Order not found or already processed', [
                'order_id' => $order_id,
                'user_id' => $userId
            ]);
            return response()->json(['error' => 'Ordem não encontrada ou já processada'], 400);
        }

        // Configurar Mercado Pago
        try {
            MercadoPagoConfig::setAccessToken($accessToken);
        } catch (\Exception $e) {
            Log::error('Failed to set MercadoPago access token', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erro na configuração do pagamento'], 500);
        }

        // Preparar dados do pagador
        $user = Auth::user();
        $first_name = Str::of($user->name)->explode(' ')[0];
        $tmp_explode = Str::of($user->name)->explode(' ');
        $last_name = count($tmp_explode) > 1 ? end($tmp_explode) : $first_name;

        // Calcular taxa
        $config = Configuration::findOrFail(1);
        $taxa_juros = $event->config_tax != 0.0 ? $event->config_tax : $config->tax;
        $application_fee = ($total * $taxa_juros);

        Log::info('Processing payment', [
            'payment_type' => $input['paymentType'],
            'order_id' => $order_id,
            'total' => $total,
            'user_id' => $userId
        ]);

        $client = new PaymentClient();
        $paymentRequest = [];

        // Preparar request baseado no tipo de pagamento
        try {
            switch ($input['paymentType']) {
                case 'credit_card':
                    if (!isset($input['formData']['token']) || !isset($input['formData']['installments'])) {
                        return response()->json(['error' => 'Dados do cartão incompletos'], 400);
                    }

                    $paymentRequest = [
                        "transaction_amount" => (float) $total,
                        "token" => $input['formData']['token'],
                        "description" => 'Ingresso ' . $event->name,
                        "installments" => (int) $input['formData']['installments'],
                        "payment_method_id" => $input['formData']['payment_method_id'],
                        "issuer_id" => (int) $input['formData']['issuer_id'],
                        "application_fee" => (float) $application_fee,
                        "payer" => [
                            "email" => $input['formData']['payer']['email'],
                            "first_name" => $first_name,
                            "last_name" => $last_name,
                            "identification" => [
                                "type" => $input['formData']['payer']['identification']['type'],
                                "number" => $input['formData']['payer']['identification']['number'],
                            ]
                        ]
                    ];
                    break;

                case 'bank_transfer':
                    $paymentRequest = [
                        "transaction_amount" => (float) $total,
                        "description" => 'Ingresso ' . $event->name,
                        "payment_method_id" => $input['formData']['payment_method_id'],
                        //"application_fee" => (float) $application_fee,
                        "payer" => [
                            "email" => $user->email,
                            "first_name" => $first_name,
                            "last_name" => $last_name,
                            "identification" => [
                                "type" => "CPF",
                                "number" => $user->cpf
                            ],
                        ]
                    ];
                    break;

                case 'ticket':
                    // Validar dados específicos do boleto
                    if (!isset($input['formData']['payer']['address'])) {
                        return response()->json(['error' => 'Endereço obrigatório para boleto'], 400);
                    }

                    $address = $input['formData']['payer']['address'];
                    $requiredAddressFields = ['zip_code', 'street_name', 'street_number', 'neighborhood', 'city', 'federal_unit'];
                    
                    foreach ($requiredAddressFields as $field) {
                        if (!isset($address[$field]) || empty($address[$field])) {
                            Log::warning('Missing address field for boleto', ['field' => $field, 'address' => $address]);
                            return response()->json(['error' => "Campo obrigatório para boleto: {$field}"], 400);
                        }
                    }

                    $paymentRequest = [
                        "transaction_amount" => (float) $total,
                        "description" => 'Ingresso ' . $event->name,
                        "payment_method_id" => $input['formData']['payment_method_id'],
                        //"application_fee" => (float) $application_fee,
                        "payer" => [
                            "email" => $input['formData']['payer']['email'],
                            "first_name" => $input['formData']['payer']['first_name'],
                            "last_name" => $input['formData']['payer']['last_name'],
                            "identification" => [
                                "type" => $input['formData']['payer']['identification']['type'],
                                "number" => $input['formData']['payer']['identification']['number'],
                            ],
                            "address" => [
                                "zip_code" => $address['zip_code'],
                                "street_name" => $address['street_name'],
                                "street_number" => $address['street_number'],
                                "neighborhood" => $address['neighborhood'],
                                "city" => $address['city'],
                                "federal_unit" => $address['federal_unit'],
                            ],
                        ]
                    ];
                    break;

                default:
                    Log::warning('Unsupported payment type', ['payment_type' => $input['paymentType']]);
                    return response()->json(['error' => 'Tipo de pagamento não suportado'], 400);
            }

            Log::info('Payment request prepared', [
                'payment_method' => $input['formData']['payment_method_id'] ?? 'unknown',
                'amount' => $total
            ]);

            // Processar pagamento
            $payment = $client->create($paymentRequest);

            Log::info('Payment response received', [
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'payment_method' => $payment->payment_method_id ?? $payment->payment_type_id
            ]);

            // Atualizar ordem com dados do pagamento
            DB::table('orders')
                ->where('id', $order_id)
                ->update([
                    'status' => $payment->status === 'approved' ? 1 : 2,
                    'gatway_hash' => $payment->id,
                    'gatway_status' => $payment->status,
                    'gatway_payment_method' => $payment->payment_type_id ?? $payment->payment_method_id,
                    'gatway_date_status' => $payment->date_created ?? now(),
                    'updated_at' => now(),
                ]);

            // Processar baseado no tipo de pagamento
            try {
                if ($input['paymentType'] == 'credit_card') {
                    $this->processCreditCardPayment($payment, $order_id, $total);
                    
                    // Enviar email de confirmação
                    try {
                        Mail::to($user->email)->send(new OrderMail($order, 'Compra realizada com sucesso'));
                    } catch (\Throwable $e) {
                        Log::warning('Failed to send confirmation email', ['error' => $e->getMessage()]);
                    }
                    
                } elseif ($input['paymentType'] == 'bank_transfer') {
                    $this->processPixPayment($payment, $order_id, $total);
                    
                    // Enviar email com dados do PIX
                    try {
                        $pixDetails = DB::table('pix_details')->where('order_id', $order_id)->first();
                        if ($pixDetails && class_exists('\App\Mail\PixPendingMail')) {
                            Mail::to($user->email)->send(new \App\Mail\PixPendingMail($order, $pixDetails));
                        }
                    } catch (\Throwable $e) {
                        Log::warning('Failed to send PIX email', ['error' => $e->getMessage()]);
                    }
                    
                } elseif ($input['paymentType'] == 'ticket') {
                    $this->processBoletoPayment($payment, $order_id, $total);
                    
                    // Enviar email com dados do boleto
                    try {
                        $boletoDetails = DB::table('boleto_details')->where('order_id', $order_id)->first();
                        if ($boletoDetails && class_exists('\App\Mail\BoletoPendingMail')) {
                            Mail::to($user->email)->send(new \App\Mail\BoletoPendingMail($order, $boletoDetails));
                        }
                    } catch (\Throwable $e) {
                        Log::warning('Failed to send boleto email', ['error' => $e->getMessage()]);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Error processing payment details', [
                    'payment_type' => $input['paymentType'],
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Continuar mesmo com erro no processamento dos detalhes
            }

            // Limpar cache de tentativas em caso de sucesso
            cache()->forget($cacheKey);

            // Atualizar status dos order_items se pagamento aprovado
            if ($payment->status === 'approved') {
                DB::table('order_items')
                    ->where('order_id', $order_id)
                    ->update(['status' => 1, 'updated_at' => now()]);
            }

            Log::info('Payment processed successfully', [
                'payment_id' => $payment->id,
                'order_id' => $order_id,
                'status' => $payment->status
            ]);

            return response()->json([
                'id' => $payment->id,
                'status' => $payment->status,
                'detail' => $payment->status_detail ?? null
            ]);

        } catch (MPApiException $e) {
            $apiResponse = $e->getApiResponse();
            $statusCode = $apiResponse->getStatusCode();
            $content = $apiResponse->getContent();

            Log::error('Mercado Pago API Error', [
                'order_id' => $order_id,
                'user_id' => Auth::id(),
                'payment_type' => $input['paymentType'],
                'status_code' => $statusCode,
                'content' => $content,
                'payment_request' => $paymentRequest
            ]);

            // Atualizar ordem com erro
            DB::table('orders')
                ->where('id', $order_id)
                ->update([
                    'status' => 3, // cancelado
                    'gatway_status' => 'rejected',
                    'gatway_payment_method' => $input['paymentType'],
                    'gatway_date_status' => now(),
                    'gatway_description' => is_array($content) ? ($content['message'] ?? 'API Error') : 'API Error',
                    'updated_at' => now()
                ]);

            // Determinar erro específico baseado no status code
            $errorMessage = 'Falha no processamento do pagamento';
            if ($statusCode >= 400 && $statusCode < 500) {
                $errorMessage = is_array($content) && isset($content['message']) ? 
                    $content['message'] : 'Dados de pagamento inválidos';
            } elseif ($statusCode >= 500) {
                $errorMessage = 'Serviço temporariamente indisponível. Tente novamente.';
            }

            return response()->json(['error' => $errorMessage], $statusCode >= 500 ? 503 : 400);

        } catch (\Exception $e) {
            Log::error('Payment Exception', [
                'order_id' => $order_id,
                'user_id' => Auth::id(),
                'payment_type' => $input['paymentType'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Atualizar ordem com erro
            try {
                DB::table('orders')
                    ->where('id', $order_id)
                    ->update([
                        'status' => 3,
                        'gatway_status' => 'error',
                        'gatway_payment_method' => $input['paymentType'],
                        'gatway_date_status' => now(),
                        'gatway_description' => $e->getMessage(),
                        'updated_at' => now()
                    ]);
            } catch (\Throwable $dbError) {
                Log::error('Failed to update order with error status', ['error' => $dbError->getMessage()]);
            }

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    private function processCreditCardPayment($payment, $order_id, $total)
    {
        $total_amount_tax = 0;
        if (isset($payment->transaction_details)) {
            $total_amount_tax = (float) $payment->transaction_details->total_paid_amount - (float) $payment->transaction_details->net_received_amount;
        }

        DB::table('credit_details')->insert([
            'token' => $payment->token ?? null,
            'installments' => $payment->installments ?? null,
            'value' => $payment->transaction_details->total_paid_amount ?? $total,
            'installment_amount' => $payment->transaction_details->installment_amount ?? null,
            'total_paid_amount' => $payment->transaction_details->total_paid_amount ?? $total,
            'net_received_amount' => $payment->transaction_details->net_received_amount ?? $total,
            'total_amount_tax' => $total_amount_tax,
            'payment_method_id' => $payment->payment_method_id,
            'order_id' => $order_id,
            'created_at' => now(),
        ]);
    }

    private function processPixPayment($payment, $order_id, $total)
    {
        DB::table('pix_details')->insert([
            'value' => $payment->transaction_details->total_paid_amount ?? $total,
            'qr_code' => $payment->point_of_interaction->transaction_data->qr_code ?? null,
            'qr_code_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null,
            'ticket_url' => $payment->point_of_interaction->transaction_data->ticket_url ?? null,
            'expiration_date' => $payment->date_of_expiration ?? null,
            'order_id' => $order_id,
            'created_at' => now(),
        ]);
    }

    private function processBoletoPayment($payment, $order_id, $total)
    {
        try {
            // Verificar se a tabela boleto_details existe
            if (!Schema::hasTable('boleto_details')) {
                Log::error('Table boleto_details does not exist');
                throw new \Exception('Tabela de detalhes do boleto não encontrada');
            }

            $boletoData = [
                'value' => $payment->transaction_details->total_paid_amount ?? $total,
                'href' => $payment->transaction_details->external_resource_url ?? null,
                'line_code' => $payment->barcode->content ?? null,
                'expiration_date' => $payment->date_of_expiration ?? null,
                'order_id' => $order_id,
                'created_at' => now(),
            ];

            Log::info('Saving boleto details', ['data' => $boletoData]);

            DB::table('boleto_details')->insert($boletoData);

            Log::info('Boleto details saved successfully', ['order_id' => $order_id]);

        } catch (\Throwable $e) {
            Log::error('Error processing boleto payment', [
                'order_id' => $order_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
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
