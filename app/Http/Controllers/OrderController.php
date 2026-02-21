<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\MpAccount;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentRefundClient;
use MercadoPago\MercadoPagoConfig;

class OrderController extends Controller
{
    public function cancel(Request $request, $order_id)
    {
        try {
            $order = Order::findOrFail($order_id);

            if (!in_array($order->status, [2])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas pedidos pendentes podem ser cancelados.'
                ], 400);
            }

            $mpAccount = $this->getOrganizerMpAccount($order->event_id);

            if ($mpAccount && $order->gatway_hash) {
                MercadoPagoConfig::setAccessToken($mpAccount->access_token);
                
                $client = new PaymentRefundClient();
                
                try {
                    $client->refund((int) $order->gatway_hash);
                    Log::info('Payment cancelled in Mercado Pago', [
                        'order_id' => $order->id,
                        'gatway_hash' => $order->gatway_hash
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Could not cancel payment in Mercado Pago', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $order->update([
                'status' => 4,
                'gatway_status' => 'cancelled',
                'updated_at' => now()
            ]);

            OrderItem::where('order_id', $order->id)->update([
                'status' => 3,
                'updated_at' => now()
            ]);

            Log::info('Order cancelled', ['order_id' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => 'Pedido cancelado com sucesso.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error cancelling order', [
                'order_id' => $order_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao cancelar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    public function refund(Request $request, $order_id)
    {
        try {
            $order = Order::findOrFail($order_id);

            if (!in_array($order->status, [1])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas pedidos aprovados podem ser estornados.'
                ], 400);
            }

            if ($order->created_at->diffInDays(now()) > 180) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prazo para estorno expirado (máximo 180 dias após aprovação).'
                ], 400);
            }

            $mpAccount = $this->getOrganizerMpAccount($order->event_id);

            if (!$mpAccount || !$order->gatway_hash) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível estornar este pedido. Pagamento não encontrado no gateway.'
                ], 400);
            }

            MercadoPagoConfig::setAccessToken($mpAccount->access_token);

            $client = new PaymentRefundClient();
            
            $requestOptions = null;
            if ($order->gatway_payment_method === 'pix') {
                $requestOptions = new RequestOptions();
                $requestOptions->setCustomHeaders(['X-Render-In-Process-Refunds' => 'true']);
            }

            $refundAmount = $request->input('amount');
            
            if ($refundAmount && $refundAmount < $this->getOrderTotal($order)) {
                $refund = $client->refund((int) $order->gatway_hash, (float) $refundAmount, $requestOptions);
                Log::info('Partial refund processed', [
                    'order_id' => $order->id,
                    'refund_id' => $refund->id,
                    'amount' => $refundAmount
                ]);
            } else {
                $refund = $client->refund((int) $order->gatway_hash, null, $requestOptions);
                Log::info('Full refund processed', [
                    'order_id' => $order->id,
                    'refund_id' => $refund->id
                ]);
            }

            $order->update([
                'status' => 5,
                'gatway_status' => 'refunded',
                'updated_at' => now()
            ]);

            OrderItem::where('order_id', $order->id)->update([
                'status' => 4,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estorno processado com sucesso.',
                'refund_id' => $refund->id ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Error refunding order', [
                'order_id' => $order_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar estorno: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelOrganizer(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $isOrganizer = DB::table('participantes_events')
            ->where('event_id', $order->event_id)
            ->where('participante_id', auth()->id())
            ->where('role', 'admin')
            ->exists();

        if (!$isOrganizer) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para cancelar este pedido.'
            ], 403);
        }

        return $this->cancel($request, $order_id);
    }

    public function refundOrganizer(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $isOrganizer = DB::table('participantes_events')
            ->where('event_id', $order->event_id)
            ->where('participante_id', auth()->id())
            ->where('role', 'admin')
            ->exists();

        if (!$isOrganizer) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para estornar este pedido.'
            ], 403);
        }

        return $this->refund($request, $order_id);
    }

    private function getOrganizerMpAccount($eventId)
    {
        $organizerParticipant = DB::table('participantes_events')
            ->where('event_id', $eventId)
            ->where('role', 'admin')
            ->first(['participante_id']);

        if (!$organizerParticipant) {
            return null;
        }

        return MpAccount::where('participante_id', $organizerParticipant->participante_id)->first();
    }

    private function getOrderTotal($order)
    {
        return OrderItem::where('order_id', $order->id)->sum('value');
    }

    public function show($order_id)
    {
        $order = Order::with(['event', 'participante', 'order_items.lote'])
            ->findOrFail($order_id);

        return view('painel_admin.order_detail', compact('order'));
    }
}
