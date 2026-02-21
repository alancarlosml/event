<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use App\Mail\EventAdminControllerMail;
use App\Mail\GuestControllerMail;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Lote;
use App\Models\Message;
use App\Models\MpAccount;
use App\Models\Participante;
use App\Models\ParticipanteEvent;
use App\Models\Place;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Owner;
use App\Models\Question;
use App\Models\User;
use App\Models\State;

use App\Http\Controllers\MercadoPagoController;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventAdminController extends Controller
{
    /**
     * Verifica se o usuário tem acesso ao evento
     * Retorna true se for super admin ou se for admin do evento
     */
    private function hasAccessToEvent($eventId)
    {
        $user = Auth::guard('participante')->user();
        
        if ($user->hasRole('super_admin')) {
            return true;
        }

        $participanteEvent = ParticipanteEvent::where('event_id', $eventId)
            ->where('participante_id', $user->id)
            ->where('role', 'admin')
            ->where('status', 1)
            ->first();

        return $participanteEvent !== null;
    }

    public function myRegistrations()
    {
        // Otimizado: Usar Eloquent com eager loading para evitar N+1 queries
        $orders = Order::with([
            'orderItems.lote',
            'eventDate.event.place'
        ])
            ->where('participante_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('painel_admin.my_registrations', compact('orders'));
    }

    public function myEvents()
    {
        $user = Auth::guard('participante')->user();
        $isSuperAdmin = $user->hasRole('super_admin');

        // Otimizado: Usar Eloquent com eager loading
        $eventsQuery = Event::with([
            'place',
            'lotes',
            'eventDates',
            'participantesEvents.participante'
        ]);

        // Se não for super admin, filtrar apenas eventos do usuário
        if (!$isSuperAdmin) {
            $eventsQuery->whereHas('participantesEvents', function ($q) use ($user) {
                $q->where('participante_id', $user->id)
                  ->where('status', 1);
            });
        }

        $events = $eventsQuery->orderBy('created_at', 'desc')->get();

        // Processar dados para compatibilidade com a view
        $events->transform(function ($event) use ($user, $isSuperAdmin) {
            $event->place_name = $event->place?->name;
            $event->date_event_min = $event->eventDates->min('date');
            $event->date_event_max = $event->eventDates->max('date');
            $event->event_date = $event->date_event_min;
            
            $admin = $event->participantesEvents->where('role', 'admin')->first();
            $event->admin_name = $admin?->participante?->name;
            $event->admin_email = $admin?->participante?->email;
            $event->participante_name = $event->admin_name;
            
            $event->lote_name = $event->lotes->first()?->name;
            
            // Determinar o role do usuário atual para este evento
            if ($isSuperAdmin) {
                $event->role = 'admin';
            } else {
                $userRelation = $event->participantesEvents->where('participante_id', $user->id)->first();
                $event->role = $userRelation?->role;
            }
            
            return $event;
        });

        return view('painel_admin.my_events', compact('events', 'isSuperAdmin'));
    }

    public function dashboard()
    {
        $user = Auth::guard('participante')->user();
        $isSuperAdmin = $user->hasRole('super_admin');

        if (!$isSuperAdmin) {
            return redirect()->route('event_home.my_events');
        }

        // Reutilizar lógica do DashboardController
        $event_count = Event::orderBy('event_dates.date', 'asc')
            ->leftJoin('lotes', 'lotes.event_id', '=', 'events.id')
            ->leftJoin('places', 'places.id', '=', 'events.place_id')
            ->leftJoin('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->leftJoin(DB::raw("(SELECT participantes.name,
                                        participantes.email, 
                                        participantes_events.event_id
                                from participantes
                                inner join participantes_events on participantes.id = participantes_events.participante_id
                                where participantes_events.role = 'admin' and participantes_events.status = 1
                                ) as x"), function ($join) {
                    $join->on('x.event_id', '=', 'events.id');
                })
            ->where('events.status', '1')
            ->select(
                'events.*',
                'places.name as place_name',
                'lotes.name as lote_name',
                'event_dates.date as event_date',
                'x.name as participante_name',
                DB::raw('MIN(event_dates.date) as date_event_min'),
                DB::raw('MAX(event_dates.date) as date_event_max'),
                'x.name as admin_name',
                'x.email as admin_email'
            )
            ->groupBy('events.id')
            ->get();

        $ingressos_confirmados = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->where('orders.status', 1)
            ->select('orders.id as order_id', 'orders.status as situacao', 'events.name as event_name', 'orders.hash as order_hash', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.name as participante_name', 'participantes.email as participante_email', 'participantes.cpf as participante_cpf')
            ->orderBy('orders.created_at', 'desc')
            ->groupBy('orders.id')
            ->get();

        $ingressos_pendentes = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->where('orders.status', 2)
            ->select('orders.id as order_id', 'orders.status as situacao', 'events.name as event_name', 'orders.hash as order_hash', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.name as participante_name', 'participantes.email as participante_email', 'participantes.cpf as participante_cpf')
            ->orderBy('orders.created_at', 'desc')
            ->groupBy('orders.id')
            ->get();

        $ingressos_cancelados = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->where('orders.status', 3)
            ->select('orders.id as order_id', 'orders.status as situacao', 'events.name as event_name', 'orders.hash as order_hash', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.name as participante_name', 'participantes.email as participante_email', 'participantes.cpf as participante_cpf')
            ->orderBy('orders.created_at', 'desc')
            ->groupBy('orders.id')
            ->get();

        $total_confirmado = DB::table('lotes')
            ->join('order_items', 'lotes.id', '=', 'order_items.lote_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->where('lotes.type', 0)
            ->where('orders.status', 1)
            ->selectRaw(
                "sum(case 
                    when coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value)
                    when coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value 
                    when coupons.discount_type is null and coupons.code is null then order_items.value
                    end
                ) as total_confirmado"
            )
            ->first();

        $total_pendente = DB::table('lotes')
            ->join('order_items', 'lotes.id', '=', 'order_items.lote_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->where('lotes.type', 0)
            ->where('orders.status', 2)
            ->selectRaw(
                "sum(case 
                    when coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value)
                    when coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value 
                    when coupons.discount_type is null and coupons.code is null then order_items.value
                    end
                ) as total_pendente"
            )
            ->first();

        return view('painel_admin.dashboard', compact(
            'event_count', 
            'ingressos_confirmados', 
            'ingressos_cancelados', 
            'ingressos_pendentes', 
            'total_confirmado', 
            'total_pendente',
            'isSuperAdmin'
        ));
    }

    public function eventClone(Request $request, $hash)
    {

        $event = Event::where('hash', $hash)->first();

        $newEvent = $event->replicate()->fill([
            'name' => $event->name . ' - CÓPIA (' . date('Y-m-d H:i:s')  . ')',
            'slug' => Str::slug($event->name . ' - CÓPIA (' . date('Y-m-d H:i:s')  . ')', '-'),
            'hash' => md5($event->name . $event->description . date('Y-m-d H:i:s') . md5('evento_duplicado')),
            'status' => 0,
        ]);

        $newEvent->save();

        DB::table('participantes_events')->insert([
            'hash' => md5(Auth::user()->name . date('Y-m-d H:i:s') . md5('evento_duplicado')),
            'role' => 'admin',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'participante_id' => Auth::user()->id,
            'event_id' => $newEvent->id,
        ]);

        $request->session()->put('event', $newEvent->toArray());

        //ENVIAR EMAIL - EVENTO CRIADO
        try {
            Mail::to(Auth::user()->email)->send(new EventAdminControllerMail($newEvent, 'Evento criado com sucesso', 'criado'));
        } catch (\Exception $mailException) {
            Log::warning('Falha ao enviar e-mail de duplicação de evento: ' . $mailException->getMessage(), [
                'event_id' => $newEvent->id,
                'user_email' => Auth::user()->email,
            ]);
        }

        return redirect()->route('event_home.my_events_edit', $newEvent->hash);
    }

    public function myEventsShow($hash)
    {
        $event = Event::where('hash', $hash)->first();

        if (!$event) {
            abort(404, 'Evento não encontrado');
        }

        if (!$this->hasAccessToEvent($event->id)) {
            abort(403, 'Você não tem permissão para acessar este evento');
        }

        // Recalcular final_value dos lotes existentes baseado em tax_service
        $config = Configuration::findOrFail(1);
        $taxa_juros = $config->tax;
        
        foreach($event->lotes as $lote) {
            if($lote->type == 0 && $lote->value > 0) {
                // Recalcular taxa se necessário
                $taxa_calculada = doubleval($lote->value) * $taxa_juros;
                
                // Recalcular final_value baseado em tax_service
                if($lote->tax_service == 0) {
                    // Taxa paga pelo participante: soma ao valor
                    $final_value_calculado = doubleval($lote->value) + $taxa_calculada;
                } else {
                    // Taxa paga pelo organizador: subtrai do valor
                    $final_value_calculado = doubleval($lote->value) - $taxa_calculada;
                }
                
                // Atualizar apenas se o valor estiver incorreto
                if(abs($lote->final_value - $final_value_calculado) > 0.01) {
                    $lote->tax = $taxa_calculada;
                    $lote->final_value = $final_value_calculado;
                    $lote->save();
                }
            }
        }

        // Recarregar o evento com os lotes atualizados
        $event->refresh();

        $isSuperAdmin = Auth::guard('participante')->user()->hasRole('super_admin');

        return view('painel_admin.my_events_show', compact('event', 'isSuperAdmin'));
    }

    public function myEventsEdit(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();

        if (!$event) {
            abort(404, 'Evento não encontrado');
        }

        if (!$this->hasAccessToEvent($event->id)) {
            abort(403, 'Você não tem permissão para editar este evento');
        }
        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();
        $options = Option::orderBy('id')->get();
        $questions = Question::with('option')->orderBy('order')->where('event_id', $event->id)->get();

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

        // Carregar dados do local do evento
        $place = $event->place;
        
        // Adicionar dados à sessão para o template unificado
        $request->session()->put('dates', $dates->toArray());
        $request->session()->put('event', $event->toArray());
        $request->session()->put('event_id', $event->id);
        $request->session()->put('place', $place ? $place->toArray() : null);
        
        // Incluir formatted_options ao salvar questões na sessão
        $questionsArray = $questions->map(function($question) {
            $questionArray = $question->toArray();
            $questionArray['formatted_options'] = $question->formatted_options;
            return $questionArray;
        })->toArray();
        $request->session()->put('questions', $questionsArray);

        // Verificar conta do Mercado Pago
        $mercadoPagoResponse = app(MercadoPagoController::class)->checkLinkedAccount($request);
        $mercadoPagoLinked = [
            'linked' => $mercadoPagoResponse->getData()->linked,
            'id' => $mercadoPagoResponse->getData()->id,
        ];

        // Usar o template unificado
        return view('painel_admin.create_event', compact('categories', 'states', 'options', 'event', 'place', 'dates', 'questions', 'mercadoPagoLinked'));
    }

    public function updateEvent(Request $request, $hash)
    {
        try {
            DB::beginTransaction();

            // Localiza o evento pelo hash e garante que esteja na sessão
            $event = Event::where('hash', $hash)->firstOrFail();
            $request->session()->put('event', $event->toArray());
            $request->session()->put('event_id', $event->id);

            // Atualiza dados principais do evento
            $event = $this->saveEvent($request);
            
            // Verificar se o retorno é um RedirectResponse (erro de validação)
            if ($event instanceof \Illuminate\Http\RedirectResponse) {
                DB::rollBack();
                return $event;
            }

            // Atualiza/relaciona lugar, datas e perguntas
            $this->savePlace($request, $event);
            $this->saveEventDates($request, $event);
            $this->saveQuestions($request, $event);
            $this->saveUserAdmin($request, $event);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar evento: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'hash' => $hash,
                'request_data' => $request->except(['password']),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar evento: ' . $e->getMessage()]);
        }

        return redirect()->route('event_home.create.step.two');
    }

    public function createEventLink(Request $request)
    {
        $request->session()->forget([
            'event',
            'event_id',
            // 'eventDate',
            'dates',
            'place',
            'place_id',
            'uf',
            'lotes',
            'lote_id',
        ]);

        return redirect()->route('event_home.create.step.one');
    }

    public function create_event(Request $request)
    {
        $categories = Category::orderBy('description')->get();
        $states = State::orderBy('name')->get();
        
        $options = Option::orderBy('id')->get();
        // $questions = Question::orderBy('order')->get();

        $eventSession = $request->session()->get('event');
        $placeSession = $request->session()->get('place');
        // $eventDate = $request->session()->get('eventDate');

        // Se há evento na sessão, buscar o objeto completo do banco
        $event = null;
        $place = null;
        if(isset($eventSession) && isset($eventSession['id'])) {
            $event = Event::with('place')->find($eventSession['id']);
            if($event && $event->place) {
                $place = $event->place;
            } elseif(isset($placeSession) && isset($placeSession['id'])) {
                $place = Place::find($placeSession['id']);
            }
        }

        $eventDate = null;
        if(isset($event)) {
            $eventDate = EventDate::where('event_id', $event->id)
                ->selectRaw('id, date, DATE_FORMAT(time_begin, "%H:%i") time_begin, DATE_FORMAT(time_end, "%H:%i") time_end')
                ->where('status', '1')
                ->get();

            if(isset($eventDate)) {
                foreach($eventDate as $date) {
                    $date['id'] = $date['id'];
                    $date['date'] = date('d/m/Y', strtotime($date['date']));
                }
            }
        }

        $questions = collect([]);
        if($event) {
            $sessionQuestions = $request->session()->get('questions');
            if($sessionQuestions) {
                $questions = collect($sessionQuestions);
            } else {
                // Se não houver questões na sessão, buscar do banco de dados com relacionamentos
                $questions = Question::with('option')->orderBy('order')->where('event_id', $event->id)->get();
            }
        }

        // Adicionei uma verificação para saber se o usuário já tem uma conta vinculada ao Mercado Pago
        $mercadoPagoResponse  = app(MercadoPagoController::class)->checkLinkedAccount($request);
        $mercadoPagoLinked = [
            'linked' => $mercadoPagoResponse->getData()->linked,
            'id' => $mercadoPagoResponse->getData()->id,
        ];

        return view('painel_admin.create_event', compact('categories', 'states', 'options', 'event', 'place', 'eventDate', 'questions', 'mercadoPagoLinked'));
    }

    public function postCreateStepOne(Request $request)
    {
        try {
            // Comece uma transação para garantir a integridade dos dados.
            DB::beginTransaction();

            $event = $this->saveEvent($request);
            
            // Verificar se o retorno é um RedirectResponse (erro de validação)
            if ($event instanceof \Illuminate\Http\RedirectResponse) {
                DB::rollback();
                return $event;
            }
            
            $request->session()->put('event', $event->toArray());

            $place = $this->savePlace($request, $event);
            $request->session()->put('place', $place ? $place->toArray() : null);

            $this->saveEventDates($request, $event);
            $this->saveQuestions($request, $event);
            $this->saveUserAdmin($request, $event);

            // Todos os dados foram salvos com sucesso. Faça o commit da transação.
            DB::commit();

        } catch (Exception $e) {
            // Alguma coisa deu errado. Reverter as operações de banco de dados.
            DB::rollback();

            // Log do erro para debugging
            Log::error('Erro ao criar evento: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['password']),
                'trace' => $e->getTraceAsString()
            ]);

            // Retornar erro específico para o usuário
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar evento: ' . $e->getMessage()]);
        }

        return redirect()->route('event_home.create.step.two');
    }

    private function saveEvent(Request $request)
    {
        if(empty($request->session()->get('event'))) {

            $validatedDataEvent = $request->validate([
                'name' => 'required|min:3|max:255',
                'hash' => 'string',
                'slug' => 'required|min:2|max:100|unique:events',
                'subtitle' => 'nullable|string|max:255',
                'description' => 'required|min:10',
                'category' => 'required|exists:categories,id',
                'area_id' => 'required|exists:areas,id',
                'max_tickets' => 'required|integer|min:1',
                'contact' => 'required|email',
                'paid' => 'required|in:0,1',
                'mercadopago_link' => 'nullable|string',
                'place_id_hidden' => 'nullable|exists:places,id',
                'admin_id' => 'required|exists:participantes,id',
                'status' => 'string',
            ], [
                'name.required' => 'O nome do evento é obrigatório.',
                'name.min' => 'O nome do evento deve ter pelo menos 3 caracteres.',
                'name.max' => 'O nome do evento não pode ter mais de 255 caracteres.',
                'slug.required' => 'A URL do evento é obrigatória.',
                'slug.min' => 'A URL do evento deve ter pelo menos 2 caracteres.',
                'slug.max' => 'A URL do evento não pode ter mais de 100 caracteres.',
                'slug.unique' => 'A URL do evento já está em uso.',
                'description.required' => 'A descrição do evento é obrigatória.',
                'description.min' => 'A descrição do evento deve ter pelo menos 10 caracteres.',
                'category.required' => 'A categoria do evento é obrigatória.',
                'category.exists' => 'A categoria selecionada não existe.',
                'area_id.required' => 'A área do evento é obrigatória.',
                'area_id.exists' => 'A área selecionada não existe.',
                'max_tickets.required' => 'O número máximo de ingressos é obrigatório.',
                'max_tickets.integer' => 'O número máximo de ingressos deve ser um número inteiro.',
                'max_tickets.min' => 'O número máximo de ingressos deve ser pelo menos 1.',
                'contact.required' => 'O email de contato é obrigatório.',
                'contact.email' => 'O email de contato deve ser um email válido.',
                'paid.required' => 'O tipo de pagamento é obrigatório.',
                'paid.in' => 'O tipo de pagamento deve ser pago ou gratuito.',
                'admin_id.required' => 'O organizador é obrigatório.',
                'admin_id.exists' => 'O organizador selecionado não existe.',
            ]);

            // dd($validatedDataEvent);

            // Validar se evento é pago e se tem conta Mercado Pago vinculada
            if ($validatedDataEvent['paid'] == 1) {
                $mpAccount = MpAccount::where('participante_id', $validatedDataEvent['admin_id'])->first();
                if (!$mpAccount || empty($mpAccount->access_token)) {
                    return back()
                        ->withInput()
                        ->withErrors(['paid' => 'Para criar um evento pago, é necessário vincular sua conta do Mercado Pago primeiro. Clique em "Vincular conta" na seção "Carteira de pagamento".']);
                }
            }

            $validatedDataEvent['hash'] = md5($validatedDataEvent['name'] . $validatedDataEvent['description'] . md5(config('services.hash_secret')));
            $validatedDataEvent['slug'] = Str::slug($validatedDataEvent['slug'], '-');
            $validatedDataEvent['status'] = 0;
            $validatedDataEvent['place_id'] = $validatedDataEvent['place_id_hidden'];

            // dd($validatedDataEvent);

            $event = new Event();
            $event->fill($validatedDataEvent);
            $event->save();
            // dd($event);
            $event_id = $event->id;
            $request->session()->put('event', $event->toArray());
            $request->session()->put('event_id', $event_id);

            DB::table('participantes_events')->insert([
                'event_id' => $event_id,
                'participante_id' => $validatedDataEvent['admin_id'],
                'hash' => md5($validatedDataEvent['name'] . $validatedDataEvent['description'] . md5('cachorronoel')),
                'role' => 'admin',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            //ENVIAR EMAIL - EVENTO CRIADO
            try {
                Mail::to(Auth::user()->email)->send(new EventAdminControllerMail($event, 'Evento criado com sucesso', 'criado'));
            } catch (\Exception $mailException) {
                Log::warning('Falha ao enviar e-mail de criação de evento: ' . $mailException->getMessage(), [
                    'event_id' => $event->id,
                    'user_email' => Auth::user()->email,
                ]);
            }

            return $event;

        } else {

            $event_id = $request->session()->get('event_id');
            $event = Event::findOrFail($event_id);

            $validatedDataEvent = $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:events,slug,' . $event_id . ',id',
                'description' => 'required',
                'subtitle' => 'string',
                'category' => 'required',
                'area_id' => 'required',
                'max_tickets' => 'required',
                'contact' => 'required',
                'paid' => 'required',
                'mercadopago_link' => 'nullable',
                'place_id_hidden' => 'nullable',
                'status' => 'string',
                'new_field' => 'required',
                'new_field_id' => 'nullable',
            ],[
                'name.required' => 'O nome do evento é obrigatório.',
                'slug.required' => 'A URL do evento é obrigatório.',
                'slug.unique' => 'A URL do evento já está em uso.',
                'description.required' => 'A descrição do evento é obrigatória.',
                'area_id.required' => 'A área do evento é obrigatória.',
                'max_tickets.required' => 'O número máximo de ingressos é obrigatório.',
                'contact.required' => 'O email de contato é obrigatório.',
                'new_field.required' => 'As perguntas são obrigatórias.',
                'paid.required' => 'O evento deve ser pago ou gratuito.',
            ]);

            $validatedDataEvent['slug'] = Str::slug($validatedDataEvent['slug'], '-');
            $validatedDataEvent['place_id'] = $validatedDataEvent['place_id_hidden'];

            // if(isset($validatedDataEvent['status'])){
            //     $validatedDataEvent['status'] = 1;
            // }else{
            //     $validatedDataEvent['status'] = 0;
            // }

            $event->fill($validatedDataEvent);
            $event->save();
            $request->session()->put('event', $event->toArray());

            // Envio de e-mail envolvido em try-catch para não quebrar o fluxo
            try {
                Mail::to(Auth::user()->email)->send(new EventAdminControllerMail($event, 'Evento editado com sucesso', 'editado'));
            } catch (\Exception $mailException) {
                Log::warning('Falha ao enviar e-mail de edição de evento: ' . $mailException->getMessage(), [
                    'event_id' => $event->id,
                    'user_email' => Auth::user()->email,
                ]);
            }

            return $event;
        }

        return null;
    }

    private function savePlace(Request $request, Event $event)
    {
        $validatedDataPlace = $request->validate([
            'place_id_hidden' => 'nullable',
            'place_name' => 'required',
            'address' => 'required',
            'number' => 'required',
            'district' => 'required',
            'complement' => 'nullable',
            'zip' => 'required',
            'city_id_hidden' => 'required',
        ],[
            'place_name.required' => 'O nome do lugar é obrigatório.',
            'address.required' => 'O endereço do lugar é obrigatório.',
            'number.required' => 'O número do lugar é obrigatório.',
            'district.required' => 'O bairro do lugar é obrigatório.',
            'zip.required' => 'O CEP do lugar é obrigatório.',
            'city_id_hidden.required' => 'A cidade do lugar é obrigatória.',
        ]);

        // dd($validatedDataPlace);

        $validatedDataPlace['name'] = $validatedDataPlace['place_name'];
        $validatedDataPlace['city_id'] = $validatedDataPlace['city_id_hidden'];
        $validatedDataPlace['place_id'] = $validatedDataPlace['place_id_hidden'];
        $validatedDataPlace['status'] = 1;

        // dd($validatedDataPlace);

        $event_id = $event->id;

        // Verificar se place_id existe (diferente de null e string vazia)
        if(!empty($validatedDataPlace['place_id'])) {
            $event = Event::where('id', $event_id)->update(['place_id' => $validatedDataPlace['place_id']]);
            $place = Place::findOrFail($validatedDataPlace['place_id']);
            // $request->session()->put('place', $place);
            // $request->session()->put('place_id', $validatedDataPlace['place_id']);
            // $request->session()->put('uf', $place->get_city()->uf);
            return $place;
        } else {
            $place = new Place();
            $place->fill($validatedDataPlace);
            $place->save();
            $place_id = $place->id;
            Event::where('id', $event_id)->update(['place_id' => $place_id]);
            // $request->session()->put('place', $place);
            // $request->session()->put('place_id', $place_id);
            return $place;
        }

        return null;
    }

    private function saveEventDates(Request $request, Event $event)
    {
        $validatedDataEventDate = $request->validate([
            'date.*' => 'required|date_format:d/m/Y',
            'time_begin.*' => 'required|date_format:H:i',
            'time_end.*' => 'required|date_format:H:i',
            'date_id.*' => 'nullable|integer',
        ],[
            'date.*.required' => 'A data do evento é obrigatória.',
            'time_begin.*.required' => 'A hora de início do evento é obrigatória.',
            'time_end.*.required' => 'A hora de término do evento é obrigatória.',
        ]);

        $date_ids = $validatedDataEventDate['date_id'];
        $dates = $validatedDataEventDate['date'];
        $times_begin = $validatedDataEventDate['time_begin'];
        $times_end = $validatedDataEventDate['time_end'];
        
        // Validar que time_end > time_begin para cada data
        foreach ($times_begin as $index => $begin) {
            if (isset($times_end[$index]) && strtotime($begin) >= strtotime($times_end[$index])) {
                throw new Exception('A hora de termino deve ser maior que a hora de inicio para a data ' . ($index + 1) . '.');
            }
        }
        
        $event_id = $event->id;

        // Verifica se está faltando um id para excluir no banco
        $date_ids_db = EventDate::where('event_id', $event_id)
            ->where('status', '1')
            ->get()
            ->map
            ->only('id')
            ->toArray();

        foreach($date_ids_db as $date_id_db) {

            if( ! in_array($date_id_db['id'], $date_ids)) {
                // Verificar se existem pedidos vinculados a esta data antes de excluir
                $ordersCount = Order::where('event_date_id', $date_id_db['id'])->count();
                if ($ordersCount > 0) {
                    throw new Exception('Nao e possivel excluir a data pois existem ' . $ordersCount . ' pedido(s) vinculado(s).');
                }
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
                    'event_id' => $event_id,
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
    }

    private function saveQuestions(Request $request, Event $event)
    {
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

        foreach($questions_ids_db as $question_id_db) {

            if( ! in_array($question_id_db['id'], $field_ids)) {
                DB::table('questions')->where('id', $question_id_db['id'])->delete();
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

                $option = Option::where('option', $result_field[0] ?? '')->first();
                
                if (!$option) {
                    Log::warning('Tipo de campo não encontrado: ' . ($result_field[0] ?? 'vazio'), [
                        'event_id' => $event_id,
                        'field' => $field['question']
                    ]);
                    continue; // Pular este campo se o tipo não for encontrado
                }
                
                $question = Question::create([
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

        $questions = Question::with('option')->orderBy('order')->where('event_id', $event_id)->get();

        // Incluir formatted_options ao salvar questões na sessão
        $questionsArray = $questions->map(function($question) {
            $questionArray = $question->toArray();
            $questionArray['formatted_options'] = $question->formatted_options;
            return $questionArray;
        })->toArray();
        $request->session()->put('questions', $questionsArray);
    }

    private function saveUserAdmin(Request $request, Event $event)
    {
        $event_id = $event->id;
        $participante_evento = ParticipanteEvent::where('role', 'admin')
            ->where('participante_id', Auth::user()->id)
            ->where('event_id', $event_id)
            ->get();

        if($participante_evento->count() == 0) {
            DB::table('participantes_events')->insert([
                'hash' => md5(Auth::user()->name . date('Y-m-d H:i:s') . md5(config('services.hash_secret'))),
                'role' => 'admin',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'participante_id' => Auth::user()->id,
                'event_id' => $event_id,
            ]);
        }
    }

    public function createStepTwo(Request $request)
    {
        $event_id = $request->session()->get('event_id');

        if($event_id != null) {

            $lotesSession = $request->session()->get('lotes');
            if(empty($lotesSession)) {
                $lotes = Lote::orderBy('order')
                    ->where('event_id', $event_id)
                    ->get();
            } else {
                // Se há lotes na sessão como array, buscar do banco
                $lotes = Lote::orderBy('order')
                    ->where('event_id', $event_id)
                    ->get();
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

        if($input['type'] == 0) {
            $validatedData = $this->validate($request, [
                'event_id' => 'nullable',
                'type' => 'required|integer',
                'tax_parcelamento' => 'required|integer',
                'tax_service' => 'required|integer',
                'value' => 'required',
                'name' => 'required',
                'quantity' => 'required',
                'description' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gte:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'form_pagamento' => 'nullable',
                'visibility' => 'required',
            ],[
               'type.required' => 'Tipo de lote é obrigatório',
               'tax_parcelamento.required' => 'Taxa de parcelamento é obrigatória',
               'tax_service.required' => 'Taxa de serviço é obrigatória',
               'value.required' => 'Valor do lote é obrigatório',
               'name.required' => 'Nome do lote é obrigatório',
               'quantity.required' => 'Quantidade é obrigatória',
               'description.required' => 'Descrição do lote é obrigatória',
               'limit_min.required' => 'Limite mínimo de 1 é obrigatório',
               'limit_max.required' => 'Limite máximo maior ou igual ao limíte mínimo é obrigatório',
               'datetime_begin.required' => 'Data de inicio é obrigatória',
               'datetime_end.required' => 'Data de fim é obrigatória',
               'form_pagamento.required' => 'Forma de pagamento é obrigatória',
               'visibility.required' => 'Visibilidade do lote é obrigatória',
            ]);
        } else {
            $validatedData = $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|integer',
                'quantity' => 'required',
                'visibility' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gte:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'event_id' => 'nullable',
            ],[
                'type.required' => 'Tipo de lote é obrigatório',
                'tax_parcelamento.required' => 'Taxa de parcelamento é obrigatória',
                'tax_service.required' => 'Taxa de serviço é obrigatória',
                'value.required' => 'Valor do lote é obrigatório',
                'name.required' => 'Nome do lote é obrigatório',
                'quantity.required' => 'Quantidade é obrigatória',
                'description.required' => 'Descrição do lote é obrigatória',
                'limit_min.required' => 'Limite mínimo de 1 é obrigatório',
                'limit_max.required' => 'Limite máximo maior ou igual ao limíte mínimo é obrigatório',
                'datetime_begin.required' => 'Data de inicio é obrigatória',
                'datetime_end.required' => 'Data de fim é obrigatória',
                'form_pagamento.required' => 'Forma de pagamento é obrigatória',
                'visibility.required' => 'Visibilidade do lote é obrigatória',
            ]);
        }

        // dd($validatedData);

        $event_id = $request->session()->get('event_id');
        $number_lotes = Lote::where('event_id', $event_id)->count();

        $validatedData['event_id'] = $event_id;

        $validatedData['order'] = $number_lotes + 1;
        $validatedData['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $validatedData['datetime_begin'])));
        $validatedData['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $validatedData['datetime_end'])));

        if($validatedData['type'] == 0) {
            $validatedData['tax'] = doubleval($validatedData['value']) * $taxa_juros;
            // Se a taxa é paga pelo participante (tax_service == 0), soma ao valor
            // Se a taxa é paga pelo organizador (tax_service == 1), subtrai do valor
            if(isset($validatedData['tax_service']) && $validatedData['tax_service'] == 0) {
                $validatedData['final_value'] = doubleval($validatedData['value']) + doubleval($validatedData['value']) * $taxa_juros;
            } else {
                $validatedData['final_value'] = doubleval($validatedData['value']) - doubleval($validatedData['value']) * $taxa_juros;
            }
            $validatedData['form_pagamento'] = implode(',', $validatedData['form_pagamento']);
        }

        $validatedData['hash'] = md5($validatedData['name'] . $validatedData['description'] . md5(config('services.hash_secret')));

        $lote = new Lote();
        $lote->fill($validatedData);
        $lote->save();
        $lote_id = $lote->id;

        $lotes = Lote::orderBy('order')
            ->where('event_id', $event_id)
            ->get();

        $request->session()->put('lotes', $lotes->toArray());
        $request->session()->put('lote_id', $lote_id);

        return redirect()->route('event_home.create.step.two');
    }

    public function editLote($hash)
    {

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

        if($input['type'] == 0) {
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
                'limit_max' => 'required|gte:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'visibility' => 'required',
                'event_id' => 'nullable',
            ],[
                'type.required' => 'Tipo de lote é obrigatório',
                'tax_parcelamento.required' => 'Taxa de parcelamento é obrigatória',
                'tax_service.required' => 'Taxa de serviço é obrigatória',
                'value.required' => 'Valor do lote é obrigatório',
                'name.required' => 'Nome do lote é obrigatório',
                'quantity.required' => 'Quantidade é obrigatória',
                'description.required' => 'Descrição do lote é obrigatória',
                'limit_min.required' => 'Limite mínimo de 1 é obrigatório',
                'limit_max.required' => 'Limite máximo maior ou igual ao limíte mínimo é obrigatório',
                'datetime_begin.required' => 'Data de inicio é obrigatória',
                'datetime_end.required' => 'Data de fim é obrigatória',
                'form_pagamento.required' => 'Forma de pagamento é obrigatória',
                'visibility.required' => 'Visibilidade do lote é obrigatória',
             ]);
        } else {

            $this->validate($request, [
                'type' => 'required|integer',
                'name' => 'required',
                'quantity' => 'required',
                'description' => 'required',
                'limit_min' => 'required|min:1',
                'limit_max' => 'required|gte:limit_min',
                'datetime_begin' => 'required',
                'datetime_end' => 'required',
                'visibility' => 'required',
                'event_id' => 'nullable',
            ],[
                'type.required' => 'Tipo de lote é obrigatório',
                'name.required' => 'Nome do lote é obrigatório',
                'quantity.required' => 'Quantidade é obrigatória',
                'description.required' => 'Descrição do lote é obrigatória',
                'limit_min.required' => 'Limite mínimo de 1 é obrigatório',
                'limit_max.required' => 'Limite máximo maior ou igual ao limíte mínimo é obrigatório',
                'datetime_begin.required' => 'Data de inicio é obrigatória',
                'datetime_end.required' => 'Data de fim é obrigatória',
                'form_pagamento.required' => 'Forma de pagamento é obrigatória',
                'visibility.required' => 'Visibilidade do lote é obrigatória',
            ]);
        }

        $input['datetime_begin'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_begin'])));
        $input['datetime_end'] = date('Y-m-d H:m', strtotime(str_replace('/', '-', $input['datetime_end'])));

        if($input['type'] == 0) {
            $input['tax'] = doubleval($input['value']) * $taxa_juros;
            // Se a taxa é paga pelo participante (tax_service == 0), soma ao valor
            // Se a taxa é paga pelo organizador (tax_service == 1), subtrai do valor
            if(isset($input['tax_service']) && $input['tax_service'] == 0) {
                $input['final_value'] = doubleval($input['value']) + doubleval($input['value']) * $taxa_juros;
            } else {
                $input['final_value'] = doubleval($input['value']) - doubleval($input['value']) * $taxa_juros;
            }
            $input['form_pagamento'] = implode(',', $input['form_pagamento']);
        } else {
            // Lote gratuito (type == 1): não cobra taxa
            $input['tax'] = 0;
            $input['final_value'] = 0;
        }

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        $lote->fill($input)->save();

        return redirect()->route('event_home.create.step.two');
    }

    public function deleteLote($hash)
    {
        $lote = Lote::where('hash', $hash)->first();

        $lote->delete();

        return redirect()->route('event_home.create.step.two')->with('success', 'Lote deletado com sucesso!');
    }

    public function save_lotes(Request $request, $hash)
    {
        $input = $request->all();

        if(isset($input['order_lote'])) {
            foreach($input['order_lote'] as $order) {

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

        if($event_id != null) {

            $event = Event::findOrFail($event_id);
            $hash_event = $event->hash;

            if(empty($request->session()->get('coupons'))) {
                $coupons = Coupon::where('event_id', $event_id)->orderBy('created_at')->get();
            } else {
                $lotes = $request->session()->get('coupons');
            }

            return view('painel_admin.list_coupons', compact('coupons', 'event_id', 'hash_event'));

        } else {

            return redirect()->route('home');
        }
    }

    public function createCoupon($hash)
    {
        $event = Event::where('hash', $hash)->first();

        $coupon_code = strtoupper(substr($event->name, 0, 2) . substr(sha1($event->id . date('Y/m/d h:i:s') . md5($event->name)), 0, 6));

        $lotes = Lote::orderBy('order')
            ->where('event_id', $event->id)
            ->get();

        return view('painel_admin.create_coupon', compact('event', 'lotes', 'hash', 'coupon_code'));
    }

    public function storeCoupon(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();

        $this->validate($request, [
            'code' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'limit_buy' => 'required',
            'limit_tickets' => 'required',
        ],[
            'code.required' => 'Código do cupom é obrigatório',
            'discount_type.required' => 'Tipo de desconto é obrigatório',
            'discount_value.required' => 'Valor do desconto é obrigatório',
            'limit_buy.required' => 'Limite de compra é obrigatório',
            'limit_tickets.required' => 'Limite de ingressos é obrigatório',
        ]);

        $input = $request->all();

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        $input['event_id'] = $event->id;
        $input['hash'] = md5($input['code'] . $input['event_id'] . md5(config('services.hash_secret')));

        $id_coupon = Coupon::create($input)->id;

        $coupon_obj = Coupon::find($id_coupon);

        $lotes = $input['lotes'];

        foreach($lotes as $lote) {

            $coupon_obj->lotes()->attach($lote);
        }

        if($input['discount_type'] == 0) {
            $input['discount_value'] = (double)$input['discount_value'] / 100;
        }

        $coupon_obj->fill($input)->save();

        return redirect()->route('event_home.create.step.three')->with('success', 'Cupom salvo com sucesso!');
    }

    public function editCoupon($hash)
    {

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
            'status' => 'nullable',
        ],[
            'code.required' => 'Código do cupom é obrigatório',
            'discount_type.required' => 'Tipo de desconto é obrigatório',
            'discount_value.required' => 'Valor do desconto é obrigatório',
            'limit_buy.required' => 'Limite de compra é obrigatório',
            'limit_tickets.required' => 'Limite de ingressos é obrigatório',
        ]);

        $input = $request->all();

        $input['event_id'] = $coupon->event_id;

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        $lotes = $input['lotes'];

        $coupon->lotes()->detach();

        foreach($lotes as $lote) {

            $coupon->lotes()->attach($lote);
        }

        if($input['discount_type'] == 0) {
            $input['discount_value'] = (double)$input['discount_value'] / 100;
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

        return redirect()->route('coupon.coupons')->with('success', 'Cupom removido com sucesso!');
    }

    public function createStepFour(Request $request)
    {
        $event_id = $request->session()->get('event_id');

        if($event_id != null) {

            $event = Event::findOrFail($event_id);
            $owner_id = $event->owner_id;
            $hash_event = $event->hash;

            return view('painel_admin.publish', compact('event', 'event_id', 'hash_event', 'owner_id'));

        } else {

            return redirect()->route('home');
        }
    }

    public function postCreateStepFour(Request $request, $hash)
    {
        $input = $request->all();

        $event = Event::where('hash', $hash)->first();
        $owner = Owner::where('id', $input['owner_id'])->first();

        if($event->banner) {
            $validatedEvent = $request->validate([
                'theme' => 'required',
                'banner_option' => 'required',
                'status' => 'nullable',
            ],[
                'theme.required' => 'O tema do evento é obrigatório',
                'banner_option.required' => 'O banner do evento é obrigatório',
                'status.required' => 'Status é obrigatório',
            ]);
        } else {
            if($input['banner_option'] == 2) {
                $validatedEvent = $request->validate([
                    'banner' => 'mimes:jpg,jpeg,bmp,png|max:2048',
                    'theme' => 'required',
                    'banner_option' => 'required',
                    'status' => 'nullable',
                ],[
                    'banner.required' => 'Os formatos aceitos para as imagens dos banner são: jpg, jpeg, bmp, png',
                    'theme.required' => 'O tema do evento é obrigatória',   
                    'banner_option.required' => 'O banner do evento é obrigatório',
                    'status.required' => 'Status é obrigatório',
                ]);
            } else {
                $validatedEvent = $request->validate([
                    'theme' => 'required',
                    'banner_option' => 'required',
                    'status' => 'nullable',
                ],[
                    'theme.required' => 'O tema do evento é obrigatório',
                    'banner_option.required' => 'O banner do evento é obrigatório',
                ]);

                $validatedEvent['banner'] = '';
            }
        }

        // dd($validatedEvent);

        if(isset($validatedEvent['status'])) {
            $validatedEvent['status'] = 1;
        } else {
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

        if($owner && $owner->icon) {
            $validatedOwner = $request->validate([
                'owner_name' => 'required',
                'description' => 'required',
                'status' => 'nullable',
                'icon' => 'nullable|mimes:jpg,jpeg,bmp,png|max:2048',
            ],[
                'owner_name.required' => 'O nome do proprietário é obrigatório',
                'description.required' => 'A descrição do proprietário é obrigatória',
                'icon.mimes' => 'Os formatos aceitos para as imagens são: jpg, jpeg, bmp, png',
            ]);
        } else {
            $validatedOwner = $request->validate([
                'icon' => 'required|mimes:jpg,jpeg,bmp,png|max:2048',
                'owner_name' => 'required',
                'description' => 'required',
                'status' => 'nullable',
            ],[
                'owner_name.required' => 'O nome do proprietário é obrigatório',
                'description.required' => 'A descrição do proprietário é obrigatória',
                'icon.required' => 'O ícone do organizador é obrigatório',
                'icon.mimes' => 'Os formatos aceitos para as imagens são: jpg, jpeg, bmp, png',
            ]);
        }

        if($request->file('icon')) {
            $fileName = time().'_'.$request->file('icon')->getClientOriginalName();
            $filePath = $request->file('icon')->storeAs('owners', $fileName, 'public');
            $validatedOwner['icon'] = $filePath;
        }

        $validatedOwner['name'] = $validatedOwner['owner_name'];
        $validatedOwner['status'] = 1;

        $owner_id = '';
        if($owner) {
            $owner->fill($validatedOwner);
            $owner->save();
            $owner_id = $owner->id;
        } else {
            $owner = new Owner();
            $owner->fill($validatedOwner);
            $owner->save();
            $owner_id = $owner->id;
        }

        Event::where('id', $event->id)->update(['owner_id' => $owner_id]);

        if(isset($validatedEvent['status'])) {
            try {
                Mail::to(Auth::user()->email)->send(new EventAdminControllerMail($event, 'Evento publicado com sucesso', 'publicado'));
            } catch (\Exception $mailException) {
                Log::warning('Falha ao enviar e-mail de publicação de evento: ' . $mailException->getMessage(), [
                    'event_id' => $event->id,
                    'user_email' => Auth::user()->email,
                ]);
            }
        } else {
            try {
                Mail::to(Auth::user()->email)->send(new EventAdminControllerMail($event, 'Evento salvo com sucesso', 'salvo'));
            } catch (\Exception $mailException) {
                Log::warning('Falha ao enviar e-mail de salvamento de evento: ' . $mailException->getMessage(), [
                    'event_id' => $event->id,
                    'user_email' => Auth::user()->email,
                ]);
            }
        }

        return redirect()->route('event_home.my_events')->with('success', 'Evento salvo com sucesso!');
    }

    public function destroy($hash)
    {
        $event = Event::where('hash', $hash)->first();

        $event->delete();

        return redirect()->route('event_home.my_events');
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

        if($event != null) {

            return view('painel_admin.guest_add', compact('event'));

        } else {

            return redirect()->route('home');
        }
    }

    public function storeGuest(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();

        if (!$event) {
            return redirect('/');
        }

        $this->validate($request, [
            'email' => 'required',
            'role' => 'required',
            'status' => 'nullable',
        ]);

        $input = $request->all();

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        $participante = Participante::where('email', $input['email'])->first();
        $msgType = '';
        $msgContent = '';
        if($participante) {
            if($participante->status == 0) {
                $msgType = 'error';
                $msgContent = 'A conta do usuário convidado está desativada. Entre em contato para regularização.';
                //ENVIAR EMAIL SOLICITANDO REATIVAÇÃO DA CONTA
            } elseif($participante->status == 1) {
                DB::table('participantes_events')->insert([
                    'hash' => md5($participante->name . date('Y-m-d H:i:s') . md5(config('services.hash_secret'))),
                    'role' => 'convidado',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'participante_id' => $participante->id,
                    'event_id' => $event->id,
                ]);
                $msgType = 'success';
                $msgContent = 'Usuário convidado adicionado com sucesso!';

                try {
                    Mail::to($participante->email)->send(new GuestControllerMail($event, 'Você foi convidado', $participante, Auth::user()));
                } catch (\Exception $mailException) {
                    Log::warning('Falha ao enviar e-mail de convite: ' . $mailException->getMessage(), [
                        'event_id' => $event->id,
                        'guest_email' => $participante->email,
                    ]);
                }
            }
        } else {
            //ENVIAR EMAIL SOLICITANDO A CRIAÇÃO DA CONTA
            $msgType = 'error';
            $msgContent = 'Não foi possível adicionar usuário. Usuário não possui um cadastro na Ticket DZ6.';
        }

        return redirect()->route('event_home.guests', $event->hash)->with($msgType, $msgContent);
    }

    public function editGuest($id)
    {
        $guest = Participante::join('participantes_events', 'participantes_events.participante_id', '=', 'participantes.id')
            ->join('events', 'participantes_events.event_id', '=', 'events.id')
            ->select('participantes_events.id', 'participantes.name', 'participantes.email', 'participantes_events.role', 'participantes_events.status', 'events.hash as event_hash')
            ->where('participantes_events.id', $id)
            ->first();

        // $guest = DB::table('participantes_events')->where('id', $id)->first();

        return view('painel_admin.guest_edit', compact('guest'));
    }

    public function updateGuest(Request $request, $id)
    {
        if (Auth::user()->userEvent->isEmpty()) {
            return redirect('/');
        }

        $this->validate($request, [
            'role' => 'required',
            'status' => 'nullable',
        ]);

        $input = $request->all();

        if(isset($input['status'])) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        ParticipanteEvent::where('id', $id)->update(['status' => $input['status']]);

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

        $path = public_path().'/storage/'.$event->banner;

        unlink($path);

        if($event) {
            $event->banner = '';
            $event->save();
        }

        return back()->with('success', 'Banner do evento removido com sucesso!');
    }

    public function deleteFileIcon(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);

        // $request->file->delete(public_path('events'), $event->banner);

        $path = public_path().'/storage/'.$owner->icon;

        unlink($path);

        if($owner) {
            $owner->icon = '';
            $owner->save();
        }

        return back()->with('success', 'Banner do organizador removido com sucesso!');
    }

    public function contacts(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();

        $event_id = $event->id;

        $messages = Message::where('event_id', $event_id)->get();

        return view('painel_admin.contacts', compact('messages', 'event'));
    }

    public function showMessage(Request $request, $id)
    {
        $contact = Message::find($id);

        Message::where('id', $id)->update(['read' => 1]);

        return view('painel_admin.contact', compact('contact'));
    }

    public function marcarComoLida(Request $request)
    {
        if ($request->input('action') === 'marcarLida') {
            
            $ids = $request->input('ids');

            if (!is_array($ids) || empty($ids)) {
                return response()->json(['error' => 'Nenhum ID de mensagem foi fornecido'], 400);
            }

            Message::whereIn('id', $ids)->update(['read' => 1]);

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Ação inválida'], 400);
    }

    public function marcarComoNaoLida(Request $request)
    {
        if ($request->input('action') === 'marcarNaoLida') {
            
            $ids = $request->input('ids');

            if (!is_array($ids) || empty($ids)) {
                return response()->json(['error' => 'Nenhum ID de mensagem foi fornecido'], 400);
            }

            Message::whereIn('id', $ids)->update(['read' => 0]);

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Ação inválida'], 400);
    }

    public function deletarMensagens(Request $request)
    {
        if ($request->input('action') === 'deletarMensagens') {
            
            $ids = $request->input('ids');

            if (!is_array($ids) || empty($ids)) {
                return response()->json(['error' => 'Nenhum ID de mensagem foi fornecido'], 400);
            }

            Message::whereIn('id', $ids)->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Ação inválida'], 400);
    }

    public function reports(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->first();

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
            ->where('events.hash', $hash)
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
            ->where('events.hash', $hash)
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

        // $participantes = Participante::orderBy('participantes.name')
        //             ->join('orders', 'orders.participante_id', '=', 'participantes.id')
        //             ->join('order_items', 'orders.id', '=', 'order_items.order_id')
        //             ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
        //             ->join('events', 'events.id', '=', 'lotes.event_id')
        //             ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
        //             ->where('events.hash', $hash)
        //             ->select('lotes.name as lote_name', 'orders.id', 'order_items.number as inscricao', 'orders.status as situacao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
        //             ->get();

        $all_orders = Order::orderBy('orders.created_at', 'asc')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->where('event_dates.event_id', $event->id)
            // ->select('lotes.name as lote_name', 'orders.id', 'orders.status as situacao', 'order_items.id as order_item_id', 'order_items.number as inscricao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
            ->select('orders.id as order_id', 'orders.status as situacao', 'orders.hash as order_hash', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.name as participante_name', 'participantes.email as participante_email', 'participantes.cpf as participante_cpf')
            ->get();

        $situacao_participantes = Participante::orderBy('participantes.name')
            ->join('orders', 'orders.participante_id', '=', 'participantes.id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->where('events.hash', $hash)
            ->select('orders.gatway_status', 'order_items.value as lote_value', 'lotes.name as lote_name', 'orders.id', 'order_items.number as inscricao', 'orders.status as situacao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
            ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value) else '' end as valor_porcentagem")
            ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value else '' end as valor_desconto")
            ->get();

        $situacao_participantes_lotes = OrderItem::orderBy('order_items.created_at', 'desc')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            // ->join('option_answers', 'option_answers.order_item_id', '=', 'order_items.id')
            ->where('events.hash', $hash)
            ->select('order_items.id as id', 'order_items.number', 'order_items.status as status_item', 'lotes.name as lote_name')
            ->get();

        // $situacao_participantes = Participante::orderBy('participantes.name')
        //             ->join('orders', 'orders.participante_id', '=', 'participantes.id')
        //             ->join('order_items', 'orders.id', '=', 'order_items.order_id')
        //             ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
        //             ->join('events', 'events.id', '=', 'lotes.event_id')
        //             ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id')
        //             ->where('events.hash', $hash)
        //             ->select('orders.gatway_status', 'order_items.value as lote_value','lotes.name as lote_name', 'orders.id', 'order_items.number as inscricao', 'orders.status as situacao', 'participantes.name as participante_name', 'participantes.email as participante_email', 'coupons.code')
        //             ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 0 and coupons.code <> '' then order_items.value - (coupons.discount_value * order_items.value) else '' end as valor_porcentagem")
        //             ->selectRaw("case when lotes.type = 0 and coupons.discount_type = 1 and coupons.code <> '' then order_items.value - coupons.discount_value else '' end as valor_desconto")
        //             ->get();

        // dd($situacao_participantes);

        $payment_methods = Order::orderBy('orders.gatway_payment_method')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->where('events.hash', $hash)
            ->where('lotes.type', 0)
            ->select('orders.gatway_payment_method', DB::raw('count(*) as payment_methods_total'))
            ->groupBy('orders.gatway_payment_method')
            ->get();

        $situacao_coupons = Coupon::orderBy('coupons.id')
            ->join('orders', 'orders.coupon_id', '=', 'coupons.id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->where('coupons.status', '1')
            ->where('events.hash', $hash)
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

        // Estatísticas de check-in
        $checkin_stats = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->where('event_dates.event_id', $event->id)
            ->where('orders.status', 1) // Apenas pedidos confirmados
            ->selectRaw('COUNT(*) as total_confirmados')
            ->selectRaw('COUNT(CASE WHEN order_items.checkin_status = 1 THEN 1 END) as total_checkin')
            ->selectRaw('COUNT(CASE WHEN order_items.checkin_status = 0 THEN 1 END) as total_sem_checkin')
            ->first();

        // Vendas por período (últimos 30 dias ou desde o início)
        $vendas_por_periodo = Order::join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->where('event_dates.event_id', $event->id)
            ->whereIn('orders.status', [1, 2])
            ->selectRaw('DATE(orders.created_at) as data')
            ->selectRaw('COUNT(*) as total_vendas')
            ->groupBy('data')
            ->orderBy('data', 'asc')
            ->get();

        // Informações do evento
        $event_dates = $event->event_dates()->orderBy('date')->get();
        $place = $event->place;
        
        // Calcular capacidade total e vendida
        $capacidade_total = Lote::where('event_id', $event->id)
            ->where('type', 0) // Apenas lotes pagos
            ->sum('quantity');
        
        $ingressos_vendidos = $resumo->geral ?? 0;
        $taxa_ocupacao = $capacidade_total > 0 ? ($ingressos_vendidos / $capacidade_total) * 100 : 0;

        // Ticket médio
        $ticket_medio = ($resumo->confirmado ?? 0) > 0 
            ? ($resumo->total_confirmado ?? 0) / ($resumo->confirmado ?? 1) 
            : 0;

        // Vendas por lote (para gráfico)
        $vendas_por_lote = Lote::where('lotes.event_id', $event->id)
            ->leftJoin('order_items', 'lotes.id', '=', 'order_items.lote_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where(function($query) {
                $query->whereNull('orders.id')
                      ->orWhereIn('orders.status', [1, 2]);
            })
            ->select('lotes.id', 'lotes.name')
            ->selectRaw('COUNT(CASE WHEN orders.status IN (1,2) THEN 1 END) as vendidos')
            ->groupBy('lotes.id', 'lotes.name')
            ->get();

        $participantes_json = response()->json($all_orders);
        $payment_methods_json = response()->json($payment_methods);

        return view('painel_admin.reports', compact(
            'event', 
            'lotes', 
            'resumo', 
            'all_orders', 
            'participantes_json', 
            'config', 
            'situacao_participantes', 
            'situacao_participantes_lotes', 
            'payment_methods', 
            'payment_methods_json', 
            'situacao_coupons',
            'checkin_stats',
            'vendas_por_periodo',
            'event_dates',
            'place',
            'capacidade_total',
            'ingressos_vendidos',
            'taxa_ocupacao',
            'ticket_medio',
            'vendas_por_lote'
        ));
    }

    public function order_details(Request $request, $hash)
    {
        $user = Auth::guard('participante')->user();
        $isSuperAdmin = $user->hasRole('super_admin');

        $order = Order::orderBy('orders.created_at', 'asc')
            ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
            ->join('events', 'events.id', '=', 'lotes.event_id')
            ->where('orders.hash', $hash)
            ->select('events.id as event_id', 'orders.id as order_id', 'orders.hash as order_hash', 'orders.status as situacao', 'orders.gatway_hash as gatway_hash', 'orders.gatway_reference as gatway_reference', 'orders.gatway_payment_method as gatway_payment_method', 'orders.created_at as created_at', 'participantes.id as participante_id', 'participantes.name as participante_name', 'participantes.email as participante_email', 'lotes.id as lote_id', 'lotes.name as lote_name')
            ->groupBy('order_items.id')
            ->first();

        if (!$order) {
            abort(404, 'Pedido não encontrado');
        }

        // Se não for super admin, verificar se o usuário tem acesso ao evento
        if (!$isSuperAdmin && !$this->hasAccessToEvent($order->event_id)) {
            abort(403, 'Você não tem permissão para acessar este pedido');
        }

        return view('painel_admin.order_detail', compact('order', 'isSuperAdmin'));
    }

    public function print_voucher(Request $request, $hash)
    {

        $items = OrderItem::orderBy('order_items.created_at', 'desc')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
            ->join('participantes', 'participantes.id', '=', 'orders.participante_id')
            ->join('events', 'event_dates.event_id', '=', 'events.id')
            ->join('places', 'places.id', '=', 'events.place_id')
            ->selectRaw(
                'events.name as event_name,
                events.status as event_status,
                events.created_at as event_date,
                event_dates.date as data_chosen,
                places.name as place_name,
                orders.hash as order_hash,
                orders.gatway_status,
                orders.gatway_hash,
                orders.gatway_reference,
                order_items.number,
                order_items.created_at,
                order_items.hash as order_items_hash,
                md5(concat(orders.hash, order_items.hash, order_items.number, order_items.created_at, md5(?))) as purchase_hash',
                [config('services.hash_secret')]
            )
            ->where('orders.hash', $hash)
            ->where('orders.gatway_status', '1')
            ->get();

        foreach($items as $item){

            OrderItem::where('status', '1')
                ->where('hash', $item->order_items_hash)
                ->update(['purchase_hash' => $item->purchase_hash]);
        }
        
        return view('painel_admin.print_voucher', compact('items'));

    }

    public function profile()
    {
        $user = Auth::guard('participante')->user();
        return view('painel_admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('participante')->user();

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:participantes,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8',
        ]);

        $input = $request->all();

        if(empty($input['password'])) {
            unset($input['password']);
        } else {
            $input['password'] = Hash::make($input['password']);
        }

        $user->fill($input)->save();

        return redirect()->route('event_home.profile')->with('success', 'Perfil atualizado com sucesso!');
    }

}
