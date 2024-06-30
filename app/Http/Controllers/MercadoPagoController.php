<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\MpAccount;
use App\Models\Participante;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $this->redirectUri = env('MERCADO_PAGO_REDIRECT_URI');
        $this->state = rand(100000, 999999);
    }

    private function getAccessToken($authorizationCode)
    {
        $response = $this->client->post('https://api.mercadopago.com/oauth/token', [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $authorizationCode,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->redirectUri,
                'state' => $this->state,
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

        $authorizationCode = $r->input('code');
        $id = Auth::user()->id;
        $participante = Participante::find($id);

        try {
            $accessTokenData = $this->getAccessToken($authorizationCode);

            $mpAccount = MpAccount::updateOrCreate(
                ['participante_id' => $participante->id],
                [
                    'access_token' => $accessTokenData['access_token'],
                    'public_key' => $accessTokenData['public_key'],
                    'refresh_token' => $accessTokenData['refresh_token'],
                    'expires_in' => Carbon::now()->addDays(178),
                    'mp_user_id' => $accessTokenData['user_id'],
                ]
            );

            $data = [   
                        'title' => 'Conta do Mercado Pago vinculada com sucesso!',
                        'subtitle' => 'Você pode fechar esta página.'
                    ];
        } catch (\Exception $e) {
            $data = [
                'title' => 'Erro ao vincular conta do Mercado Pago',
                'subtitle' => 'Feche esta página e tente novamente.',
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

        // Addicionar lógica para calcular a taxa da plataforma aqui
        // $fee = round(0.1 * $total, 2);

        $config = Configuration::findOrFail(1);

        $fee = $config->tax;
        if($event->config_tax != 0.0) {
            $fee = $event->config_tax;
        }

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
            "marketplace" => $this->accessToken,
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
            'hash' => md5(time() . uniqid() . md5('papainoel')),
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
        // Adicionar lógica para processar os webhooks
        // dd($r->all());
        return response()->json(['success' => 'true'], 200);
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

}