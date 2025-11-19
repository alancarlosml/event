<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\OrderItem;
use App\Models\Order;

class CheckInController extends Controller
{
    /**
     * Validar check-in via QR Code
     */
    public function validateCheckIn(Request $request, $purchaseHash)
    {
        try {
            // Buscar order_item pelo purchase_hash
            $orderItem = OrderItem::where('purchase_hash', $purchaseHash)
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
                ->join('events', 'event_dates.event_id', '=', 'events.id')
                ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
                ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
                ->select(
                    'order_items.id as order_item_id',
                    'order_items.number as ticket_number',
                    'order_items.purchase_hash',
                    'order_items.checkin_status',
                    'order_items.checkin_at',
                    'orders.hash as order_hash',
                    'orders.gatway_status',
                    'events.name as event_name',
                    'events.id as event_id',
                    'event_dates.date as event_date',
                    'participantes.name as participant_name',
                    'participantes.email as participant_email',
                    'lotes.name as lote_name'
                )
                ->first();

            if (!$orderItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code inválido ou ingresso não encontrado.'
                ], 404);
            }

            // Verificar se o pagamento foi aprovado
            if ($orderItem->gatway_status != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingresso não confirmado. Pagamento pendente ou cancelado.',
                    'data' => [
                        'ticket_number' => $orderItem->ticket_number,
                        'participant_name' => $orderItem->participant_name,
                        'event_name' => $orderItem->event_name,
                        'event_date' => $orderItem->event_date,
                        'status' => 'pending'
                    ]
                ], 400);
            }

            // Verificar se já foi feito check-in
            if ($orderItem->checkin_status == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este ingresso já foi utilizado para check-in.',
                    'data' => [
                        'ticket_number' => $orderItem->ticket_number,
                        'participant_name' => $orderItem->participant_name,
                        'event_name' => $orderItem->event_name,
                        'event_date' => $orderItem->event_date,
                        'checkin_at' => $orderItem->checkin_at,
                        'status' => 'already_checked_in'
                    ]
                ], 400);
            }

            // Fazer check-in
            OrderItem::where('id', $orderItem->order_item_id)
                ->update([
                    'checkin_status' => 1,
                    'checkin_at' => now(),
                    'updated_at' => now()
                ]);

            Log::info('Check-in realizado com sucesso', [
                'order_item_id' => $orderItem->order_item_id,
                'purchase_hash' => $purchaseHash,
                'ticket_number' => $orderItem->ticket_number,
                'event_id' => $orderItem->event_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-in realizado com sucesso!',
                'data' => [
                    'ticket_number' => $orderItem->ticket_number,
                    'participant_name' => $orderItem->participant_name,
                    'participant_email' => $orderItem->participant_email,
                    'event_name' => $orderItem->event_name,
                    'event_date' => $orderItem->event_date,
                    'lote_name' => $orderItem->lote_name,
                    'checkin_at' => now()->format('d/m/Y H:i:s'),
                    'status' => 'checked_in'
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao validar check-in', [
                'purchase_hash' => $purchaseHash,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar check-in. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Visualizar informações do ingresso (sem fazer check-in)
     */
    public function viewTicket(Request $request, $purchaseHash)
    {
        try {
            $orderItem = OrderItem::where('purchase_hash', $purchaseHash)
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('event_dates', 'orders.event_date_id', '=', 'event_dates.id')
                ->join('events', 'event_dates.event_id', '=', 'events.id')
                ->join('participantes', 'orders.participante_id', '=', 'participantes.id')
                ->join('lotes', 'order_items.lote_id', '=', 'lotes.id')
                ->join('places', 'events.place_id', '=', 'places.id')
                ->select(
                    'order_items.number as ticket_number',
                    'order_items.purchase_hash',
                    'order_items.checkin_status',
                    'order_items.checkin_at',
                    'orders.hash as order_hash',
                    'orders.gatway_status',
                    'events.name as event_name',
                    'event_dates.date as event_date',
                    'places.name as place_name',
                    'participantes.name as participant_name',
                    'participantes.email as participant_email',
                    'lotes.name as lote_name'
                )
                ->first();

            if (!$orderItem) {
                return view('errors.404', ['message' => 'Ingresso não encontrado.']);
            }

            return view('checkin.view_ticket', compact('orderItem'));

        } catch (\Exception $e) {
            Log::error('Erro ao visualizar ingresso', [
                'purchase_hash' => $purchaseHash,
                'error' => $e->getMessage()
            ]);

            return view('errors.500', ['message' => 'Erro ao carregar informações do ingresso.']);
        }
    }
}

