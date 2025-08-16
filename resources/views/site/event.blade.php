<x-event-layout>
    <div class="hero-image" id="home">
        <img src="{{ URL::asset('storage/' . $event->banner) }}" alt="{{ $event->name }}" class="img-fluid" loading="lazy">
    </div>
    <section id="information-bar">
        <div class="container">
            <div class="row inforation-wrapper">
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul role="list" aria-label="Informações de localização do evento">
                        <li>
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                        </li>
                        <li><span><b>Local</b> {{ $event->place->name }}, {{ optional($event->place->get_city)->name }}-{{ optional($event->place->get_city)->uf }}</span></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul role="list" aria-label="Data e hora do evento">
                        <li>
                            <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                        </li>
                        <li><span><b>Data &amp; Hora</b>
                            @if ($event->min_event_dates() != $event->max_event_dates())
                                De {{ \Carbon\Carbon::parse($event->min_event_dates())->format('d/m') }} a {{ \Carbon\Carbon::parse($event->max_event_dates())->format('d/m') }}
                            @else
                                {{ \Carbon\Carbon::parse($event->min_event_dates())->format('d/m') }}
                            @endif ,
                            {{ \Carbon\Carbon::parse($event->min_event_time())->format('H:i') }}h
                        </span></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul role="list" aria-label="Categoria do evento">
                        <li>
                            <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                        </li>
                        <li><span><b>Categoria</b> {{ $event->area->category->description }}</span></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul role="list" aria-label="Total de vagas do evento">
                        <li>
                            <i class="fa-solid fa-id-badge" aria-hidden="true"></i>
                        </li>
                        <li><span><b>Total de vagas</b> {{ $event->max_tickets }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-4">
                <div class="text-center">
                    @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                        <a href="#inscricoes" class="btn btn-common sub-btn" aria-label="Inscrever-se no evento">Inscreva-se</a>
                    @else
                        <button class="btn btn-common sub-btn disabled" aria-label="Evento encerrado" disabled>Encerrado</button>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="sobre" class="section-padding mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="about-content">
                        <div>
                            <div class="about-text">
                                <h2>Sobre o evento</h2>
                            </div>
                            {!! $event->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="inscricoes" class="intro section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-header text-center pb-4">
                        <h2 class="section-title wow fadeInUp animated" data-wow-delay="0.2s" style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">Inscrições</h2>
                    </div>
                </div>
            </div>
            <div class="row intro-wrapper">
                <div class="col-lg-8 col-sm-12">
                    @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($event->event_dates as $event_date)
                                <li class="nav-item">
                                    <a class="nav-link event_date_nav @if ($total_dates == 1) active @endif" href="javascript:;" data-tab="{{ $event_date->id }}">{{ \Carbon\Carbon::parse($event_date->date)->format('d/m') }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <input type="hidden" name="event_date_result" id="event_date_result" value="@if ($total_dates == 1) {{ $date_min->id }} @endif">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <thead>
                                    <th>Lote</th>
                                    <th>
                                        @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                                            Valor
                                        @endif
                                    </th>
                                    <th class="text-center">
                                        @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                                            Quantidade
                                        @endif
                                    </th>
                                </thead>
                                @foreach ($event->lotesAtivosHoje() as $lote)
                                    <tr class="border-bottom" lote_hash="{{ $lote->hash }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ps-3 d-flex flex-column">
                                                    <p class="fw-bold text-uppercase"> <b>{{ $lote->name }} </b></p>
                                                    @if ($lote->description)
                                                        <p class="fw-bold"> {{ $lote->description }} </p>
                                                    @endif
                                                    <em>
                                                        @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                                                            Disponível até
                                                        @else
                                                            Finalizado em
                                                        @endif
                                                        {{ \Carbon\Carbon::parse($lote->datetime_end)->format('d/m/y \à\s h:i') }}
                                                    </em>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                                                <div class="d-flex">
                                                    <p class="pe-3">
                                                        <span class="red">@money($lote->value)</span><br />
                                                        @if ($lote->type == 0)
                                                            <small>+ taxa de @money($lote->value * 0.1)</small>
                                                        @endif
                                                    </p>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                                                    <span class="pe-3 ml-2">
                                                        <input class="inp-number" name="inp-number" type="number"
                                                               style="min-width: 1.5rem"
                                                               value="{{ old('inp-number', 0) }}" min="0"
                                                               max="{{ $lote->limit_max }}" 
                                                               data-min="{{ $lote->limit_min }}" 
                                                               data-max="{{ $lote->limit_max }}" />
                                                        {{-- <input class="ps-2" type="number" value="{{$lote->limit_min}}" min="{{$lote->limit_min}}" max="{{$lote->limit_max}}"> --}}
                                                    </span>
                                                @else
                                                    Encerrado
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12 payment-summary">
                    <div class="card px-md-3 px-2">
                        <h6>Resumo</h6>
                        <hr>
                        @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                            <div class="input-group mb-3">
                                <input type="text" class="form-control ps-2" placeholder="CUPOM" aria-label="CUPOM" aria-describedby="button-cupom" id="inp_coupon">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn_apply_coupon rounded-0" style="font-weight: 700" type="button" id="button-cupom" onclick="setCoupon('{{ $event->hash }}')">Aplicar</button>
                                </div>
                            </div>
                            <div class="d-flex flex-column b-bottom">
                                <div class="d-flex justify-content-between py-3">
                                    <strong>Sub-total</strong>
                                    @if (isset($subtotal))
                                        <p id="subtotal">@money($subtotal)</p>
                                    @else
                                        <p id="subtotal">R$ 0,00</p>
                                    @endif
                                </div>
                                @if (isset($coupon))
                                    <div id="cupom_field" class="d-flex justify-content-between pb-3">
                                        <strong>Cupom ({{ $coupon[0]['code'] }}) <a href="javascript:;"
                                               onclick="removeCoupon()" title="Remover cupom"><i
                                                   class="fa-regular fa-trash-can"></i></a></strong>
                                        <p id="cupom_subtotal">- @money($coupon_subtotal)</p>
                                    </div>
                                @else
                                    <div id="cupom_field" class="d-flex justify-content-between pb-3"
                                         style="display: none !important;">
                                        <strong>Cupom (<span id="cupom_code"></span>)</strong>
                                        <p id="cupom_subtotal">- R$ 0,00</p>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between">
                                    <strong>Valor total</strong>
                                    <p id="total">R$ 0,00</p>
                                </div>
                            </div>
                            <div class="d-flex flex-column b-bottom mt-4">
                                <a href="{{ route('conference.resume', $event->slug) }}"
                                   onclick="return validSubmition()" class="btn btn-common">Continuar</a>
                            </div>
                        @else
                            <span>Esse evento já ocorreu. Agradecemos seu interesse!</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="local">
        <div class="row">
            <div class="col-12">
                <div class="section-title-header text-center pb-4">
                    <h2 class="section-title wow fadeInUp animated" data-wow-delay="0.2s"
                        style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">Local</h2>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="text-center mb-3" style="font-size: 18px">
                <b>{{ $event->place->name }}.</b> {{ $event->place->address }}, {{ $event->place->number }}. Bairro:
                {{ $event->place->district }}. CEP: {{ $event->place->zip }}.
                {{ $event->place->get_city()->name }}-{{ $event->place->get_city()->uf }}
            </div>
            <div class="row">
                <div class="col-12" id="map_canvas" style="height: 450px; width: 100%; position: relative;">
                    <div id="map-loading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Carregando mapa...</span>
                            </div>
                            <p class="mt-2 mb-0">Carregando mapa...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row contact-wrapper">

                {{-- <div class="col-lg-4 col-md-5 col-xs-12">
                    <ul>
                        <li>
                            <i class="lni-home"></i>
                        </li>
                        <li><span>Cesare Rosaroll, 118 80139 Eventine</span></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-3 col-xs-12">
                    <ul>
                        <li>
                            <i class="lni-phone"></i>
                        </li>
                        <li><span>+789 123 456 79</span></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-3 col-xs-12">
                    <ul>
                        <li>
                            <i class="lni-envelope"></i>
                        </li>
                        <li><span>Support@example.com</span></li>
                    </ul>
                </div> --}}
            </div>
        </div>
    </section>
    <section id="contato" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-header text-center pb-4">
                        <h2 class="section-title wow fadeInUp animated" data-wow-delay="0.2s"
                            style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">
                            Organizador</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="schedule-area row wow fadeInDown animated" data-wow-delay="0.3s"
                         style="visibility: visible;-webkit-animation-delay: 0.3s; -moz-animation-delay: 0.3s; animation-delay: 0.3s;">
                        <div class="schedule-tab-content col-12 clearfix">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="monday" role="tabpanel"
                                     aria-labelledby="monday-tab">
                                    <div id="accordion">
                                        @if (isset($event->owner))
                                            <div class="card">
                                                <div id="headingOne">
                                                    <div class="collapsed card-header">
                                                        <div class="row">
                                                            <div class="col-sm-2 col-md-2">
                                                                @if($event->owner->icon && $event->owner->icon != "")
                                                                    <img src="{{ URL::asset('storage/' . $event->owner->icon) }}"
                                                                         alt="{{ $event->owner->name }}"
                                                                         class="img-fluid">
                                                                @else
                                                                    <div class="img-fluid" style="width: 100%; height: 100px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border: 1px solid #dee2e6;">
                                                                        <i class="fas fa-user" style="font-size: 2rem; color: #6c757d;"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-10 col-md-10">
                                                                <h6>{{ $event->owner->name }}</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="owner-info">
                                                    <div class="card-body">
                                                        <p>{{ $event->owner->description }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="wow fadeInDown animated duvida-contato text-center" data-wow-delay="0.2s"
                   style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">
                    <b>Em caso de alguma dúvida, por favor, entre em contato com o organizador do evento.</b></p>
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="container-form wow fadeInLeft animated" data-wow-delay="0.2s"
                         style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">
                        <div class="form-wrapper">
                            <form role="form" method="post" id="contactForm" name="contact-form"
                                  action="{{ route('contact_event', $event->hash) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-line">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name" name="name"
                                                   placeholder="Nome" required data-error="Please enter your name">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-line">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" name="email"
                                                   placeholder="Email" required data-error="Please enter your Email">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-line">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="msg_subject"
                                                   name="subject" placeholder="Assunto" required
                                                   data-error="Please enter your message subject">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-line">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="msg_phone" name="phone"
                                                   placeholder="Telefone" required
                                                   data-error="Please enter your message subject">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-line">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="4" id="text" name="text" required
                                                      data-error="Write your message" placeholder="Mensagem"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <div class="loading">Carregando...</div>
                                        <div class="error-message"></div>
                                        <div class="sent-message">Sua mensagem foi enviada. Obrigado!</div>

                                        <button type="submit" class="btn btn-common">Enviar mensagem</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade mt-5" id="cupomModal" tabindex="-1" role="dialog"
             aria-labelledby="cupomModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong class="modal-title" id="cupomModalLabel"><img id="modal_icon"
                                 src="/assets_conference/imgs/success.png" style="max-height: 48px"> <span
                                  id="modal_txt">Cupom adicionado com sucesso!</span></strong>
                        {{-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&#10005;</span>
                        </button> --}}
                    </div>
                    {{-- <div class="modal-body">
                    ...
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" id="modal_close" class="btn btn-secondary"
                                data-bs-dismiss="modal">Ok</button>
                        {{-- <button type="button" class="btn btn-primary">Ok</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('event_name')
        {{ $event->name }}
    @endpush

    @push('theme')
        @if ($event->theme == 'red')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/red.css') }}" type="text/css">
        @elseif($event->theme == 'blue')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/blue.css') }}" type="text/css">
        @elseif($event->theme == 'green')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/green.css') }}"
                  type="text/css">
        @elseif($event->theme == 'purple')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/purple.css') }}"
                  type="text/css">
        @elseif($event->theme == 'orange')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/orange.css') }}"
                  type="text/css">
        @endif
    @endpush

    @push('head')
    @endpush

    @push('footer')
        <script src="{{ asset('assets_conference/js/bootstrap-input-spinner.js') }}"></script>
        <script src="{{ asset('assets_conference/js/custom-editors.js') }}"></script>
        <script src="{{ asset('assets_conference/js/validate.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <!-- Leaflet JavaScript -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>

            $(document).ready(function() {
                // Função para criar mapa padrão (fallback)
                function createDefaultMap() {
                    document.getElementById('map-loading').style.display = 'none';
                    const map = L.map('map_canvas').setView([-15.7801, -47.9292], 4);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);
                    
                    const errorDiv = document.createElement('div');
                    errorDiv.style.cssText = 'position: absolute; top: 10px; left: 10px; background: white; padding: 10px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 1000;';
                    errorDiv.innerHTML = '<p style="margin: 0; color: #dc3545; font-size: 14px;">⚠️ Não foi possível carregar a localização exata</p>';
                    document.getElementById('map_canvas').appendChild(errorDiv);
                }
                
                // Função para criar o mapa
                function createMap(lat, lon) {
                    document.getElementById('map-loading').style.display = 'none';
                    // Criar mapa com Leaflet
                    const map = L.map('map_canvas').setView([lat, lon], 15);
                    
                    // Adicionar camada de tiles do OpenStreetMap
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);
                    
                    // Adicionar marcador
                    const marker = L.marker([lat, lon]).addTo(map);
                    
                    // Adicionar popup com informações do local
                    const popupContent = `
                        <div style="text-align: center;">
                            <strong>{{ $event->place->name }}</strong><br>
                            {{ $event->place->address }}, {{ $event->place->number }}<br>
                            {{ $event->place->district }}<br>
                            {{ $event->place->get_city()->name }}-{{ $event->place->get_city()->uf }}
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                }
                
                // Função para geocodificar endereço usando Nominatim (OpenStreetMap)
                function geocodeAddress(address, isRetry = false) {
                    // Verificar cache local primeiro
                    const cacheKey = `map_${btoa(address).replace(/[^a-zA-Z0-9]/g, '')}`;
                    const cachedData = localStorage.getItem(cacheKey);
                    
                    if (cachedData && !isRetry) {
                        try {
                            const data = JSON.parse(cachedData);
                            if (data.timestamp && (Date.now() - data.timestamp) < 86400000) { // 24 horas
                                createMap(data.lat, data.lon);
                                return;
                            }
                        } catch (e) {
                            // Cache inválido, continuar com geocodificação
                        }
                    }
                    
                    const nominatimUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`;
                    
                    fetch(nominatimUrl)
                        .then(response => response.json())
                        .then(data => {
                            // Esconder indicador de carregamento
                            document.getElementById('map-loading').style.display = 'none';
                            
                            if (data && data.length > 0) {
                                const lat = parseFloat(data[0].lat);
                                const lon = parseFloat(data[0].lon);
                                
                                // Salvar no cache
                                if (!isRetry) {
                                    localStorage.setItem(cacheKey, JSON.stringify({
                                        lat: lat,
                                        lon: lon,
                                        timestamp: Date.now()
                                    }));
                                }
                                
                                createMap(lat, lon);
                                
                            } else if (!isRetry) {
                                // Se não conseguir geocodificar, tentar com endereço mais simples
                                const simpleAddress = '{{ $event->place->get_city()->name }}, {{ $event->place->get_city()->uf }}, Brasil';
                                geocodeAddress(simpleAddress, true);
                            } else {
                                createDefaultMap();
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao geocodificar:', error);
                            createDefaultMap();
                        });
                }
                

                
                // Endereço completo para geocodificação
                const fullAddress = '{{ $event->place->address }}, {{ $event->place->number }}, {{ $event->place->district }}, {{ $event->place->get_city()->name }}, {{ $event->place->get_city()->uf }}, Brasil';
                
                // Timeout para evitar carregamento infinito (8 segundos)
                const timeoutId = setTimeout(() => {
                    createDefaultMap();
                }, 8000);
                
                // Iniciar geocodificação
                geocodeAddress(fullAddress);
                
                // Limpar timeout quando o mapa for carregado com sucesso
                const originalGeocodeAddress = geocodeAddress;
                geocodeAddress = function(address, isRetry = false) {
                    clearTimeout(timeoutId);
                    originalGeocodeAddress(address, isRetry);
                };

                $('#msg_phone').mask('(00) 00000-0000');

                $('.event_date_nav').click(function() {

                    $('.event_date_nav').removeClass('active');
                    $(this).addClass('active');
                    $('#event_date_result').val($(this).attr('data-tab'));

                    let event_date_result = $('#event_date_result').val();
                    let _token = '{{ csrf_token() }}';

                    $.ajax({
                        // url: "/getSubTotal",
                        url: "{{ route('conference.setEventDate') }}",
                        type: "POST",
                        data: {
                            event_date_result: event_date_result,
                            _token: _token
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log(response);
                                // $('.success').text(response.success);
                            }

                            if (response.error) {
                                location.reload();
                            }
                        },
                    });
                });

                $(".inp-number").inputSpinner({
                    buttonsOnly: true,
                    autoInterval: undefined
                });

                $("input[type=number].inp-number").change(function(e) {
                    // let lote_hash = $(this).parents('tr').attr('lote_hash');
                    // let lote_quantity = $(this).val();
                    
                    // Validação client-side antes de chamar setSubTotal
                    let quantity = parseInt($(this).val()) || 0;
                    let loteHash = $(this).parents('tr').attr('lote_hash');
                    let limitMin = parseInt($(this).attr('data-min')) || 0;
                    let limitMax = parseInt($(this).attr('data-max')) || 999;
                    
                    // Validar limites mínimos e máximos
                    if (quantity > 0) {
                        if (quantity < limitMin) {
                            $('#modal_txt').text(`Quantidade mínima para este lote é ${limitMin} ingressos.`);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/alert.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                            
                            // Resetar valor para 0
                            $(this).val(0);
                            return;
                        }
                        
                        if (quantity > limitMax) {
                            $('#modal_txt').text(`Quantidade máxima para este lote é ${limitMax} ingressos.`);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/alert.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                            
                            // Ajustar valor para o máximo permitido
                            $(this).val(limitMax);
                        }
                    }
                    
                    setSubTotal();
                    // alert(lote_hash);
                    // $('.inp-number').each(function() {
                    //     // sum += parseInt($(this).val());
                    //     e.preventDefault();
                    //     alert($(this).val());
                    // });
                });
            });

            function setSubTotal() {

                let subtotal = "0,00";
                let _token = '{{ csrf_token() }}';

                let dict = [];
                $("input[type=number].inp-number").each(function() {
                    let lote_hash = $(this).parents('tr').attr('lote_hash');
                    let lote_quantity = $(this).val();

                    dict.push({
                        lote_hash: lote_hash,
                        lote_quantity: lote_quantity
                    });
                });

                $.ajax({
                    // url: "/getSubTotal",
                    url: "{{ route('conference.getSubTotal') }}",
                    type: "POST",
                    data: {
                        dict: dict,
                        _token: _token
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            // $('.success').text(response.success);
                            $('#subtotal').html(response.subtotal);
                            $('#coupon_subtotal').html(response.coupon_subtotal);
                            $('#total').html(response.total);
                        }

                        if (response.error) {
                            // Mostrar erro para o usuário em vez de apenas recarregar
                            $('#modal_txt').text(response.error);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/error.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                            
                            // Resetar valores para 0 em caso de erro
                            $("input[type=number].inp-number").each(function() {
                                if ($(this).val() > 0) {
                                    $(this).val(0);
                                }
                            });
                            
                            // Atualizar subtotal para 0
                            $('#subtotal').html('R$ 0,00');
                            $('#coupon_subtotal').html('R$ 0,00');
                            $('#total').html('R$ 0,00');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Tratar erros de rede ou servidor
                        let errorMessage = 'Erro ao processar solicitação. Tente novamente.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        
                        $('#modal_txt').text(errorMessage);
                        $('#modal_icon').attr('src', '/assets_conference/imgs/error.png');
                        $('#cupomModal').modal('show');
                        $('#cupomModal').css('padding-right', '0');
                        $('body').css('padding-right', '0');
                        $('.navbar').css('padding-right', '0');
                        
                        console.log('Erro na requisição:', xhr.responseText);
                    }
                });
            }

            function setCoupon(hash) {

                let couponCode = $('#inp_coupon').val();
                let eventHash = hash;
                let _token = '{{ csrf_token() }}';

                if (couponCode == '') {
                    $('#modal_txt').text('Por favor, insira um cupom válido.');
                    $('#modal_icon').attr('src', '/assets_conference/imgs/alert.png');
                    $('#cupomModal').modal('show');
                    $('#cupomModal').css('padding-right', '0');
                    $('body').css('padding-right', '0');
                    $('.navbar').css('padding-right', '0');

                    return;
                }

                $.ajax({
                    url: "/getCoupon",
                    type: "POST",
                    data: {
                        couponCode: couponCode,
                        eventHash: eventHash,
                        _token: _token
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            $('#modal_txt').text(response.success);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/success.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                            $('#cupom_field').show();
                            let coupon_code = response.coupon[0]['code'];
                            let coupon_type = response.coupon[0]['type'];
                            let coupon_value = response.coupon[0]['value'];
                            $('#cupom_code').text(coupon_code);
                            $('#cupom_subtotal').text('- R$ ' + parseInt(response.coupon_discount).toFixed(2)
                                .replace(".", ","));
                            $('#total').html('R$ ' + parseInt(response.total).toFixed(2).replace(".", ","));
                        }

                        if (response.error) {
                            $('#modal_txt').text(response.error);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/error.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                        }

                        if (response.alert) {
                            $('#modal_txt').text(response.alert);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/alert.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                        }
                    },

                    error: function(response) {
                        console.log(response);
                        if (response) {
                            $('#modal_txt').text(response.error);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/error.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                        }
                    },
                });
            }

            function removeCoupon() {

                let _token = '{{ csrf_token() }}';

                $.ajax({
                    url: "{{ route('conference.removeCoupon', $event->slug) }}",
                    type: "DELETE",
                    data: {
                        _token: _token
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#modal_txt').text(response.success);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/success.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                            $('#cupom_field').hide();
                            $('#subtotal').text(response.subtotal);
                        }

                        if (response.error) {
                            $('#modal_txt').text(response.error);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/error.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                        }
                    },

                    error: function(response) {
                        console.log(response);
                        if (response) {
                            $('#modal_txt').text(response.error);
                            $('#modal_icon').attr('src', '/assets_conference/imgs/error.png');
                            $('#cupomModal').modal('show');
                            $('#cupomModal').css('padding-right', '0');
                            $('body').css('padding-right', '0');
                            $('.navbar').css('padding-right', '0');
                        }
                    },
                });
            }


            function validSubmition() {

                // let customSelect = $('.custom-select').val();

                var count = 0;
                $(".inp-number").each(function() {
                    if ($(this).val() !== '0') {
                        count++
                    }
                });

                if (count === 0) {

                    $('#cupomModal').modal('show');
                    $('#cupomModal').css('padding-right', '0');
                    $('body').css('padding-right', '0');
                    $('.navbar').css('padding-right', '0');
                    $('#modal_txt').text('Por favor, selecione ao menos um ingresso.');
                    $('#modal_icon').attr('src', '/assets_conference/imgs/alert.png');
                    return false;
                }

                var event_date_result = $('#event_date_result').val();
                if (event_date_result === "") {

                    $('#cupomModal').modal('show');
                    $('#cupomModal').css('padding-right', '0');
                    $('body').css('padding-right', '0');
                    $('.navbar').css('padding-right', '0');
                    $('#modal_txt').text('Por favor, selecione ao menos uma data válida.');
                    $('#modal_icon').attr('src', '/assets_conference/imgs/alert.png');
                    return false;
                }

                return true;
            }
        </script>
    @endpush

</x-event-layout>
