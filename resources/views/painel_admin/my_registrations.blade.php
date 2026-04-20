@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">Meus eventos</a></li>
            </ol>
            <h2>Minhas inscrições</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="app-page-head">
                    <div class="app-page-copy">
                        <span class="app-page-kicker">Conta do participante</span>
                        <h1 class="app-page-title">Minhas inscrições</h1>
                        <p class="app-page-subtitle">Acompanhe o status dos pedidos, recupere QR Codes, conclua pagamentos pendentes e acesse certificados sem precisar abrir várias telas.</p>
                    </div>
                </div>

                <div class="mb-3 px-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                            <strong>Erros encontrados:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                </div>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" action="{{ route('event_home.my_registrations') }}" class="row g-2 align-items-end">
                            <div class="col-lg-4">
                                <label for="q" class="form-label mb-1">Busca</label>
                                <input type="text" id="q" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}" placeholder="Nome do evento ou hash">
                            </div>
                            <div class="col-lg-2">
                                <label for="status" class="form-label mb-1">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>Todos</option>
                                    <option value="1" {{ ($filters['status'] ?? '') === '1' ? 'selected' : '' }}>Confirmado</option>
                                    <option value="2" {{ ($filters['status'] ?? '') === '2' ? 'selected' : '' }}>Pendente</option>
                                    <option value="3" {{ ($filters['status'] ?? '') === '3' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="date_from" class="form-label mb-1">Compra de</label>
                                <input type="date" id="date_from" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                            </div>
                            <div class="col-lg-2">
                                <label for="date_to" class="form-label mb-1">Compra até</label>
                                <input type="date" id="date_to" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                            </div>
                            <div class="col-lg-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                                <a href="{{ route('event_home.my_registrations', ['reset_filters' => 1]) }}" class="btn btn-outline-secondary">Limpar</a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Grid de Inscrições -->
                @if($orders->count() > 0)
                    <div class="registrations-grid" id="registrationsGrid">
                        @foreach($orders as $order)
                            @php
                                $statusClass = 'not-processed';
                                $statusText = 'Não processado';
                                $statusIcon = 'fa-question-circle';

                                if($order->status == 1) {
                                    $statusClass = 'confirmed';
                                    $statusText = 'Confirmado';
                                    $statusIcon = 'fa-check-circle';
                                } elseif($order->status == 2) {
                                    $statusClass = 'pending';
                                    $statusText = 'Pendente';
                                    $statusIcon = 'fa-clock';
                                } elseif($order->status == 3) {
                                    $statusClass = 'cancelled';
                                    $statusText = 'Cancelado';
                                    $statusIcon = 'fa-times-circle';
                                }
                            @endphp
                            <div class="registration-card">
                                <div class="registration-header">
                                    <div class="registration-id">ID: {{ $order->id }}</div>
                                    <div class="registration-hash">{{ $order->hash }}</div>
                                </div>
                                <div class="registration-content">
                                    <h3 class="registration-event-name">{{ $order->eventDate?->event?->name ?? 'Evento' }}</h3>
                                    <div class="registration-status {{ $statusClass }}">
                                        <i class="fas {{ $statusIcon }}"></i>
                                        <span>{{ $statusText }}</span>
                                    </div>
                                    <div class="registration-info">
                                        <div class="registration-info-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <div>
                                                <span class="registration-info-label">Data do evento:</span>
                                                {{ $order->eventDate ? \Carbon\Carbon::parse($order->eventDate->date)->format('d/m/Y') : '-' }}
                                            </div>
                                        </div>
                                        <div class="registration-info-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div>
                                                <span class="registration-info-label">Local:</span>
                                                {{ $order->eventDate?->event?->place?->name ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="registration-info-item">
                                            <i class="fas fa-shopping-cart"></i>
                                            <div>
                                                <span class="registration-info-label">Data da compra:</span>
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- QR Codes para Check-in (só pedidos confirmados) -->
                                    @if($order->status == 1 && $order->order_items && $order->order_items->count() > 0)
                                        <div class="registration-subsection qr-codes-section">
                                            <h5 class="registration-subsection-title">
                                                <i class="fas fa-qrcode"></i> QR Codes para Check-in
                                            </h5>
                                            <div class="qr-codes-grid">
                                                @foreach($order->order_items as $item)
                                                    @if($item->purchase_hash)
                                                        <div class="qr-code-item">
                                                            <div class="qr-code-meta">
                                                                Ingresso #{{ $item->number }}
                                                            </div>
                                                            @if($item->lote?->name)
                                                                <div class="qr-code-lote">
                                                                    {{ $item->lote->name }}
                                                                </div>
                                                            @endif
                                                            <div class="qr-code-canvas">
                                                                {!! QrCode::size(120)->generate(route('checkin.view', $item->purchase_hash)) !!}
                                                            </div>
                                                            @if($item->checkin_status == 1)
                                                                <div class="qr-code-status qr-code-status--success">
                                                                    <i class="fas fa-check-circle"></i> Check-in realizado
                                                                    @if($item->checkin_at)
                                                                        <span class="qr-code-status-time">
                                                                            {{ \Carbon\Carbon::parse($item->checkin_at)->format('d/m/Y H:i') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="qr-code-status qr-code-status--muted">
                                                                    Aguardando check-in
                                                                </div>
                                                            @endif
                                                            <a href="{{ route('checkin.view', $item->purchase_hash) }}"
                                                               target="_blank"
                                                               class="qr-code-link">
                                                                <i class="fas fa-external-link-alt"></i> Ver ingresso
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Dados de pagamento para pedidos pendentes -->
                                    @if($order->status == 2)
                                        @php
                                            $pixInfo = $pixDetailsMap[$order->id] ?? null;
                                            $boletoInfo = $boletoDetailsMap[$order->id] ?? null;
                                        @endphp
                                        @if($pixInfo)
                                            <div class="registration-subsection">
                                                <h5 class="registration-subsection-title">
                                                    <i class="fas fa-qrcode"></i> Pague com PIX
                                                </h5>
                                                <div class="registration-payment-box text-center">
                                                    @if($pixInfo->qr_code_base64)
                                                        <img src="data:image/png;base64,{{ $pixInfo->qr_code_base64 }}" alt="QR Code PIX" class="img-fluid mb-3 registration-payment-image">
                                                    @endif
                                                    @if($pixInfo->qr_code)
                                                        <div class="input-group mb-2 mx-auto registration-copy-group">
                                                            <input type="text" class="form-control form-control-sm" value="{{ $pixInfo->qr_code }}" id="pix_code_{{ $order->id }}" readonly>
                                                            <button class="btn btn-outline-secondary btn-sm copy-feedback-btn" onclick="navigator.clipboard.writeText(document.getElementById('pix_code_{{ $order->id }}').value).then(()=>{this.innerHTML='<i class=\'fas fa-check\'></i> Copiado';setTimeout(()=>{this.innerHTML='<i class=\'fas fa-copy\'></i> Copiar'},2000)})">
                                                                <i class="fas fa-copy"></i> Copiar
                                                            </button>
                                                        </div>
                                                    @endif
                                                    @if($pixInfo->expiration_date)
                                                        <p class="registration-inline-note">Expira em: {{ \Carbon\Carbon::parse($pixInfo->expiration_date)->format('d/m/Y H:i') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        @if($boletoInfo)
                                            <div class="registration-subsection">
                                                <h5 class="registration-subsection-title">
                                                    <i class="fas fa-barcode"></i> Boleto Bancário
                                                </h5>
                                                <div class="registration-payment-box">
                                                    @if($boletoInfo->href)
                                                        <a href="{{ $boletoInfo->href }}" target="_blank" class="btn btn-warning w-100 mb-2">
                                                            <i class="fas fa-external-link-alt me-2"></i>Abrir Boleto
                                                        </a>
                                                    @endif
                                                    @if($boletoInfo->line_code)
                                                        <div class="input-group mb-2">
                                                            <input type="text" class="form-control form-control-sm" value="{{ $boletoInfo->line_code }}" id="boleto_code_{{ $order->id }}" readonly>
                                                            <button class="btn btn-outline-secondary btn-sm copy-feedback-btn" onclick="navigator.clipboard.writeText(document.getElementById('boleto_code_{{ $order->id }}').value).then(()=>{this.innerHTML='<i class=\'fas fa-check\'></i> Copiado';setTimeout(()=>{this.innerHTML='<i class=\'fas fa-copy\'></i> Copiar'},2000)})">
                                                                <i class="fas fa-copy"></i> Copiar
                                                            </button>
                                                        </div>
                                                    @endif
                                                    @if($boletoInfo->expiration_date)
                                                        <p class="registration-inline-note">Vencimento: {{ \Carbon\Carbon::parse($boletoInfo->expiration_date)->format('d/m/Y') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <div class="registration-actions">
                                        @if($order->status == 1)
                                            <a href="{{route('event_home.order.print_voucher', $order->hash)}}"
                                               target="_blank"
                                               class="registration-btn registration-btn-primary">
                                                <i class="fas fa-print"></i>
                                                Imprimir
                                            </a>
                                            @if($order->event && $order->event->certificate_enabled && $order->event->max_event_dates() && $order->event->max_event_dates() < now()->toDateString())
                                                <a href="{{route('event_home.certificate.download', $order->hash)}}"
                                                   class="registration-btn registration-btn-primary"
                                                   title="Ver certificados disponíveis">
                                                    <i class="fas fa-certificate"></i>
                                                    Certificados
                                                </a>
                                            @endif
                                        @endif
                                        <a href="{{route('event_home.order.details', $order->hash)}}"
                                           class="registration-btn registration-btn-secondary">
                                            <i class="fas fa-eye"></i>
                                            Detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 d-flex justify-content-end">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="registrations-empty">
                        <div class="registrations-empty-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3 class="registrations-empty-title">Nenhuma inscrição encontrada</h3>
                        <p class="registrations-empty-text">Você ainda não possui inscrições em eventos.</p>
                    </div>
                @endif
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('assets/css/my-registrations-improvements.css') }}" type="text/css">
      @endpush

      @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="{{ asset('assets_admin/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

        <script>
        $(document).ready(function() {
            // Filtros de status
            $('.filter-tab').on('click', function() {
                $('.filter-tab').removeClass('active');
                $(this).addClass('active');
                
                const status = $(this).data('status');
                filterRegistrations(status);
            });
            
            // Busca
            $('#searchRegistrations').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                const activeStatus = $('.filter-tab.active').data('status');
                filterRegistrations(activeStatus, searchTerm);
            });
            
            function filterRegistrations(status, searchTerm = '') {
                $('.registration-card').each(function() {
                    const cardStatus = $(this).data('status') || '0';
                    const cardSearch = $(this).data('search') || '';
                    
                    let show = true;
                    
                    // Filtro por status
                    if (status !== 'all' && cardStatus != status) {
                        show = false;
                    }
                    
                    // Filtro por busca
                    if (searchTerm && !cardSearch.includes(searchTerm)) {
                        show = false;
                    }
                    
                    $(this).toggle(show);
                });
                
                // Verificar se há resultados
                const visibleCards = $('.registration-card:visible').length;
                if (visibleCards === 0 && $('.registrations-grid').length > 0) {
                    if ($('.no-results-message').length === 0) {
                        $('.registrations-grid').after(
                            '<div class="registrations-empty no-results-message">' +
                            '<div class="registrations-empty-icon"><i class="fas fa-search"></i></div>' +
                            '<h3 class="registrations-empty-title">Nenhum resultado encontrado</h3>' +
                            '<p class="registrations-empty-text">Tente ajustar os filtros de busca.</p>' +
                            '</div>'
                        );
                    }
                } else {
                    $('.no-results-message').remove();
                }
            }

            // Remover DataTable antigo se existir
            if ($.fn.DataTable.isDataTable('#list_events')) {
                $('#list_events').DataTable().destroy();
            }
        });
    
    </script>
      
    @endpush

</x-site-layout>
