@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<x-site-layout>
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <h2 class="mt-4">Visualizar Ingresso</h2>
            </div>
        </section>

        <section class="inner-page">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white text-center">
                                <h3 class="mb-0">
                                    <i class="fas fa-ticket-alt"></i> {{ $orderItem->event_name }}
                                </h3>
                            </div>
                            <div class="card-body text-center p-4">
                                <div class="mb-4">
                                    <h4 class="text-muted">Ingresso #{{ $orderItem->ticket_number }}</h4>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-calendar"></i> 
                                        {{ \Carbon\Carbon::parse($orderItem->event_date)->format('d/m/Y') }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-map-marker-alt"></i> {{ $orderItem->place_name }}
                                    </p>
                                    <p class="text-muted">
                                        <i class="fas fa-tag"></i> {{ $orderItem->lote_name }}
                                    </p>
                                </div>

                                <div class="qr-code-container mb-4" style="background: white; padding: 1.5rem; border-radius: 12px; display: inline-block;">
                                    {!! QrCode::size(250)->generate(route('checkin.view', $orderItem->purchase_hash)) !!}
                                </div>

                                <div class="ticket-info mb-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Participante:</strong><br>
                                            <span class="text-muted">{{ $orderItem->participant_name }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>E-mail:</strong><br>
                                            <span class="text-muted">{{ $orderItem->participant_email }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if($orderItem->checkin_status == 1)
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> 
                                        <strong>Check-in realizado em:</strong> 
                                        {{ \Carbon\Carbon::parse($orderItem->checkin_at)->format('d/m/Y H:i:s') }}
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> 
                                        Este ingresso está válido e aguardando check-in no dia do evento.
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <p class="text-muted small">
                                        <i class="fas fa-info-circle"></i> 
                                        Apresente este QR Code no dia do evento para realizar o check-in.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('event_home.my_registrations') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar para Minhas Inscrições
                            </a>
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-site-layout>

