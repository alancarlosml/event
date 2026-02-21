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
use App\Models\MpAccount;
use App\Models\OptionAnswer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\State;
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
            $total_dates = $event->event_dates->count();
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
                    $valorExibir = (float) $lote->value;
                    $ehPago = ($lote->type == 0 && $valorExibir > 0);
                    if ($ehPago && $lote->tax_service == 0) {
                        $taxa_fixa = $lote->tax ?? 0;
                        $array = ['id' => $lote->id, 'quantity' => $quantity, 'value' => ($valorExibir + $taxa_fixa), 'name' => $lote->name];
                    } else {
                        $array = ['id' => $lote->id, 'quantity' => $quantity, 'value' => $valorExibir, 'name' => $lote->name];
                    }
                    array_push($array_lotes, $array);
                }

                if($quantity > 0) {
                    $valorExibir = (float) $lote->value;
                    $ehPago = ($lote->type == 0 && $valorExibir > 0);
                    for($j = 0; $j < $quantity; $j++) {
                        if ($ehPago && $lote->tax_service == 0) {
                            $taxa_fixa = $lote->tax ?? 0;
                            $array_obj = ['id' => $lote->id, 'quantity' => 1, 'value' => ($valorExibir + $taxa_fixa), 'name' => $lote->name];
                        } else {
                            $array_obj = ['id' => $lote->id, 'quantity' => 1, 'value' => $valorExibir, 'name' => $lote->name];
                        }
                        array_push($array_lotes_obj, $array_obj);
                    }
                }
            }

            $request->session()->put('event_date', $eventDate);
            $request->session()->put('array_lotes', $array_lotes);
            $request->session()->put('array_lotes_obj', $array_lotes_obj);

            // Carregar estados para campos do tipo Estados (BRA)
            $states = State::orderBy('name')->get();

            Log::info('resume: rendering conference.resume view');
            return view('conference.resume', compact('event', 'questions', 'array_lotes', 'array_lotes_obj', 'coupon', 'subtotal', 'total', 'eventDate', 'states'));

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
        // Validação mais flexível - permite array vazio para limpar valores
        $request->validate([
            'dict' => 'required|array',
            'dict.*.lote_hash' => 'required_with:dict.*|string|exists:lotes,hash',
            'dict.*.lote_quantity' => 'required_with:dict.*|integer|min:0|max:999'
        ]);

        // Add rate limiting to prevent abuse - permite 15 requisições por 10 segundos
        $key = 'subtotal:' . $request->ip();
        $cacheKey = $key . ':count';
        $cacheExpirationKey = $key . ':expiration';
        
        $count = cache()->get($cacheKey, 0);
        $expiration = cache()->get($cacheExpirationKey, 0);

        // Se o período de 10 segundos ainda não expirou
        if ($expiration > time()) {
            // Se já fez 15 requisições ou mais, bloquear
            if ($count >= 15) {
                $remainingSeconds = $expiration - time();
                return response()->json([
                    'error' => "Muitas solicitações. Tente novamente em {$remainingSeconds} segundo(s)."
                ], 429);
        }
            // Incrementar contador
            cache()->put($cacheKey, $count + 1, now()->addSeconds(10));
        } else {
            // Iniciar novo período de 10 segundos
            cache()->put($cacheKey, 1, now()->addSeconds(10));
            cache()->put($cacheExpirationKey, time() + 10, now()->addSeconds(10));
        }

        $data = $request->all();
        $dicts = $data['dict'] ?? [];

        $subtotal = 0;
        $coupon_subtotal = 0;
        $total = 0;

        $request->session()->put('dict_lotes', $dicts);

        Log::info('getSubTotal called', ['dicts' => $dicts, 'count' => count($dicts ?? [])]);

        // Processar apenas lotes com quantidade > 0
        if($dicts && is_array($dicts) && count($dicts) > 0) {
            foreach($dicts as $index => $dict) {
                // Converter quantidade para inteiro
                $quantity = (int)($dict['lote_quantity'] ?? 0);
                $loteHash = $dict['lote_hash'] ?? null;
                
                Log::info("Processando lote {$index}", [
                    'lote_hash' => $loteHash,
                    'quantity' => $quantity
                ]);
                
                // Pular se quantidade for 0 ou negativa
                if ($quantity <= 0) {
                    Log::info("Lote {$index} pulado - quantidade <= 0", ['quantity' => $quantity]);
                    continue;
                }
                
                if (!$loteHash) {
                    Log::warning("Lote {$index} sem hash");
                    continue;
                }
                
                $lote = Lote::where('hash', $loteHash)->first();

                // VALIDATION 1: Check if lot exists
                if (!$lote) {
                    Log::error("Lote não encontrado", [
                        'hash' => $loteHash,
                        'tentativa_busca' => 'Lote::where("hash", $loteHash)->first()'
                    ]);
                    // Tentar buscar de outra forma para debug
                    $loteAlternativo = Lote::where('hash', 'like', '%' . $loteHash . '%')->first();
                    if ($loteAlternativo) {
                        Log::warning("Lote encontrado com busca alternativa", [
                            'hash_buscado' => $loteHash,
                            'hash_encontrado' => $loteAlternativo->hash
                        ]);
                    }
                    return response()->json(['error' => "Lote não encontrado (hash: {$loteHash})."], 400);
                }
                
                Log::info("Lote encontrado", [
                    'lote_id' => $lote->id,
                    'lote_name' => $lote->name,
                    'lote_type' => $lote->type,
                    'lote_value' => $lote->value,
                    'lote_tax_service' => $lote->tax_service ?? 'null',
                    'quantity' => $quantity,
                    'hash_buscado' => $loteHash,
                    'hash_encontrado' => $lote->hash
                ]);

                // VALIDATION 2: Check if lot is currently available for sale
                $now = now();
                if ($lote->datetime_begin && $lote->datetime_end) {
                    if ($lote->datetime_begin > $now || $lote->datetime_end < $now) {
                        Log::warning("Lote fora do período de venda", [
                            'lote_id' => $lote->id,
                            'datetime_begin' => $lote->datetime_begin,
                            'datetime_end' => $lote->datetime_end,
                            'now' => $now
                        ]);
                        // Não retornar erro aqui, apenas logar - permitir cálculo mesmo fora do período
                        // return response()->json(['error' => 'Este lote não está disponível para venda no momento.'], 400);
                    }
                }

                // VALIDATION 3: Check quantity limits per user (apenas se houver limites definidos)
                if ($lote->limit_min > 0 && $quantity < $lote->limit_min) {
                    return response()->json(['error' => "Quantidade mínima para este lote é {$lote->limit_min} ingressos."], 400);
                }
                if ($lote->limit_max > 0 && $quantity > $lote->limit_max) {
                    return response()->json(['error' => "Quantidade máxima para este lote é {$lote->limit_max} ingressos."], 400);
                }

                // VALIDATION 4: Check if lot has enough available tickets (apenas se houver limite)
                if ($lote->quantity > 0) {
                    $soldTickets = DB::table('order_items')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->where('order_items.lote_id', $lote->id)
                        ->whereIn('orders.status', [1, 2]) // Apenas confirmados e pendentes
                        ->sum('order_items.quantity');
                    
                    $availableTickets = $lote->quantity - $soldTickets;
                    if ($quantity > $availableTickets) {
                        Log::warning("Quantidade solicitada excede disponibilidade", [
                            'quantity' => $quantity,
                            'available' => $availableTickets
                        ]);
                        return response()->json(['error' => "Apenas {$availableTickets} ingressos disponíveis neste lote."], 400);
                    }
                }

                // VALIDATION 5: Check if user already purchased tickets for this lot
                if (Auth::check() && $lote->limit_max > 0) {
                    $userPurchases = DB::table('order_items')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->where('order_items.lote_id', $lote->id)
                        ->where('orders.participante_id', Auth::user()->id)
                        ->whereIn('orders.status', [1, 2]) // Apenas confirmados e pendentes
                        ->sum('order_items.quantity');
                    
                    if ($lote->limit_max > 0 && ($userPurchases + $quantity) > $lote->limit_max) {
                        return response()->json(['error' => "Você já comprou {$userPurchases} ingressos deste lote. Limite máximo: {$lote->limit_max}."], 400);
                    }
                }

                // Só adiciona ao subtotal para lotes pagos (tipo 0 e valor > 0). Lotes gratuitos nunca cobram.
                $valorNumerico = (float) $lote->value;
                $ehLotePago = ($lote->type == 0 && $valorNumerico > 0);

                if ($ehLotePago) {
                    try {
                        // Aplicar taxa apenas para lotes do tipo 0 (pagos) e se tax_service for 0
                        if ($lote->tax_service == 0) {
                            // Usar o valor fixo da taxa do lote (já calculado na criação)
                            // Se não houver taxa no lote, calcular usando a taxa global como fallback
                            $taxa_fixa = $lote->tax ?? 0;
                            if($taxa_fixa == 0) {
                                // Fallback: calcular taxa usando configuração global
                                $config = Configuration::first();
                                $taxRate = $config ? ($config->tax ?? 0) : 0;
                                $taxa_fixa = $valorNumerico * $taxRate;
                            }
                            $valor_calculado = ($valorNumerico + $taxa_fixa) * $quantity;
                            $subtotal += $valor_calculado;
                            Log::info("Subtotal calculado (com taxa)", [
                                'lote_type' => $lote->type,
                                'valor_unitario' => $lote->value,
                                'taxa_fixa' => $taxa_fixa,
                                'quantidade' => $quantity,
                                'valor_calculado' => $valor_calculado,
                                'subtotal_acumulado' => $subtotal
                            ]);
                        } else {
                            // Lote pago com taxa paga pelo organizador (tax_service == 1)
                            $valor_calculado = $valorNumerico * $quantity;
                            $subtotal += $valor_calculado;
                            Log::info("Subtotal calculado (sem taxa - organizador paga)", [
                                'lote_type' => $lote->type,
                                'valor_unitario' => $lote->value,
                                'quantidade' => $quantity,
                                'valor_calculado' => $valor_calculado,
                                'subtotal_acumulado' => $subtotal
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error("Erro ao calcular subtotal", [
                            'lote_id' => $lote->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                } else {
                    Log::info("Lote gratuito ou sem valor - não adiciona ao subtotal", [
                        'lote_type' => $lote->type,
                        'lote_id' => $lote->id,
                        'lote_value' => $lote->value
                    ]);
                }
            }
            
            Log::info("Subtotal final calculado", [
                'subtotal' => $subtotal,
                'coupon_subtotal' => $coupon_subtotal,
                'total' => $total,
                'lotes_processados' => count($dicts),
                'dicts' => $dicts
            ]);
            
            // Aplicar cupom de desconto se existir (após calcular todos os subtotais)
            $coupon = $request->session()->get('coupon');
            if($coupon && $subtotal > 0) {
                // Verificar se o cupom se aplica a algum dos lotes selecionados
                $couponBelongs = false;
                foreach($dicts as $dict) {
                    $quantity = (int)($dict['lote_quantity'] ?? 0);
                    if ($quantity <= 0) continue;
                    
                    $lote = Lote::where('hash', $dict['lote_hash'])->first();
                    if ($lote) {
                        foreach($lote->coupons as $lote_cupom) {
                            if($coupon[0]['code'] == $lote_cupom->code) {
                                $couponBelongs = true;
                                break 2; // Sair dos dois loops
                            }
                        }
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
                } else {
                    $total = $subtotal;
                }
            } else {
                $total = $subtotal;
            }

            $request->session()->put('subtotal', $subtotal);
            $request->session()->put('coupon_subtotal', $coupon_subtotal);
            $request->session()->put('total', $total);

            $responseData = [
                'success' => 'Ajax request submitted successfully', 
                'subtotal' => 'R$ '.number_format($subtotal, 2, ',', '.'), 
                'coupon_subtotal' => 'R$ '.number_format($coupon_subtotal, 2, ',', '.'), 
                'total' => 'R$ '.number_format($total, 2, ',', '.')
            ];
            
            Log::info("Resposta final do getSubTotal", [
                'subtotal_raw' => $subtotal,
                'subtotal_formatted' => $responseData['subtotal'],
                'total_raw' => $total,
                'total_formatted' => $responseData['total'],
                'dicts_count' => count($dicts)
            ]);

            return response()->json($responseData);
        } else {
            // Se não houver lotes selecionados, retornar valores zerados
            $request->session()->put('subtotal', 0);
            $request->session()->put('coupon_subtotal', 0);
            $request->session()->put('total', 0);

            return response()->json(['success' => 'Ajax request submitted successfully', 'subtotal' => 'R$ 0,00', 'coupon_subtotal' => 'R$ 0,00', 'total' => 'R$ 0,00']);
        }
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
                ->whereIn('status', [1, 2]) // Apenas confirmados e pendentes
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

                // CORREÇÃO: Incluir ID do cupom para registro posterior
                $coupon = [[
                    'id' => $coupon->id, 
                    'code' => $coupon->code, 
                    'type' => $coupon->discount_type, 
                    'value' => $coupon->discount_value
                ]];
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

        if (!$event) {
            return redirect()->route('conference.index', $slug)->withErrors(['error' => 'Sessão expirada. Selecione os ingressos novamente.'])->setStatusCode(303);
        }

        // Validações obrigatórias (303 evita loop ao voltar ao resumo após POST)
        if (!$event_date) {
            return redirect()->route('conference.resume', $event->slug)->withErrors(['error' => 'Data do evento não foi selecionada.'])->setStatusCode(303);
        }

        if (!$dict_lotes || empty($dict_lotes)) {
            return redirect()->route('conference.resume', $event->slug)->withErrors(['error' => 'Nenhum ingresso foi selecionado.'])->setStatusCode(303);
        }

        if (!$total || $total < 0) {
            return redirect()->route('conference.resume', $event->slug)->withErrors(['error' => 'Valor total inválido.'])->setStatusCode(303);
        }

        // VALIDATION: Validate form fields
        $questions = Question::orderBy('order')->where('event_id', $event->id)->get();
        
        if ($questions->count() > 0 && $array_lotes_obj) {
            $errors = [];
            
            foreach ($array_lotes_obj as $k => $lote_obj) {
                foreach ($questions as $question) {
                    $fieldName = "newfield_" . ($k + 1) . "_" . $question->id;
                    $fieldValue = $input[$fieldName] ?? null;
                    
                    // Validar campos obrigatórios
                    if ($question->required == 1) {
                        if (empty($fieldValue) || $fieldValue === '0' || $fieldValue === '') {
                            $errors[] = "O campo '{$question->question}' é obrigatório para o participante #" . ($k + 1) . ".";
                        }
                    }
                    
                    // Validações específicas por tipo
                    if (!empty($fieldValue) && $fieldValue !== '0') {
                        switch ($question->option_id) {
                            case 13: // E-mail
                                if (!filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
                                    $errors[] = "O campo '{$question->question}' deve ser um e-mail válido para o participante #" . ($k + 1) . ".";
                                }
                                break;
                            case 5: // CPF
                                $cpf = preg_replace('/[^0-9]/', '', $fieldValue);
                                if (strlen($cpf) != 11) {
                                    $errors[] = "O campo '{$question->question}' deve ser um CPF válido para o participante #" . ($k + 1) . ".";
                                }
                                break;
                            case 6: // CNPJ
                                $cnpj = preg_replace('/[^0-9]/', '', $fieldValue);
                                if (strlen($cnpj) != 14) {
                                    $errors[] = "O campo '{$question->question}' deve ser um CNPJ válido para o participante #" . ($k + 1) . ".";
                                }
                                break;
                            case 9: // Número inteiro
                                if (!is_numeric($fieldValue) || (int)$fieldValue != $fieldValue) {
                                    $errors[] = "O campo '{$question->question}' deve ser um número inteiro para o participante #" . ($k + 1) . ".";
                                }
                                break;
                            case 10: // Número decimal
                                if (!is_numeric($fieldValue)) {
                                    $errors[] = "O campo '{$question->question}' deve ser um número para o participante #" . ($k + 1) . ".";
                                }
                                break;
                        }
                    }
                }
            }
            
            if (!empty($errors)) {
                return redirect()->route('conference.resume', $event->slug)->withErrors(['form_errors' => $errors])->withInput()->setStatusCode(303);
            }
        }

        // FINAL VALIDATION: Re-validate all lots before payment
        foreach ($dict_lotes as $dict) {
            $quantity = $dict['lote_quantity'];
            $lote = Lote::where('hash', $dict['lote_hash'])->first();

            if (!$lote) {
                return redirect()->route('conference.resume', $event->slug)->withErrors(['error' => 'Lote não encontrado.'])->setStatusCode(303);
            }

            // Verificar período de vendas
            $now = now();
            if ($lote->datetime_begin > $now || $lote->datetime_end < $now) {
                return redirect()->route('conference.resume', $event->slug)->withErrors(['error' => 'Este lote não está disponível para venda no momento.'])->setStatusCode(303);
            }

            // Verificar limites de quantidade
            if ($lote->limit_min > 0 && $quantity < $lote->limit_min) {
                return redirect()->route('conference.resume', $event->slug)->withErrors(['error' => "Quantidade mínima é {$lote->limit_min} ingressos."])->setStatusCode(303);
            }
            if ($lote->limit_max > 0 && $quantity > $lote->limit_max) {
                return redirect()->route('conference.resume', $event->slug)->withErrors(['error' => "Quantidade máxima é {$lote->limit_max} ingressos."])->setStatusCode(303);
            }

            // Verificar disponibilidade
            $soldTickets = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('order_items.lote_id', $lote->id)
                ->whereIn('orders.status', [1, 2]) // Apenas confirmados e pendentes
                ->sum('order_items.quantity');
            
            $availableTickets = $lote->quantity - $soldTickets;
            if ($quantity > $availableTickets) {
                return redirect()->route('conference.resume', $event->slug)->withErrors(['error' => "Apenas {$availableTickets} ingressos disponíveis neste lote."])->setStatusCode(303);
            }

            // Verificar limite por usuário
            if (Auth::check() && $lote->limit_max > 0) {
                $userPurchases = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.lote_id', $lote->id)
                    ->where('orders.participante_id', Auth::user()->id)
                    ->whereIn('orders.status', [1, 2]) // Apenas confirmados e pendentes
                    ->sum('order_items.quantity');
                
                if ($lote->limit_max > 0 && ($userPurchases + $quantity) > $lote->limit_max) {
                    return redirect()->route('conference.resume', $event->slug)->withErrors(['error' => "Você já comprou {$userPurchases} ingressos deste lote. Limite máximo: {$lote->limit_max}."])->setStatusCode(303);
                }
            }
        }

        // Bypass para eventos gratuitos
        if ((float) $total <= 0) {
            return $this->processFreeTicker($request, $event, $event_date, $coupon, $input);
        }

        // Criar pedido para pagamento com Transaction para garantir integridade
        $coupon_id = $coupon ? ($coupon->id ?? (is_array($coupon) && isset($coupon[0]['id']) ? $coupon[0]['id'] : null)) : null;

        try {
            $order_id = DB::transaction(function () use ($event, $event_date, $coupon_id) {
                return DB::table('orders')->insertGetId([
                    'hash' => md5(time() . uniqid() . md5(config('services.hash_secret'))),
                    'status' => 2, // pendente
                    'event_id' => $event->id,
                    'event_date_id' => $event_date->id,
                    'participante_id' => Auth::user()->id,
                    'coupon_id' => $coupon_id,
                    'created_at' => now(),
                ]);
            });

            $request->session()->put('order_id', $order_id);

            // Registrar uso do cupom na tabela pivot (para limit_buy funcionar)
            if ($coupon_id) {
                DB::table('orders_coupons')->insert([
                    'order_id' => $order_id,
                    'coupon_id' => $coupon_id,
                ]);
            }

            // Criar order_items corretamente
            $this->createOrderItems($order_id, $request);

            return redirect()->route('conference.paymentView', $event->slug)->setStatusCode(303);
        } catch (\Throwable $e) {
            Log::error('Erro ao criar pedido', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Erro ao processar pedido. Tente novamente.'])->setStatusCode(303);
        }
    }

    private function processFreeTicker($request, $event, $event_date, $coupon, $input)
    {
        $coupon_id = $coupon ? ($coupon->id ?? (is_array($coupon) && isset($coupon[0]['id']) ? $coupon[0]['id'] : null)) : null;

        // Usar Transaction para garantir integridade dos dados
        try {
            $order_id = DB::transaction(function () use ($event, $event_date, $coupon_id) {
                return DB::table('orders')->insertGetId([
                    'hash' => md5(time() . uniqid() . md5(config('services.hash_secret'))),
                    'status' => 1, // concluído
                    'gatway_payment_method' => 'free',
                    'gatway_status' => '1',
                    'event_id' => $event->id,
                    'event_date_id' => $event_date->id,
                    'participante_id' => Auth::user()->id,
                    'coupon_id' => $coupon_id,
                    'created_at' => now(),
                ]);
            });

            $this->createOrderItems($order_id, $request);

            // Registrar uso do cupom na tabela pivot (para limit_buy funcionar)
            if ($coupon_id) {
                DB::table('orders_coupons')->insert([
                    'order_id' => $order_id,
                    'coupon_id' => $coupon_id,
                ]);
            }

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
        } catch (\Throwable $e) {
            Log::error('Erro ao processar inscrição gratuita', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Erro ao processar inscrição. Tente novamente.']);
        }
    }

    private function createOrderItems($order_id, $request)
    {
        $array_lotes_obj = $request->session()->get('array_lotes_obj');
        $input = $request->all();

        if ($array_lotes_obj && is_array($array_lotes_obj)) {
            // Usar transaction para garantir atomicidade
            DB::transaction(function () use ($array_lotes_obj, $input, $order_id) {
                $kIndex = 1;
                
                foreach ($array_lotes_obj as $i => $entry) {
                    $lote = Lote::find($entry['id']);
                    if (!$lote) continue;

                    $order_item_id = DB::table('order_items')->insertGetId([
                        'hash' => md5((time() . uniqid() . $i) . md5(config('services.hash_secret'))),
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
            });
        }
    }

    public function paymentView(Request $request)
    {
        $event = $request->session()->get('event');
        $total = $request->session()->get('total');

        // Validar se o evento existe
        if (!$event) {
            return redirect()->route('conference.index', $request->route('slug'))->withErrors(['error' => 'Evento não encontrado. Sessão pode ter expirado.'])->setStatusCode(303);
        }

        // Validar se o evento é pago
        if ($event->paid == 1) {
            // Verificar se o organizador tem conta vinculada
            $organizerParticipant = DB::table('participantes_events')
                ->where('event_id', $event->id)
                ->where('role', 'admin')
                ->first(['participante_id']);
            
            if ($organizerParticipant) {
                $mpAccount = MpAccount::where('participante_id', $organizerParticipant->participante_id)->first();
                
                if (!$mpAccount || empty($mpAccount->access_token)) {
                    Log::warning('Payment view accessed but organizer account not linked', [
                        'event_id' => $event->id,
                        'organizer_id' => $organizerParticipant->participante_id
                    ]);
                    
                    return redirect()->route('conference.resume', $request->route('slug'))->withErrors([
                        'error' => 'O organizador deste evento ainda não vinculou sua conta do Mercado Pago. Entre em contato com o organizador do evento.'
                    ])->setStatusCode(303);
                }

                // Verificar se o token está expirado ou próximo de expirar
                if ($this->isTokenExpiredOrExpiring($mpAccount)) {
                    Log::info('Token expirado ou próximo de expirar, tentando renovar', [
                        'organizer_id' => $organizerParticipant->participante_id
                    ]);
                    
                    // Tentar renovar o token
                    $renewed = $this->renewAccessToken($mpAccount);
                    if (!$renewed) {
                        return redirect()->route('conference.resume', $request->route('slug'))->withErrors([
                            'error' => 'A conta do Mercado Pago do organizador precisa ser reautorizada. Entre em contato com o organizador do evento.'
                        ])->setStatusCode(303);
                    }
                }
            } else {
                Log::error('Event organizer not found for payment view', ['event_id' => $event->id]);
                return redirect()->route('conference.resume', $request->route('slug'))->withErrors(['error' => 'Organizador do evento não encontrado.'])->setStatusCode(303);
            }
        }

        return view('conference.payment', compact('event', 'total'));
    }

    /**
     * Verifica o status de um pagamento (usado para polling do PIX)
     * 
     * @param Request $request
     * @param int $order_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPaymentStatus(Request $request, $order_id)
    {
        try {
            // Buscar pedido - apenas do usuário autenticado
            $order = DB::table('orders')
                ->where('id', $order_id)
                ->where('participante_id', Auth::id())
                ->first();
            
            if (!$order) {
                return response()->json(['error' => 'Pedido não encontrado'], 404);
            }
            
            // Mapear status interno para status legível
            $statusMap = [
                1 => 'approved',
                2 => 'pending',
                3 => 'rejected',
                4 => 'cancelled',
                5 => 'refunded',
                6 => 'charged_back'
            ];
            
            $internalStatus = $statusMap[$order->status] ?? 'unknown';
            
            return response()->json([
                'status' => $order->gatway_status ?? $internalStatus,
                'internal_status' => $order->status,
                'approved' => $order->status == 1,
                'payment_method' => $order->gatway_payment_method
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error checking payment status', [
                'order_id' => $order_id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Erro ao verificar status'], 500);
        }
    }

    public function thanks(Request $request)
    {
        $coupon_discount = 0;
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
        // O Payment Brick pode enviar dados em formatos diferentes dependendo do método
        $paymentType = null;
        $formData = null;
        
        // Formato 1: { paymentType: "...", formData: {...} }
        if (isset($input['paymentType']) && isset($input['formData'])) {
            $paymentType = $input['paymentType'];
            $formData = $input['formData'];
        }
        // Formato 2: Dados diretos do Payment Brick (para cartão)
        elseif (isset($input['token']) || isset($input['payment_method_id'])) {
            // Se tem token, é cartão de crédito
            if (isset($input['token'])) {
                $paymentType = 'credit_card';
                $formData = $input;
            }
            // Se tem payment_method_id, pode ser PIX ou boleto
            elseif (isset($input['payment_method_id'])) {
                if ($input['payment_method_id'] === 'pix') {
                    $paymentType = 'bank_transfer';
                    $formData = $input;
                } elseif (in_array($input['payment_method_id'], ['bolbradesco', 'pec'])) {
                    $paymentType = 'ticket';
                    $formData = $input;
                }
            }
        }
        // Formato 3: Dados do Payment Brick com selectedPaymentMethod
        elseif (isset($input['selectedPaymentMethod'])) {
            if ($input['selectedPaymentMethod'] === 'bank_transfer' || (isset($input['formData']) && $input['formData']['payment_method_id'] === 'pix')) {
                $paymentType = 'bank_transfer';
                $formData = $input['formData'] ?? $input;
            } elseif ($input['selectedPaymentMethod'] === 'credit_card') {
                $paymentType = 'credit_card';
                $formData = $input['formData'] ?? $input;
            } elseif ($input['selectedPaymentMethod'] === 'ticket') {
                $paymentType = 'ticket';
                $formData = $input['formData'] ?? $input;
            }
        }
        
        if (!$paymentType || !$formData) {
            Log::warning('Invalid payment data structure', ['input' => $input]);
            return response()->json(['error' => 'Dados de pagamento inválidos'], 400);
        }
        
        // Normalizar input para o formato esperado
        $input['paymentType'] = $paymentType;
        $input['formData'] = $formData;

        // Rate limiting por usuário
        $userId = Auth::id();
        $cacheKey = "payment_attempt:{$userId}";
        $attempts = cache()->get($cacheKey, 0);
        
        if ($attempts >= 3) {
            Log::warning('Rate limit exceeded for user', ['user_id' => $userId, 'attempts' => $attempts]);
            return response()->json(['error' => 'Muitas tentativas de pagamento. Tente novamente em 5 minutos.'], 429);
        }

        cache()->put($cacheKey, $attempts + 1, now()->addMinutes(5));

        // Validar dados da sessão
        $order_id = $request->session()->get('order_id');
        $event = $request->session()->get('event');
        $total = $request->session()->get('total');
        $coupon_discount = (float) $request->session()->get('coupon_discount', 0);

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
            // Verificar se a ordem existe mas com status diferente
            $existingOrder = Order::where('id', $order_id)
                                ->where('participante_id', Auth::id())
                                ->first();
            
            if ($existingOrder) {
                Log::warning('Order already processed', [
                    'order_id' => $order_id,
                    'status' => $existingOrder->status,
                    'user_id' => $userId
                ]);
                return response()->json(['error' => 'Esta ordem já foi processada anteriormente.'], 400);
            }
            
            Log::warning('Order not found', [
                'order_id' => $order_id,
                'user_id' => $userId
            ]);
            return response()->json(['error' => 'Ordem não encontrada. Reinicie o processo de compra.'], 400);
        }
        
        // Buscar o organizador do evento
        $organizerParticipant = DB::table('participantes_events')
            ->where('event_id', $event->id)
            ->where('role', 'admin')
            ->first(['participante_id']);
        
        if (!$organizerParticipant) {
            Log::error('Event organizer not found', ['event_id' => $event->id]);
            return response()->json(['error' => 'Organizador do evento não encontrado'], 500);
        }
        
        // Buscar a conta do Mercado Pago vinculada ao organizador
        $mpAccount = MpAccount::where('participante_id', $organizerParticipant->participante_id)->first();
        
        if (!$mpAccount || empty($mpAccount->access_token)) {
            Log::error('Mercado Pago account not linked for organizer', [
                'event_id' => $event->id,
                'organizer_id' => $organizerParticipant->participante_id
            ]);
            return response()->json(['error' => 'Conta do Mercado Pago não vinculada ao organizador. Entre em contato com o organizador do evento.'], 500);
        }

        // Verificar se o token está expirado ou próximo de expirar
        if ($this->isTokenExpiredOrExpiring($mpAccount)) {
            Log::info('Token expirado ou próximo de expirar durante pagamento, tentando renovar', [
                'organizer_id' => $organizerParticipant->participante_id
            ]);
            
            $renewed = $this->renewAccessToken($mpAccount);
            if (!$renewed) {
                Log::error('Failed to renew expired token during payment', [
                    'organizer_id' => $organizerParticipant->participante_id
                ]);
                return response()->json(['error' => 'A conta do Mercado Pago do organizador precisa ser reautorizada. Entre em contato com o organizador do evento.'], 500);
            }
            
            // Recarregar o modelo para obter o novo token
            $mpAccount->refresh();
        }
        
        $accessToken = $mpAccount->access_token;

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

        // O $total da sessão JÁ contém o desconto do cupom (calculado em getSubTotal)
        // NÃO subtrair novamente para evitar desconto duplo
        $total_a_pagar = (float) $total;
        
        // Validar que total não seja negativo
        if ($total_a_pagar < 0) {
            Log::warning('Total amount is negative', ['total' => $total]);
            $total_a_pagar = 0;
        }
        
        Log::info('Payment total calculated', [
            'session_total' => $total,
            'total_a_pagar' => $total_a_pagar
        ]);
        
        // Calcular taxa (apenas para métodos que suportam application_fee)
        $config = Configuration::first();
        if (!$config) {
            Log::error('Configuration not found - ensure configuration record exists');
            return response()->json(['error' => 'Configuração do sistema não encontrada.'], 500);
        }
        $taxa_juros = $event->config_tax != 0.0 ? $event->config_tax : $config->tax;
        $application_fee = ($total_a_pagar * $taxa_juros);
        
        // Validar application_fee
        if ($application_fee < 0) {
            $application_fee = 0;
        }
        if ($application_fee >= $total_a_pagar) {
            Log::warning('Application fee is greater than or equal to transaction amount', [
                'application_fee' => $application_fee,
                'total' => $total_a_pagar
            ]);
            $application_fee = $total_a_pagar * 0.99; // Limitar a 99% do total
        }

        Log::info('Processing payment', [
            'payment_type' => $input['paymentType'],
            'order_id' => $order_id,
            'original_total' => $total,
            'total_a_pagar' => $total_a_pagar,
            'coupon_discount' => $coupon_discount,
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

                    // Usar email do formData ou do usuário autenticado
                    $payerEmail = isset($input['formData']['payer']['email']) && !empty($input['formData']['payer']['email']) 
                        ? $input['formData']['payer']['email'] 
                        : $user->email;

                    $paymentRequest = [
                        "transaction_amount" => (float) $total_a_pagar, // COM DESCONTO DO CUPOM
                        "token" => $input['formData']['token'],
                        "description" => 'Ingresso ' . $event->name,
                        "installments" => (int) $input['formData']['installments'],
                        "payment_method_id" => $input['formData']['payment_method_id'],
                        "application_fee" => (float) $application_fee,
                        "external_reference" => (string) $order_id, // CRÍTICO: Para webhook encontrar pedido
                        "notification_url" => config('app.url') . "/webhooks/mercado-pago/notification",
                        "payer" => [
                            "email" => $payerEmail,
                            "first_name" => $first_name,
                            "last_name" => $last_name,
                            "identification" => [
                                "type" => $input['formData']['payer']['identification']['type'],
                                "number" => $input['formData']['payer']['identification']['number'],
                            ]
                        ]
                    ];

                    // Adicionar issuer_id apenas se fornecido
                    if (isset($input['formData']['issuer_id']) && !empty($input['formData']['issuer_id'])) {
                        $paymentRequest["issuer_id"] = (int) $input['formData']['issuer_id'];
                    }

                    break;

                case 'bank_transfer':
                    // Obter CPF - pode vir do usuário ou do formData
                    $cpfSource = null;
                    $cpfRaw = null;
                    
                    // Tentar obter CPF do formData primeiro (se o Payment Brick enviou)
                    if (isset($input['formData']['payer']['identification']['number'])) {
                        $cpfRaw = $input['formData']['payer']['identification']['number'];
                        $cpfSource = 'formData';
                    } elseif (!empty($user->cpf)) {
                        $cpfRaw = $user->cpf;
                        $cpfSource = 'user_profile';
                    }
                    
                    if (empty($cpfRaw)) {
                        Log::warning('User CPF missing for PIX payment', [
                            'user_id' => $userId,
                            'has_user_cpf' => !empty($user->cpf),
                            'has_formdata_cpf' => isset($input['formData']['payer']['identification']['number'])
                        ]);
                        return response()->json(['error' => 'CPF é obrigatório para pagamento via PIX. Atualize seu perfil e tente novamente.'], 400);
                    }
                    
                    // Limpar CPF (remover caracteres não numéricos)
                    $cpf = preg_replace('/[^0-9]/', '', trim($cpfRaw));
                    
                    // Validar comprimento do CPF
                    if (strlen($cpf) != 11) {
                        Log::warning('Invalid CPF format for PIX payment', [
                            'user_id' => $userId,
                            'cpf_source' => $cpfSource,
                            'cpf_original' => $cpfRaw,
                            'cpf_cleaned' => $cpf,
                            'cpf_length' => strlen($cpf)
                        ]);
                        return response()->json(['error' => 'CPF inválido. O CPF deve ter 11 dígitos. Atualize seu perfil e tente novamente.'], 400);
                    }
                    
                    // Validar se o CPF não é uma sequência de números iguais (ex: 11111111111)
                    if (preg_match('/^(\d)\1{10}$/', $cpf)) {
                        Log::warning('CPF is a sequence of same digits', [
                            'user_id' => $userId,
                            'cpf_source' => $cpfSource,
                            'cpf' => substr($cpf, 0, 3) . '***'
                        ]);
                        return response()->json(['error' => 'CPF inválido. Atualize seu perfil com um CPF válido e tente novamente.'], 400);
                    }
                    
                    // Obter payment_method_id (pode estar em formData ou no nível raiz)
                    $paymentMethodId = $input['formData']['payment_method_id'] ?? 'pix';
                    
                    // Log do CPF que será enviado (sem mostrar completo por segurança)
                    Log::info('PIX payment - CPF prepared', [
                        'user_id' => $userId,
                        'cpf_source' => $cpfSource,
                        'cpf_length' => strlen($cpf),
                        'cpf_first_3' => substr($cpf, 0, 3) . '***',
                        'payment_method_id' => $paymentMethodId
                    ]);
                    
                    $paymentRequest = [
                        "transaction_amount" => (float) $total_a_pagar,
                        "description" => 'Ingresso ' . $event->name,
                        "payment_method_id" => $paymentMethodId,
                        "application_fee" => (float) $application_fee,
                        "external_reference" => (string) $order_id,
                        "notification_url" => config('app.url') . "/webhooks/mercado-pago/notification",
                        "payer" => [
                            "email" => $user->email,
                            "first_name" => $first_name,
                            "last_name" => $last_name,
                            "identification" => [
                                "type" => "CPF",
                                "number" => $cpf
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
                        "transaction_amount" => (float) $total_a_pagar, // COM DESCONTO DO CUPOM
                        "description" => 'Ingresso ' . $event->name,
                        "payment_method_id" => $input['formData']['payment_method_id'],
                        "application_fee" => (float) $application_fee,
                        "external_reference" => (string) $order_id, // CRÍTICO: Para webhook encontrar pedido
                        "notification_url" => config('app.url') . "/webhooks/mercado-pago/notification",
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

            // Atualizar status dos order_items e gerar purchase_hash se pagamento aprovado
            if ($payment->status === 'approved') {
                $order = DB::table('orders')->where('id', $order_id)->first();
                $orderItems = DB::table('order_items')->where('order_id', $order_id)->get();
                
                foreach ($orderItems as $item) {
                    // Gerar purchase_hash para cada order_item (usado no QR Code)
                    // Usar created_at formatado como string para garantir consistência
                    $createdAtStr = is_string($item->created_at) ? $item->created_at : (is_object($item->created_at) ? $item->created_at->format('Y-m-d H:i:s') : $item->created_at);
                    $purchaseHash = md5($order->hash . $item->hash . $item->number . $createdAtStr . md5(config('services.hash_secret')));
                    
                    DB::table('order_items')
                        ->where('id', $item->id)
                        ->update([
                            'status' => 1,
                            'purchase_hash' => $purchaseHash,
                            'updated_at' => now()
                        ]);
                }
            }

            Log::info('Payment processed successfully', [
                'payment_id' => $payment->id,
                'order_id' => $order_id,
                'status' => $payment->status
            ]);

            $responseData = [
                'id' => $payment->id,
                'status' => $payment->status,
                'detail' => $payment->status_detail ?? null
            ];

            // Adicionar detalhes do PIX se disponível
            if ($input['paymentType'] == 'bank_transfer' && ($payment->status === 'pending' || $payment->status === 'in_process')) {
                $pixDetails = DB::table('pix_details')->where('order_id', $order_id)->first();
                if ($pixDetails) {
                    $responseData['pix'] = [
                        'qr_code' => $pixDetails->qr_code,
                        'qr_code_base64' => $pixDetails->qr_code_base64,
                        'ticket_url' => $pixDetails->ticket_url,
                        'expiration_date' => $pixDetails->expiration_date
                    ];
                }
            }

            // Adicionar detalhes do boleto se disponível
            if ($input['paymentType'] == 'ticket' && ($payment->status === 'pending' || $payment->status === 'in_process')) {
                $boletoDetails = DB::table('boleto_details')->where('order_id', $order_id)->first();
                if ($boletoDetails) {
                    $responseData['boleto'] = [
                        'href' => $boletoDetails->href,
                        'line_code' => $boletoDetails->line_code,
                        'expiration_date' => $boletoDetails->expiration_date
                    ];
                }
            }

            return response()->json($responseData);

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
                // Erros do cliente - dados inválidos
                if (is_array($content)) {
                    if (isset($content['message'])) {
                        $errorMessage = $content['message'];
                    } elseif (isset($content['cause']) && is_array($content['cause'])) {
                        // Mercado Pago retorna erros detalhados em 'cause'
                        $causes = array_map(function($cause) {
                            return $cause['description'] ?? $cause['code'] ?? 'Erro desconhecido';
                        }, $content['cause']);
                        $errorMessage = implode('. ', $causes);
                    }
                }
                
                // Mensagens mais amigáveis para erros comuns
                if (strpos(strtolower($errorMessage), 'card') !== false || strpos(strtolower($errorMessage), 'cartão') !== false) {
                    $errorMessage = 'Dados do cartão inválidos. Verifique os dados e tente novamente.';
                } elseif (strpos(strtolower($errorMessage), 'insufficient') !== false || strpos(strtolower($errorMessage), 'insuficiente') !== false) {
                    $errorMessage = 'Saldo insuficiente. Verifique sua conta e tente novamente.';
                } elseif (strpos(strtolower($errorMessage), 'application_fee') !== false) {
                    $errorMessage = 'Erro na configuração do pagamento. Entre em contato com o organizador do evento.';
                }
            } elseif ($statusCode >= 500) {
                $errorMessage = 'Serviço temporariamente indisponível. Tente novamente em alguns instantes.';
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

    /**
     * Verifica se o token está expirado ou próximo de expirar
     * 
     * @param MpAccount $mpAccount
     * @return bool
     */
    private function isTokenExpiredOrExpiring($mpAccount)
    {
        // Se não tem expires_in, considerar como válido (compatibilidade com registros antigos)
        if (!$mpAccount->expires_in) {
            return false;
        }

        // expires_in é um timestamp Carbon ou DateTime
        $expiresAt = is_string($mpAccount->expires_in) 
            ? \Carbon\Carbon::parse($mpAccount->expires_in) 
            : $mpAccount->expires_in;

        // Considerar expirado se faltam menos de 7 dias para expirar
        // Isso dá tempo suficiente para renovar antes de expirar
        $daysUntilExpiration = now()->diffInDays($expiresAt, false);
        
        return $daysUntilExpiration < 7;
    }

    /**
     * Renova o access_token usando o refresh_token
     * 
     * @param MpAccount $mpAccount
     * @return bool
     */
    private function renewAccessToken($mpAccount)
    {
        // Se não tem refresh_token, não pode renovar
        if (empty($mpAccount->refresh_token)) {
            Log::warning('Cannot renew token: no refresh_token', [
                'mp_account_id' => $mpAccount->id
            ]);
            return false;
        }

        try {
            $client = new Client();
            
            $response = $client->post('https://api.mercadopago.com/oauth/token', [
                'form_params' => [
                    'client_id' => config('services.mercadopago.client_id'),
                    'client_secret' => config('services.mercadopago.client_secret'),
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $mpAccount->refresh_token,
                ],
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!isset($responseData['access_token'])) {
                Log::error('Failed to renew token: invalid response', [
                    'mp_account_id' => $mpAccount->id,
                    'response' => $responseData
                ]);
                return false;
            }

            // Atualizar o registro com os novos tokens
            $mpAccount->update([
                'access_token' => $responseData['access_token'],
                'refresh_token' => $responseData['refresh_token'] ?? $mpAccount->refresh_token,
                'expires_in' => isset($responseData['expires_in']) 
                    ? \Carbon\Carbon::now()->addSeconds($responseData['expires_in'])
                    : \Carbon\Carbon::now()->addDays(178),
            ]);

            Log::info('Token renewed successfully', [
                'mp_account_id' => $mpAccount->id,
                'organizer_id' => $mpAccount->participante_id
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error renewing access token', [
                'mp_account_id' => $mpAccount->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    public function oauth(Request $request){
     
        $code = $request->input('code');

        // Configuração da solicitação cURL
        $apiEndpoint = 'https://api.mercadopago.com/oauth/token';
        $requestData = [
            'client_id' => config('services.mercadopago.client_id', ''),
            'client_secret' => config('services.mercadopago.client_secret', ''),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('services.mercadopago.redirect_uri', ''),
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
