<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação - {{ $event->name ?? 'Pedido' }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/frontend-unified.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if ($event && $event->theme == 'red')
        <link rel="stylesheet" href="{{ asset('assets_conference/css/red.css') }}" type="text/css">
    @elseif($event && $event->theme == 'blue')
        <link rel="stylesheet" href="{{ asset('assets_conference/css/blue.css') }}" type="text/css">
    @elseif($event && $event->theme == 'green')
        <link rel="stylesheet" href="{{ asset('assets_conference/css/green.css') }}" type="text/css">
    @elseif($event && $event->theme == 'purple')
        <link rel="stylesheet" href="{{ asset('assets_conference/css/purple.css') }}" type="text/css">
    @elseif($event && $event->theme == 'orange')
        <link rel="stylesheet" href="{{ asset('assets_conference/css/orange.css') }}" type="text/css">
    @endif
    <style>
        .confirmation-container { max-width: 700px; margin: 0 auto; }
        .status-approved { color: #198754; }
        .status-pending { color: #ffc107; }
        .status-rejected { color: #dc3545; }
        .ticket-card { border-left: 4px solid #198754; }
        .ticket-card.pending { border-left-color: #ffc107; }
    </style>
</head>
<body class="confirmation-shell-page">
    <div class="container py-5">
        <div class="confirmation-container">
            <div class="confirmation-shell-header">
                <span class="app-page-kicker">Pós-compra</span>
                <h1>Resumo da sua inscrição</h1>
                <p>Acompanhe o status do pedido, recupere instruções de pagamento e acesse seus ingressos com clareza.</p>
            </div>

            {{-- Status header --}}
            <div class="confirmation-status-hero">
                @if($order->status == 1)
                    <i class="fas fa-check-circle fa-4x status-approved mb-3"></i>
                    <h2 class="status-approved">Pagamento Aprovado!</h2>
                    <p class="text-muted">Seus ingressos estão prontos.</p>
                @elseif($order->status == 2)
                    <i class="fas fa-clock fa-4x status-pending mb-3"></i>
                    <h2 class="status-pending">Pagamento Pendente</h2>
                    <p class="text-muted">Aguardando confirmação do pagamento.</p>
                @else
                    <i class="fas fa-times-circle fa-4x status-rejected mb-3"></i>
                    <h2 class="status-rejected">Pagamento não aprovado</h2>
                    <p class="text-muted">Houve um problema com seu pagamento.</p>
                @endif
            </div>

            {{-- Event details --}}
            @if($event)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar-alt me-2"></i>{{ $event->name }}</h5>
                    @if($eventDate)
                        <p class="mb-1"><i class="fas fa-calendar me-2 text-muted"></i>{{ \Carbon\Carbon::parse($eventDate->date)->format('d/m/Y') }}</p>
                    @endif
                    @if($event->place)
                        <p class="mb-1"><i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $event->place->name }}</p>
                        @if($event->place->address)
                            <p class="mb-0 text-muted small">{{ $event->place->address }}</p>
                        @endif
                    @endif
                </div>
            </div>
            @endif

            {{-- Order summary --}}
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Resumo do Pedido</h6></div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @php $totalValue = 0; @endphp
                            @foreach($order->order_items as $item)
                                <tr>
                                    <td>{{ $item->lote?->name ?? 'Ingresso' }}</td>
                                    <td class="text-end">R$ {{ number_format($item->value, 2, ',', '.') }}</td>
                                </tr>
                                @php $totalValue += $item->value; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td>Total</td>
                                <td class="text-end">R$ {{ number_format($totalValue, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="mt-3 small text-muted">
                        @if($order->gatway_payment_method)
                            <span><i class="fas fa-credit-card me-1"></i>
                                @switch($order->gatway_payment_method)
                                    @case('credit_card') Cartão de Crédito @break
                                    @case('pix') PIX @break
                                    @case('bolbradesco') Boleto @break
                                    @case('account_money') Saldo MP @break
                                    @default {{ $order->gatway_payment_method }}
                                @endswitch
                            </span>
                        @endif
                        @if($creditDetails && $creditDetails->installments > 1)
                            <span class="ms-2">{{ $creditDetails->installments }}x de R$ {{ number_format($creditDetails->installment_amount, 2, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- PIX pending instructions --}}
            @if($order->status == 2 && $pixDetails)
            <div class="card mb-4 border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <h6 class="mb-0"><i class="fas fa-qrcode me-2"></i>Pague com PIX</h6>
                </div>
                <div class="card-body text-center">
                    @if($pixDetails->qr_code_base64)
                        <img src="data:image/png;base64,{{ $pixDetails->qr_code_base64 }}" alt="QR Code PIX" class="img-fluid mb-3" style="max-width: 250px;">
                    @endif
                    @if($pixDetails->qr_code)
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-sm" value="{{ $pixDetails->qr_code }}" id="pix_code" readonly>
                            <button class="btn btn-outline-secondary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('pix_code').value)">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </div>
                    @endif
                    @if($pixDetails->expiration_date)
                        <p class="text-muted small">Expira em: {{ \Carbon\Carbon::parse($pixDetails->expiration_date)->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Boleto pending instructions --}}
            @if($order->status == 2 && $boletoDetails)
            <div class="card mb-4 border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <h6 class="mb-0"><i class="fas fa-barcode me-2"></i>Boleto Bancário</h6>
                </div>
                <div class="card-body">
                    @if($boletoDetails->href)
                        <a href="{{ $boletoDetails->href }}" target="_blank" class="btn btn-warning w-100 mb-3">
                            <i class="fas fa-external-link-alt me-2"></i>Abrir Boleto
                        </a>
                    @endif
                    @if($boletoDetails->line_code)
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-sm" value="{{ $boletoDetails->line_code }}" id="boleto_code" readonly>
                            <button class="btn btn-outline-secondary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('boleto_code').value)">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </div>
                    @endif
                    @if($boletoDetails->expiration_date)
                        <p class="text-muted small">Vencimento: {{ \Carbon\Carbon::parse($boletoDetails->expiration_date)->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Tickets (if approved) --}}
            @if($order->status == 1)
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Seus Ingressos</h6></div>
                <div class="card-body">
                    @foreach($order->order_items as $item)
                        <div class="card ticket-card {{ $order->status != 1 ? 'pending' : '' }} mb-2">
                            <div class="card-body py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $item->lote?->name ?? 'Ingresso' }}</strong>
                                    <br><small class="text-muted">#{{ $item->number }}</small>
                                </div>
                                @if($item->purchase_hash)
                                    <a href="{{ url('/checkin/' . $item->purchase_hash) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                        <i class="fas fa-qrcode me-1"></i>QR Code
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('event_home.my_registrations') }}" class="btn btn-primary">
                    <i class="fas fa-ticket-alt me-2"></i>Ver Meus Ingressos
                </a>
                @if($event)
                    <a href="{{ url('/' . $event->slug) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar ao Evento
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
