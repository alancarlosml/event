<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\MpAccount;
use App\Models\Participante;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class MercadoPagoController extends Controller
{
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;
    protected $redirectUri;
    protected $state;

    public function __construct()
    {
        $this->client = new Client();
        $this->clientId = env('MERCADO_PAGO_CLIENT_ID');
        $this->clientSecret = env('MERCADO_PAGO_CLIENT_SECRET');
        $this->accessToken = env('MERCADO_PAGO_ACCESS_TOKEN');
        // Usa a rota nomeada para garantir que a URL está correta
        $this->redirectUri = route('mercado-pago.link-account');
        $this->state = rand(100000, 999999);
    }

    private function getAccessToken($authorizationCode)
    {
        // O redirect_uri deve ser exatamente o mesmo usado na URL de autorização
        $redirectUri = route('mercado-pago.link-account');
        
        $response = $this->client->post('https://api.mercadopago.com/oauth/token', [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $authorizationCode,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirectUri,
            ],
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    // Vinclua conta do Mercado Pago e salva
    // os dados necessários na tabela mp_accounts.
    // Retorna uma view com feedback, informando se
    // a vinculação foi bem sucedida.
    public function linkAccount(Request $r)
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            $data = [
                'title' => 'Erro ao vincular conta do Mercado Pago',
                'subtitle' => 'Você precisa estar autenticado para vincular uma conta.',
            ];
            return view('feedback', $data);
        }

        $authorizationCode = $r->input('code');
        
        // Verifica se o código de autorização foi recebido
        if (!$authorizationCode) {
            Log::error('Código de autorização não recebido', [
                'request_params' => $r->all()
            ]);
            
            $data = [
                'title' => 'Erro ao vincular conta do Mercado Pago',
                'subtitle' => 'Código de autorização não recebido. Por favor, tente novamente.',
            ];
            return view('feedback', $data);
        }

        $id = Auth::user()->id;
        $participante = Participante::find($id);

        if (!$participante) {
            $data = [
                'title' => 'Erro ao vincular conta do Mercado Pago',
                'subtitle' => 'Usuário não encontrado.',
            ];
            return view('feedback', $data);
        }

        try {
            $accessTokenData = $this->getAccessToken($authorizationCode);

            // Verifica se a resposta contém os dados necessários
            if (!isset($accessTokenData['access_token']) || !isset($accessTokenData['user_id'])) {
                Log::error('Resposta do Mercado Pago incompleta', [
                    'response' => $accessTokenData
                ]);
                
                $data = [
                    'title' => 'Erro ao vincular conta do Mercado Pago',
                    'subtitle' => 'Resposta inválida do Mercado Pago. Verifique as credenciais da aplicação.',
                ];
                return view('feedback', $data);
            }

            $mpAccount = MpAccount::updateOrCreate(
                ['participante_id' => $participante->id],
                [
                    'access_token' => $accessTokenData['access_token'],
                    'public_key' => $accessTokenData['public_key'] ?? null,
                    'refresh_token' => $accessTokenData['refresh_token'] ?? null,
                    'expires_in' => isset($accessTokenData['expires_in']) 
                        ? Carbon::now()->addSeconds($accessTokenData['expires_in'])
                        : Carbon::now()->addDays(178),
                    'mp_user_id' => $accessTokenData['user_id'],
                ]
            );

            Log::info('Conta do Mercado Pago vinculada com sucesso', [
                'user_id' => $id,
                'mp_user_id' => $accessTokenData['user_id']
            ]);

            $data = [   
                'title' => 'Conta do Mercado Pago vinculada com sucesso!',
                'subtitle' => 'Você pode fechar esta página.'
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);
            
            Log::error('Erro ao vincular conta Mercado Pago (ClientException)', [
                'user_id' => $id,
                'status_code' => $response->getStatusCode(),
                'response' => $responseBody,
                'error' => $e->getMessage()
            ]);
            
            $errorMessage = $responseBody['message'] ?? 'Erro ao processar a vinculação.';
            $data = [
                'title' => 'Erro ao vincular conta do Mercado Pago',
                'subtitle' => $errorMessage . ' Verifique se o redirect_uri está configurado corretamente no painel do Mercado Pago.',
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao vincular conta Mercado Pago', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $data = [
                'title' => 'Erro ao vincular conta do Mercado Pago',
                'subtitle' => 'Ocorreu um erro ao processar a vinculação: ' . $e->getMessage(),
            ];
        }
        
        return view('feedback', $data);

    }

    // Verifica se o usuário já tem uma conta
    // do Mercado Pago vinculada, e retorna o id
    // do usuário no Mercado Pago, caso tenha.
    public function checkLinkedAccount(Request $r)
    {

        $id = Auth::user()->id;
        $participante = Participante::find($id);
        $mpAccount = MpAccount::where('participante_id', $participante->id)->first();

        if ($mpAccount) {
            return response()->json(['linked' => true, 'id' => $mpAccount->mp_user_id]);
        } else {
            return response()->json(['linked' => false, 'id' => NULL]);
        }

    }

    // Recebe os dados do pedido e redireciona
    // para o checkout do Mercado Pago.
    public function payment (Request $r)
    {

        // Recebe os dados do evento
        $event = $r->session()->get('event');
        $array_lotes = $r->session()->get('array_lotes');
        $quantity = intval($array_lotes[0]['quantity']);
        $total = intval($array_lotes[0]['value']);
        $slug = $event->slug;
        // Imagem apenas para teste
        $img = "https://iaia.edu/wp-content/plugins/events-calendar-pro/src/resources/images/tribe-related-events-placeholder.png";
        
        // Busca o id do criador do evento
        $event_name = $event->name;
        $event_id = $event->id;
        if ( $participante = DB::table('participantes_events')->where('event_id', $event_id)->where('role', 'admin')->first(['participante_id']) ) {
            $creator_id = $participante->participante_id;
        } else {
            return null;
        }

        // Busca a conta do mercado pago vinculada ao criador do evento
        if ( $mpAccount = MpAccount::where('participante_id', $creator_id)->first() ) {
            $accessToken = $mpAccount->access_token;
        } else {
            return null;
        }

        // Calcular a taxa da plataforma (valor absoluto)
        $config = Configuration::findOrFail(1);

        $taxa_percentual = $config->tax;
        if($event->config_tax != 0.0) {
            $taxa_percentual = $event->config_tax;
        }
        
        // Calcular valor absoluto da taxa (marketplace_fee precisa ser valor absoluto)
        $fee = round($total * $taxa_percentual, 2);

        // Monta o payload para criar a preferência no Mercado Pago
        $data = [
            "notification_url" => env('APP_URL') . "/webhooks/mercado-pago/notification",
            "external_reference" => rand(100000, 999999),
            "items" => [
                [
                    "title" => $event_name,
                    "quantity" => $quantity,
                    "currency_id" => "BRL",
                    "unit_price" => $total,
                    "picture_url" => $img
                ]
            ],
            "marketplace" => env('MERCADO_PAGO_ACCESS_TOKEN'),
            "marketplace_fee" => $fee,
            "back_urls" => [
                "success" => env('APP_URL') . "/" . $slug . "/obrigado",
                "failure" => env('APP_URL') . "/" . $slug . "/erro",
                "pending" => env('APP_URL') . "/" . $slug . "/pendente"
            ],
        ];

        // Cria a preferência de pagamento no Mercado Pago
        $client = new Client();
        $response = $client->post('https://api.mercadopago.com/checkout/preferences', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => $data
        ]);

        $body = json_decode($response->getBody(), true);

        // Salva o pedido no banco de dados
        // (necessário ajustes)
        $order_id = DB::table('orders')->insertGetId([
            'hash' => md5(time() . uniqid() . md5('7bc05eb02415fe73101eeea0180e258d45e8ba2b')),
            'status' => 2,
            'gatway_hash' => null,
            'gatway_reference' => null,
            'gatway_status' => null,
            'gatway_payment_method' => null,
            'event_id' => $event_id,
            // 'event_date_id' => $event_date->id, // Precisei comentar para consegui testar
            // 'event_date_id' => '63', // Ajustar, coloquei fixo pra testes
            'participante_id' => Auth::user()->id,
            'coupon_id' => NULL,
            'created_at' => now(),
        ]);

        $r->session()->put('order_id', $order_id);

        // Redireciona para o checkout do Mercado Pago
        // através do link gerado pela preferência
        return redirect()->away($body['init_point']);

    }

    // Recebe os webhooks do Mercado Pago
    public function notification (Request $r)
    {
        try {
            $data = $r->all();
            
            // Log da notificação recebida
            Log::info('Mercado Pago Webhook received', $data);
            
            // Verificar se é uma notificação válida do Mercado Pago
            if (!isset($data['type']) || !isset($data['data']['id'])) {
                Log::error('Invalid Mercado Pago notification', $data);
                return response()->json(['error' => 'Invalid notification'], 400);
            }
            
            // Processar apenas notificações de pagamento
            if ($data['type'] === 'payment') {
                $paymentId = $data['data']['id'];
                
                Log::info('Processing payment notification', ['payment_id' => $paymentId]);
                
                // Buscar informações do pagamento no Mercado Pago
                $client = new Client();
                $response = $client->get("https://api.mercadopago.com/v1/payments/{$paymentId}", [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->accessToken,
                        'Content-Type' => 'application/json'
                    ]
                ]);
                
                $paymentData = json_decode($response->getBody(), true);
                
                Log::info('Payment data retrieved', ['payment_data' => $paymentData]);
                
                // Buscar o pedido correspondente
                $order = DB::table('orders')
                    ->where('gatway_hash', $paymentId)
                    ->first();
                
                if ($order) {
                    // Atualizar status do pedido baseado no status do pagamento
                    $status = $this->mapPaymentStatus($paymentData['status']);
                    
                    DB::table('orders')
                        ->where('id', $order->id)
                        ->update([
                            'status' => $status,
                            'gatway_status' => $paymentData['status'],
                            'gatway_payment_method' => $paymentData['payment_method_id'],
                            'gatway_date_status' => $paymentData['date_created'],
                            'updated_at' => now()
                        ]);
                    
                    Log::info('Order status updated', [
                        'order_id' => $order->id,
                        'status' => $status,
                        'payment_status' => $paymentData['status']
                    ]);
                    
                    // Se o pagamento foi aprovado, gerar ingressos
                    if ($paymentData['status'] === 'approved') {
                        $this->generateTickets($order->id);
                        // Enviar email de confirmação
                        $orderModel = \App\Models\Order::find($order->id);
                        if ($orderModel) {
                            Mail::to($orderModel->get_participante()->email)->send(new \App\Mail\PaymentApprovedMail($orderModel));
                        }
                        
                        Log::info('Payment approved - tickets generated and email sent', ['order_id' => $order->id]);
                    }
                } else {
                    Log::warning('Order not found for payment', ['payment_id' => $paymentId]);
                }
            }
            
            return response()->json(['success' => true], 200);
            
        } catch (\Exception $e) {
            Log::error('Mercado Pago Webhook Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $r->all()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    
    // Mapear status do Mercado Pago para status interno
    private function mapPaymentStatus($mpStatus)
    {
        $statusMap = [
            'pending' => 2,      // Pendente
            'approved' => 1,     // Aprovado
            'authorized' => 2,   // Autorizado (pendente)
            'in_process' => 2,   // Em processo
            'in_mediation' => 2, // Em mediação
            'rejected' => 3,     // Rejeitado
            'cancelled' => 4,    // Cancelado
            'refunded' => 5,     // Reembolsado
            'charged_back' => 6  // Contestado
        ];
        
        return $statusMap[$mpStatus] ?? 2; // Default: pendente
    }
    
    // Gerar ingressos após pagamento aprovado
    private function generateTickets($orderId)
    {
        try {
            $order = DB::table('orders')->where('id', $orderId)->first();
            
            if (!$order) {
                throw new \Exception('Order not found');
            }
            
            // Buscar detalhes dos lotes do pedido
            $orderItems = DB::table('order_items')
                ->where('order_id', $orderId)
                ->get();
            
            foreach ($orderItems as $item) {
                // Gerar purchase_hash para cada order_item (usado no QR Code)
                // Usar created_at formatado como string para garantir consistência
                $createdAtStr = is_string($item->created_at) 
                    ? $item->created_at 
                    : (is_object($item->created_at) && method_exists($item->created_at, 'format') 
                        ? $item->created_at->format('Y-m-d H:i:s') 
                        : (string)$item->created_at);
                $purchaseHash = md5($order->hash . $item->hash . $item->number . $createdAtStr . md5("7bc05eb02415fe73101eeea0180e258d45e8ba2b"));
                
                // Atualizar order_item com purchase_hash
                DB::table('order_items')
                    ->where('id', $item->id)
                    ->update([
                        'purchase_hash' => $purchaseHash,
                        'status' => 1,
                        'updated_at' => now()
                    ]);
                
                // Gerar ingressos para cada item (se necessário)
                for ($i = 0; $i < $item->quantity; $i++) {
                    DB::table('tickets')->insert([
                        'hash' => md5(time() . uniqid() . $orderId . $i),
                        'order_id' => $orderId,
                        'lote_id' => $item->lote_id,
                        'participante_id' => $order->participante_id,
                        'status' => 1, // Ativo
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Error generating tickets: ' . $e->getMessage());
        }
    }

    public function thanks (Request $r)
    {

        // Adicionar lógica da página de obrigado
        $data = [
            'title' => 'Pagamento Aprovado!',
            'subtitle' => 'Confira seu e-mail para mais informações.',
        ];
        return view('feedback', $data);

    }

    public function error (Request $r)
    {

        // Adicionar lógica da página de pagamentos com erro
        $data = [
            'title' => 'Erro ao processar o pagamento...',
            'subtitle' => 'Confira seu e-mail para mais informações.',
        ];
        return view('feedback', $data);

}

    public function pending (Request $r)
    {

        // Adicionar lógica da página de pagamentos pendentes
        $data = [
            'title' => 'Seu pagamento está incompleto...',
            'subtitle' => 'Confira seu e-mail para mais informações.',
        ];
        return view('feedback', $data);
    }

    /**
     * Generate a new boleto for a participant and send it via email
     *
     * @param int $id Participant ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function novaCobranca($id, Request $request)
    {
        try {
            // Find the participant
            $participant = \App\Models\Participante::findOrFail($id);
            
            // Find the pending order for this participant
            $order = \App\Models\Order::where('participante_id', $participant->id)
                ->where('status', 2) // Pending status
                ->firstOrFail();
            
            // Find the event
            $event = \App\Models\Event::findOrFail($order->event_id);
            
            // Find the event creator's Mercado Pago account
            $mpAccount = \App\Models\MpAccount::where('participante_id', $event->user_id)->firstOrFail();
            
            // Create a new payment preference
            $client = new Client();
            
            // Get the order items
            $orderItems = \App\Models\OrderItem::where('order_id', $order->id)->get();
            $items = [];
            $total = 0;
            
            foreach ($orderItems as $item) {
                $items[] = [
                    'title' => $event->name,
                    'quantity' => $item->quantity,
                    'currency_id' => 'BRL',
                    'unit_price' => $item->price
                ];
                $total += $item->price * $item->quantity;
            }
            
            // Get platform fee from configuration (valor absoluto)
            $config = \App\Models\Configuration::first();
            $taxa_percentual = $event->config_tax ?? $config->tax;
            $fee = round($total * $taxa_percentual, 2);
            
            // Create payment preference data
            $data = [
                'notification_url' => env('APP_URL') . "/webhooks/mercado-pago/notification",
                'external_reference' => $order->id,
                'items' => $items,
                'marketplace' => env('MERCADO_PAGO_ACCESS_TOKEN'),
                'marketplace_fee' => $fee,
                'back_urls' => [
                    'success' => env('APP_URL') . "/{$event->slug}/obrigado",
                    'failure' => env('APP_URL') . "/{$event->slug}/erro",
                    'pending' => env('APP_URL') . "/{$event->slug}/pendente"
                ],
                'payment_methods' => [
                    'excluded_payment_methods' => [
                        ['id' => 'credit_card'],
                        ['id' => 'ticket'],
                        ['id' => 'bank_transfer']
                    ],
                    'installments' => 1
                ]
            ];
            
            // Create the payment preference
            $response = $client->post('https://api.mercadopago.com/checkout/preferences', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $mpAccount->access_token,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            
            $body = json_decode($response->getBody(), true);
            
            if (!isset($body['init_point'])) {
                throw new \Exception('Failed to generate boleto link');
            }
            
            // Update order with new payment link
            $order->update([
                'gatway_hash' => $body['id'],
                'gatway_status' => 'pending',
                'updated_at' => now()
            ]);
            
            // Send email with boleto link
            Mail::to($participant->email)->send(new \App\Mail\BoletoPendingMail($order, $body['init_point']));
            
            return response()->json([
                'success' => true,
                'message' => 'Nova cobrança gerada e enviada por e-mail com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error generating new boleto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar nova cobrança: ' . $e->getMessage()
            ], 500);
        }
    }
}