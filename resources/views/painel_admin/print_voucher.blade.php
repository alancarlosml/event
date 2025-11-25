<x-guestsite-layout>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="logo text-center mb-4 mt-5">
                    <a href="/">
                        <img src="{{ asset('assets/img/logo_principal.png') }}" alt="Logo" style="max-width: 200px;">
                    </a>
                </div>
            </div>
            
            <section id="checkout" class="section-bg">
                <div class="container pb-5">
                    <div class="py-4 text-center">
                        <div class="section-header">
                            <h2><i class="fas fa-ticket-alt me-2"></i>Imprima seu voucher</h2>
                            <p class="lead text-muted">Por favor, apresente esse voucher na data e local do seu evento.</p>
                        </div>
                    </div>
                    
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            @foreach ($items as $k => $item)
                            <div class="card voucher-card shadow-sm mb-4 print-break">
                                <div class="card-body text-center p-4">
                                    <div class="voucher-number mb-3">
                                        <span class="badge bg-primary fs-5 px-4 py-2">#{{ $item->number }}</span>
                                    </div>
                                    
                                    <h4 class="card-title fw-bold text-dark mb-2">{{ $item->event_name }}</h4>
                                    
                                    <div class="event-details mb-3">
                                        <p class="mb-2">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($item->data_chosen)->format('d/m/Y') }}</span>
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            <span>{{ $item->place_name }}</span>
                                        </p>
                                    </div>
                                    
                                    <div class="qr-code-container my-4 p-3 bg-white d-inline-block rounded border">
                                        {!! QrCode::size(250)->generate($item->purchase_hash) !!}
                                    </div>
                                    
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Código: {{ $item->purchase_hash }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                            
                            <div class="text-center mt-4 no-print">
                                <button onclick="window.print()" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-print me-2"></i>Imprimir Vouchers
                                </button>
                                <a href="/" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                                    <i class="fas fa-home me-2"></i>Voltar ao Início
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>

    @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
        <style>
            .voucher-card {
                border: 2px solid #e9ecef;
                border-radius: 12px;
                transition: transform 0.2s;
            }
            
            .voucher-number {
                margin-bottom: 1rem;
            }
            
            .event-details {
                font-size: 1.1rem;
            }
            
            .qr-code-container {
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            @media print {
                .no-print {
                    display: none !important;
                }
                
                .voucher-card {
                    page-break-inside: avoid;
                    border: 2px solid #000;
                    margin-bottom: 2cm;
                }
                
                .print-break {
                    page-break-after: always;
                }
                
                .print-break:last-child {
                    page-break-after: auto;
                }
                
                body {
                    background: white !important;
                }
                
                .section-bg {
                    background: white !important;
                }
            }
        </style>
    @endpush

    @push('footer')
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Auto-focus print button for easier keyboard access
                console.log('Voucher page loaded successfully');
            });
        </script>
    @endpush

</x-guestsite-layout>
