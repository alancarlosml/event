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
                
                <!-- Filtros -->
                <div class="registrations-filters">
                    <div class="filter-tabs" id="statusFilters">
                        <div class="filter-tab active" data-status="all">
                            <i class="fas fa-list"></i> Todas
                        </div>
                        <div class="filter-tab" data-status="1">
                            <i class="fas fa-check-circle"></i> Confirmadas
                        </div>
                        <div class="filter-tab" data-status="2">
                            <i class="fas fa-clock"></i> Pendentes
                        </div>
                        <div class="filter-tab" data-status="3">
                            <i class="fas fa-times-circle"></i> Canceladas
                        </div>
                    </div>
                    <div class="filter-search-box">
                        <i class="fas fa-search filter-search-icon"></i>
                        <input type="text" class="form-control" id="searchRegistrations" placeholder="Buscar por nome do evento, hash...">
                    </div>
                </div>
                
                <!-- Grid de Inscrições -->
                @if(count($orders) > 0)
                    <div class="registrations-grid" id="registrationsGrid">
                        @foreach($orders as $order)
                            @php
                                $statusClass = 'not-processed';
                                $statusText = 'Não processado';
                                $statusIcon = 'fa-question-circle';
                                
                                if(isset($order->gatway_status)) {
                                    if($order->gatway_status == 1) {
                                        $statusClass = 'confirmed';
                                        $statusText = 'Confirmado';
                                        $statusIcon = 'fa-check-circle';
                                    } elseif($order->gatway_status == 2) {
                                        $statusClass = 'pending';
                                        $statusText = 'Pendente';
                                        $statusIcon = 'fa-clock';
                                    } elseif($order->gatway_status == 3) {
                                        $statusClass = 'cancelled';
                                        $statusText = 'Cancelado';
                                        $statusIcon = 'fa-times-circle';
                                    }
                                }
                            @endphp
                            <div class="registration-card" data-status="{{ $order->gatway_status ?? '0' }}" data-search="{{ strtolower($order->event_name . ' ' . $order->order_hash) }}">
                                <div class="registration-header">
                                    <div class="registration-id">ID: {{ $order->order_id }}</div>
                                    <div class="registration-hash">{{ $order->order_hash }}</div>
                                </div>
                                <div class="registration-content">
                                    <h3 class="registration-event-name">{{ $order->event_name }}</h3>
                                    <div class="registration-status {{ $statusClass }}">
                                        <i class="fas {{ $statusIcon }}"></i>
                                        <span>{{ $statusText }}</span>
                                    </div>
                                    <div class="registration-info">
                                        <div class="registration-info-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <div>
                                                <span class="registration-info-label">Data do evento:</span>
                                                {{ \Carbon\Carbon::parse($order->data_chosen)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <div class="registration-info-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div>
                                                <span class="registration-info-label">Local:</span>
                                                {{ $order->place_name }}
                                            </div>
                                        </div>
                                        <div class="registration-info-item">
                                            <i class="fas fa-shopping-cart"></i>
                                            <div>
                                                <span class="registration-info-label">Data da compra:</span>
                                                {{ \Carbon\Carbon::parse($order->event_date)->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- QR Codes para Check-in -->
                                    @if($order->gatway_status == 1 && isset($order->order_items) && $order->order_items->count() > 0)
                                        <div class="qr-codes-section" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e9ecef;">
                                            <h5 style="font-size: 1rem; font-weight: 600; color: #333; margin-bottom: 1rem;">
                                                <i class="fas fa-qrcode"></i> QR Codes para Check-in
                                            </h5>
                                            <div class="qr-codes-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                                                @foreach($order->order_items as $item)
                                                    @if($item->purchase_hash)
                                                        <div class="qr-code-item" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; text-align: center;">
                                                            <div style="font-size: 0.75rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">
                                                                Ingresso #{{ $item->number }}
                                                            </div>
                                                            @if($item->lote_name)
                                                                <div style="font-size: 0.7rem; color: #999; margin-bottom: 0.5rem;">
                                                                    {{ $item->lote_name }}
                                                                </div>
                                                            @endif
                                                            <div style="background: white; padding: 0.5rem; border-radius: 4px; display: inline-block; margin-bottom: 0.5rem;">
                                                                {!! QrCode::size(120)->generate(route('checkin.view', $item->purchase_hash)) !!}
                                                            </div>
                                                            @if($item->checkin_status == 1)
                                                                <div style="font-size: 0.7rem; color: #28a745; font-weight: 600; margin-top: 0.5rem;">
                                                                    <i class="fas fa-check-circle"></i> Check-in realizado
                                                                </div>
                                                                @if($item->checkin_at)
                                                                    <div style="font-size: 0.65rem; color: #666; margin-top: 0.25rem;">
                                                                        {{ \Carbon\Carbon::parse($item->checkin_at)->format('d/m/Y H:i') }}
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <div style="font-size: 0.7rem; color: #666; margin-top: 0.5rem;">
                                                                    Aguardando check-in
                                                                </div>
                                                            @endif
                                                            <a href="{{ route('checkin.view', $item->purchase_hash) }}" 
                                                               target="_blank" 
                                                               style="display: inline-block; margin-top: 0.5rem; font-size: 0.75rem; color: #007bff; text-decoration: none;">
                                                                <i class="fas fa-external-link-alt"></i> Ver ingresso
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="registration-actions">
                                        @if($order->gatway_status == 1)
                                            <a href="{{route('event_home.order.print_voucher', $order->order_hash)}}" 
                                               target="_blank" 
                                               class="registration-btn registration-btn-primary">
                                                <i class="fas fa-print"></i>
                                                Imprimir
                                            </a>
                                        @endif
                                        <a href="{{route('event_home.order.details', $order->order_hash)}}" 
                                           class="registration-btn registration-btn-secondary">
                                            <i class="fas fa-eye"></i>
                                            Detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="{{ asset('assets_admin/jquery.datetimepicker.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
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