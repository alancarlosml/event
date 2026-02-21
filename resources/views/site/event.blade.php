<x-event-layout>
<div class="event-page-wrap">
    <div class="hero-image" id="home">
        <img src="{{ URL::asset('storage/' . $event->banner) }}" alt="{{ htmlspecialchars($event->name) }}" class="img-fluid" loading="lazy">
        @php
            // Obter data e hora do evento
            $eventDate = $event->min_event_dates(); // Retorna formato YYYY-MM-DD
            $eventTime = $event->min_event_time(); // Retorna formato HH:MM:SS ou HH:MM
            
            // Garantir que a hora tenha formato completo (HH:MM:SS)
            if ($eventTime) {
                $timeParts = explode(':', $eventTime);
                if (count($timeParts) == 2) {
                    $eventTime = $eventTime . ':00'; // Adicionar segundos se não existir
                }
            } else {
                $eventTime = '00:00:00';
            }
            
            // Criar objeto Carbon com data e hora combinadas
            try {
                $eventStartDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $eventDate . ' ' . $eventTime);
            } catch (\Exception $e) {
                // Fallback: usar parse se createFromFormat falhar
                $eventStartDateTime = \Carbon\Carbon::parse($eventDate . ' ' . $eventTime);
            }
            
            // Obter data/hora atual
            $now = \Carbon\Carbon::now();
            
            // Verificar se o evento ainda não começou (data/hora do evento é no futuro)
            $eventNotStarted = $eventStartDateTime->gt($now);
        @endphp
        @if ($eventNotStarted)
            <div class="hero-content">
                <h1>{{ $event->name }}</h1>
                <p>{{ \Carbon\Carbon::parse($event->min_event_dates())->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($event->min_event_time())->format('H:i') }}h</p>
            </div>
        @endif
    </div>
    @if ($eventNotStarted)
        <div class="container">
            <div class="countdown-container" id="countdownContainer">
                <h3>O evento começa em:</h3>
                <div class="countdown" id="countdown" data-date="{{ $event->min_event_dates() }} {{ $event->min_event_time() }}">
                    <div class="countdown-item">
                        <span class="countdown-value" id="days">00</span>
                        <span class="countdown-label">Dias</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="hours">00</span>
                        <span class="countdown-label">Horas</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="minutes">00</span>
                        <span class="countdown-label">Minutos</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="seconds">00</span>
                        <span class="countdown-label">Segundos</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <section id="information-bar" class="event-info-bar">
        <div class="container">
            <div class="information-wrapper">
                <div class="info-card">
                    <ul role="list" aria-label="Informações de localização do evento">
                        <li class="info-card-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></li>
                        <li><span><b>Local</b> {{ $event->place->name }}, {{ optional($event->place->get_city)->name }}-{{ optional(optional($event->place->get_city)->state)->uf ?? optional($event->place->get_city)->uf }}</span></li>
                    </ul>
                </div>
                <div class="info-card">
                    <ul role="list" aria-label="Data e hora do evento">
                        <li class="info-card-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></li>
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
                <div class="info-card">
                    <ul role="list" aria-label="Categoria do evento">
                        <li class="info-card-icon"><i class="fa-solid fa-list-check" aria-hidden="true"></i></li>
                        <li><span><b>Categoria</b> {{ $event->area->category->description }}</span></li>
                    </ul>
                </div>
                <div class="info-card">
                    <ul role="list" aria-label="Total de vagas do evento">
                        <li class="info-card-icon"><i class="fa-solid fa-id-badge" aria-hidden="true"></i></li>
                        <li><span><b>Total de vagas</b> {{ $event->max_tickets }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="info-bar-cta">
                @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                    <a href="#inscricoes" class="btn btn-common sub-btn" aria-label="Inscrever-se no evento">Inscreva-se</a>
                @else
                    <button class="btn btn-common sub-btn disabled" aria-label="Evento encerrado" disabled>Encerrado</button>
                @endif
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
                            
                            <!-- Galeria de Imagens -->
                            @if($event->banner)
                                <div class="event-gallery mb-4">
                                    <div class="gallery-main">
                                        <a href="{{ asset('storage/' . $event->banner) }}" data-lightbox="event-gallery" data-glightbox="title: {{ htmlspecialchars($event->name) }}">
                                            <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ htmlspecialchars($event->name) }}" class="gallery-main-image" loading="lazy">
                                            <div class="gallery-overlay">
                                                <i class="fas fa-search-plus"></i>
                                                <span>Clique para ampliar</span>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    @php
                                        // Extrair imagens da descrição HTML
                                        $description = $event->description ?? '';
                                        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $description, $matches);
                                        $galleryImages = [];
                                        
                                        // Processar URLs das imagens encontradas
                                        if (!empty($matches[1])) {
                                            foreach ($matches[1] as $imgSrc) {
                                                // Se for URL relativa, converter para asset
                                                if (strpos($imgSrc, 'http') !== 0 && strpos($imgSrc, '//') !== 0) {
                                                    if (strpos($imgSrc, 'storage/') === 0) {
                                                        $galleryImages[] = asset($imgSrc);
                                                    } else {
                                                        $galleryImages[] = asset('storage/' . $imgSrc);
                                                    }
                                                } else {
                                                    $galleryImages[] = $imgSrc;
                                                }
                                            }
                                        }
                                        
                                        // Adicionar banner como primeira imagem
                                        $bannerUrl = asset('storage/' . $event->banner);
                                        $allImages = array_merge([$bannerUrl], array_unique($galleryImages));
                                        $hasMultipleImages = count($allImages) > 1;
                                    @endphp
                                    
                                    @if($hasMultipleImages)
                                        <div class="gallery-thumbnails">
                                            @foreach($allImages as $index => $image)
                                                <a href="{{ $image }}" data-lightbox="event-gallery" data-glightbox="title: {{ htmlspecialchars($event->name) }} - Imagem {{ $index + 1 }}" class="gallery-thumb {{ $index === 0 ? 'active' : '' }}">
                                                    <img src="{{ $image }}" alt="Galeria - {{ $index + 1 }}" loading="lazy">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            @php
                                $fullDescription = $event->description;
                                // Remover tags img da descrição para exibição
                                $descriptionWithoutImages = preg_replace('/<img[^>]+>/i', '', $fullDescription);
                                $shortDescription = \Illuminate\Support\Str::limit(strip_tags($descriptionWithoutImages), 500);
                                $isLong = strlen(strip_tags($descriptionWithoutImages)) > 500;
                            @endphp
                            <div class="about-text-expandable {{ !$isLong ? 'expanded' : '' }}" id="aboutText" data-full-text="{{ htmlspecialchars($descriptionWithoutImages) }}">
                                {!! $isLong ? $shortDescription : $descriptionWithoutImages !!}
                            </div>
                            @if ($isLong)
                                <div class="about-text-fade" id="aboutFade"></div>
                                <button class="btn-read-more" onclick="toggleAboutText()" id="readMoreBtn">
                                    Leia mais
                                </button>
                            @endif
                            
                            <!-- Compartilhamento Social -->
                            <div class="social-share">
                                <span class="social-share-label">
                                    <i class="fas fa-share-alt"></i> Compartilhar:
                                </span>
                                @php
                                    $eventUrl = url()->current();
                                    $eventTitle = urlencode($event->name);
                                    $eventDescription = urlencode(strip_tags(\Illuminate\Support\Str::limit($event->description, 150)));
                                @endphp
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($eventUrl) }}" 
                                   target="_blank" 
                                   class="social-share-btn facebook" 
                                   title="Compartilhar no Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode($eventUrl) }}&text={{ $eventTitle }}" 
                                   target="_blank" 
                                   class="social-share-btn twitter" 
                                   title="Compartilhar no Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ $eventTitle }}%20{{ urlencode($eventUrl) }}" 
                                   target="_blank" 
                                   class="social-share-btn whatsapp" 
                                   title="Compartilhar no WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($eventUrl) }}" 
                                   target="_blank" 
                                   class="social-share-btn linkedin" 
                                   title="Compartilhar no LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="mailto:?subject={{ $eventTitle }}&body={{ $eventDescription }}%20{{ urlencode($eventUrl) }}" 
                                   class="social-share-btn email" 
                                   title="Enviar por e-mail">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <a href="#" 
                                   onclick="copyToClipboard('{{ $eventUrl }}'); return false;" 
                                   class="social-share-btn copy-link" 
                                   title="Copiar link">
                                    <i class="fas fa-link"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="faq-section">
            <div class="container">
                <h2 class="faq-title">Perguntas Frequentes</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>Como faço para me inscrever no evento?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                Para se inscrever, selecione a data do evento, escolha o lote desejado, informe a quantidade de ingressos e clique em "Continuar". Preencha os dados solicitados e finalize o pagamento.
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>Quais são as formas de pagamento aceitas?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                Aceitamos pagamento via cartão de crédito, débito, boleto bancário e PIX através do Mercado Pago. O pagamento é 100% seguro e criptografado.
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>Posso cancelar minha inscrição?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                O cancelamento e reembolso seguem a política de cancelamento do evento. Entre em contato através do formulário de contato ou e-mail informado na página do evento para mais informações.
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>Como recebo meu ingresso?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                Após a confirmação do pagamento, você receberá o ingresso por e-mail. Você também pode acessar "Minhas Inscrições" no painel do participante para visualizar e imprimir seu ingresso.
                            </div>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>O evento oferece certificado?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                A disponibilidade de certificado depende do tipo de evento. Entre em contato com os organizadores através do formulário de contato para mais informações sobre certificados.
                            </div>
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
                                    <a class="nav-link event_date_nav @if ($total_dates == 1) active @endif" href="javascript:;" data-tab="{{ $event_date->id }}">
                                        <i class="fa-solid fa-calendar-days me-2"></i>
                                        <span>{{ \Carbon\Carbon::parse($event_date->date)->format('d/m') }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <input type="hidden" name="event_date_result" id="event_date_result" value="@if ($total_dates == 1) {{$date_min->id}} @endif">
                    <div class="lotes-container">
                        @foreach ($event->lotesAtivosHoje() as $lote)
                            @php
                                // Calcular progresso de vendas (se disponível)
                                // Tentar obter vendas do lote, se não existir, usar 0
                                $sold = 0;
                                if (method_exists($lote, 'getSoldAttribute') || isset($lote->sold)) {
                                    $sold = $lote->sold ?? 0;
                                } elseif (method_exists($lote, 'orders') || isset($lote->orders)) {
                                    // Tentar contar através de relacionamento
                                    try {
                                        $sold = $lote->orders()->where('status', 'approved')->sum('quantity') ?? 0;
                                    } catch (\Exception $e) {
                                        $sold = 0;
                                    }
                                }
                                $remaining = max(0, $lote->limit_max - $sold);
                                $progressPercent = $lote->limit_max > 0 ? min(100, ($sold / $lote->limit_max) * 100) : 0;
                                $isSoldOut = $lote->limit_max > 0 && $remaining <= 0;
                                $isLowStock = $lote->limit_max > 0 && $remaining > 0 && $remaining <= 10;
                                $isPopular = $lote->limit_max > 0 && $sold > ($lote->limit_max * 0.5); // Mais de 50% vendido
                            @endphp
                            <div class="lote-card {{ $isPopular ? 'popular' : '' }} {{ $isSoldOut ? 'sold-out' : '' }}" 
                                 lote_hash="{{ $lote->hash }}" 
                                 data-value="{{ $lote->value }}" 
                                 data-type="{{ $lote->type }}"
                                 data-original-sold="{{ $sold }}">
                                @if ($isPopular || $isLowStock || $isSoldOut)
                                    <div class="lote-badge">
                                        @if($isPopular && !$isSoldOut)
                                            <span class="badge badge-popular">⭐ Popular</span>
                                        @endif
                                        @if($isLowStock && !$isSoldOut)
                                            <span class="badge badge-warning-lote">⚠️ Últimas {{ $remaining }} vagas</span>
                                        @endif
                                        @if($isSoldOut)
                                            <span class="badge badge-danger-lote">Esgotado</span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="lote-header">
                                    <h3 class="lote-name">{{ $lote->name }}</h3>
                                    @if ($lote->description)
                                        <p class="lote-description">{{ $lote->description }}</p>
                                    @endif
                                </div>
                                
                                @if ($event->max_event_dates() >= \Carbon\Carbon::now() && !$isSoldOut)
                                    <div class="lote-pricing">
                                        <div class="price-main">
                                            <span class="currency">R$</span>
                                            <span class="amount">{{ number_format($lote->value, 2, ',', '.') }}</span>
                                        </div>
                                        @if ($lote->type == 0 && $lote->tax_service == 0)
                                            <small class="tax-info">+ taxa de R$ {{ number_format($lote->tax ?? 0, 2, ',', '.') }}</small>
                                        @endif
                                    </div>
                                    
                                    @if ($lote->limit_max > 0)
                                        <div class="lote-progress" id="progress-{{ $lote->hash }}">
                                            <div class="progress-bar">
                                                <div class="progress-fill" id="progress-fill-{{ $lote->hash }}" style="width: {{ $progressPercent }}%"></div>
                                            </div>
                                            <small class="progress-text" id="progress-text-{{ $lote->hash }}">{{ $sold }} de {{ $lote->limit_max }} vendidos</small>
                                        </div>
                                    @endif
                                    
                                    <div class="lote-quantity">
                                        <label class="lote-qty-label">Quantidade:</label>
                                        <div class="quantity-selector">
                                            <button class="qty-btn minus" type="button" onclick="decreaseQty('{{ $lote->hash }}')" disabled>-</button>
                                            <input type="number" 
                                                   class="qty-input inp-number" 
                                                   id="qty-{{ $lote->hash }}"
                                                   name="inp-number"
                                                   value="0" 
                                                   min="0" 
                                                   max="{{ $lote->limit_max }}"
                                                   data-lote-hash="{{ $lote->hash }}"
                                                   data-min="{{ $lote->limit_min }}"
                                                   data-max="{{ $lote->limit_max }}"
                                                   data-value="{{ $lote->value }}">
                                            <button class="qty-btn plus" type="button" onclick="increaseQty('{{ $lote->hash }}')">+</button>
                                        </div>
                                        <div class="quick-select">
                                            <button class="quick-btn" type="button" onclick="setQuickQty('{{ $lote->hash }}', 1)">1</button>
                                            <button class="quick-btn" type="button" onclick="setQuickQty('{{ $lote->hash }}', 2)">2</button>
                                            <button class="quick-btn" type="button" onclick="setQuickQty('{{ $lote->hash }}', 3)">3</button>
                                            <button class="quick-btn" type="button" onclick="setQuickQty('{{ $lote->hash }}', 4)">4+</button>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="lote-footer">
                                    <small class="lote-deadline">
                                        @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                                            Disponível até
                                        @else
                                            Finalizado em
                                        @endif
                                        {{ \Carbon\Carbon::parse($lote->datetime_end)->format('d/m/y \à\s H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
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
                                <div class="d-flex justify-content-between pb-3" id="free_tickets_field" style="display: none;">
                                    <strong>Ingressos gratuitos</strong>
                                    <p id="free_tickets_count">0</p>
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
                                <form id="registration-form" action="{{ route('conference.resume', $event->slug) }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="event_date_result" value="">
                                </form>

                                <button onclick="validSubmition(event)" class="btn btn-common">Continuar</button>
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
                {{ $event->place->get_city()->name }}-{{ optional($event->place->get_city()->state)->uf ?? $event->place->get_city()->uf }}
            </div>
            <div class="row">
                <div class="col-12" id="map_canvas" style="height: 450px; width: 100%; position: relative;">
                    <div class="map-actions">
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($event->place->address . ', ' . $event->place->number . ', ' . $event->place->district . ', ' . $event->place->get_city()->name . '-' . (optional($event->place->get_city()->state)->uf ?? $event->place->get_city()->uf)) }}" 
                           target="_blank" 
                           class="map-action-btn"
                           title="Abrir no Google Maps">
                            <i class="fas fa-directions"></i>
                            <span>Como chegar</span>
                        </a>
                    </div>
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

    <!-- Sticky CTA -->
    @if ($event->max_event_dates() >= \Carbon\Carbon::now())
        @php
            $minPrice = $event->lotesAtivosHoje()->min('value') ?? 0;
        @endphp
        <div class="sticky-cta" id="stickyCTA">
            <div class="sticky-cta-content">
                <div class="sticky-cta-info">
                    <span class="sticky-cta-label">A partir de</span>
                    <span class="sticky-cta-price">R$ {{ number_format($minPrice, 2, ',', '.') }}</span>
                </div>
                <button class="btn btn-primary btn-lg" onclick="scrollToInscricoes()">
                    Inscreva-se Agora
                </button>
            </div>
        </div>
    @endif
</div><!-- .event-page-wrap -->

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
        <!-- Event page typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
        <!-- UX Improvements CSS -->
        <link rel="stylesheet" href="{{ asset('assets_conference/css/ux-improvements.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('assets_conference/css/additional-improvements.css') }}" type="text/css">
        <!-- GLightbox CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" type="text/css">
    @endpush
    
    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>

    @push('footer')
        <!-- GLightbox JS -->
        <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
        <script src="{{ asset('assets_conference/js/bootstrap-input-spinner.js') }}"></script>
        <script src="{{ asset('assets_conference/js/custom-editors.js') }}"></script>
        <script src="{{ asset('assets_conference/js/validate.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <!-- Leaflet JavaScript -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>

            // ===== Contador Regressivo =====
            function initCountdown() {
                const countdownEl = document.getElementById('countdown');
                if (!countdownEl) return;
                
                const eventDateStr = countdownEl.dataset.date;
                if (!eventDateStr) return;
                
                // Parse da data no formato do backend: YYYY-MM-DD HH:MM ou HH:MM:SS
                const [datePart, timePart] = eventDateStr.trim().split(/\s+/);
                const [year, month, day] = datePart.split('-').map(Number);
                const timeParts = timePart ? timePart.split(':') : ['00', '00'];
                const hour = parseInt(timeParts[0], 10) || 0;
                const minute = parseInt(timeParts[1], 10) || 0;
                // month - 1 porque Date usa mês 0-11
                const eventDate = new Date(year, month - 1, day, hour, minute, 0).getTime();
                
                const timer = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = eventDate - now;
                    
                    if (distance < 0) {
                        clearInterval(timer);
                        const container = document.getElementById('countdownContainer');
                        if (container) {
                            container.innerHTML = '<div class="countdown-expired">O evento já começou!</div>';
                        }
                        return;
                    }
                    
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    const daysEl = document.getElementById('days');
                    const hoursEl = document.getElementById('hours');
                    const minutesEl = document.getElementById('minutes');
                    const secondsEl = document.getElementById('seconds');
                    
                    if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
                    if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
                    if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
                    if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
                }, 1000);
            }

            // ===== Sticky CTA =====
            function initStickyCTA() {
                const stickyCTA = document.getElementById('stickyCTA');
                const inscricoesSection = document.getElementById('inscricoes');
                
                if (!stickyCTA || !inscricoesSection) return;
                
                window.addEventListener('scroll', function() {
                    const inscricoesTop = inscricoesSection.offsetTop;
                    const scrollPosition = window.scrollY + window.innerHeight;
                    
                    if (scrollPosition > inscricoesTop + 100) {
                        stickyCTA.classList.add('visible');
                    } else {
                        stickyCTA.classList.remove('visible');
                    }
                });
            }

            function scrollToInscricoes() {
                const inscricoesSection = document.getElementById('inscricoes');
                if (inscricoesSection) {
                    inscricoesSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            // ===== Funções para quantidade de lotes =====
            function increaseQty(loteHash) {
                const input = document.getElementById('qty-' + loteHash);
                if (!input) return;
                
                const currentValue = parseInt(input.value) || 0;
                const maxValue = parseInt(input.getAttribute('data-max')) || 999;
                
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    updateQtyButtons(input);
                    $(input).trigger('change');
                }
            }

            function decreaseQty(loteHash) {
                const input = document.getElementById('qty-' + loteHash);
                if (!input) return;
                
                const currentValue = parseInt(input.value) || 0;
                
                if (currentValue > 0) {
                    input.value = currentValue - 1;
                    updateQtyButtons(input);
                    $(input).trigger('change');
                }
            }

            function setQuickQty(loteHash, qty) {
                const input = document.getElementById('qty-' + loteHash);
                if (!input) return;
                
                const maxValue = parseInt(input.getAttribute('data-max')) || 999;
                const finalQty = qty === 4 ? Math.min(4, maxValue) : Math.min(qty, maxValue);
                
                input.value = finalQty;
                updateQtyButtons(input);
                $(input).trigger('change');
            }

            function updateQtyButtons(input) {
                const currentValue = parseInt(input.value) || 0;
                const minValue = parseInt(input.getAttribute('data-min')) || 0;
                const maxValue = parseInt(input.getAttribute('data-max')) || 999;
                
                const card = input.closest('.lote-card');
                if (card) {
                    const minusBtn = card.querySelector('.qty-btn.minus');
                    const plusBtn = card.querySelector('.qty-btn.plus');
                    
                    if (minusBtn) {
                        minusBtn.disabled = currentValue <= minValue;
                    }
                    if (plusBtn) {
                        plusBtn.disabled = currentValue >= maxValue;
                    }
                    
                    // Atualizar barra de progresso
                    updateProgressBar(input);
                }
            }
            
            function updateProgressBar(input) {
                const loteHash = input.getAttribute('data-lote-hash');
                const currentQty = parseInt(input.value) || 0;
                const maxValue = parseInt(input.getAttribute('data-max')) || 999;
                
                if (!loteHash || maxValue <= 0) return;
                
                const progressFill = document.getElementById('progress-fill-' + loteHash);
                const progressText = document.getElementById('progress-text-' + loteHash);
                
                if (progressFill && progressText) {
                    // Obter vendas originais do elemento (armazenado em data attribute)
                    const card = input.closest('.lote-card');
                    let originalSold = 0;
                    if (card) {
                        const soldAttr = card.getAttribute('data-original-sold');
                        originalSold = soldAttr ? parseInt(soldAttr) : 0;
                    }
                    
                    // Calcular novo total vendido (original + quantidade selecionada)
                    const newSold = originalSold + currentQty;
                    const newProgressPercent = Math.min(100, (newSold / maxValue) * 100);
                    
                    // Atualizar visualmente
                    progressFill.style.width = newProgressPercent + '%';
                    progressText.textContent = newSold + ' de ' + maxValue + ' vendidos';
                }
            }

            // ===== Expandir/Colapsar texto sobre =====
            function toggleAboutText() {
                const textEl = document.getElementById('aboutText');
                const fadeEl = document.getElementById('aboutFade');
                const btnEl = document.getElementById('readMoreBtn');
                
                if (!textEl || !btnEl) return;
                
                const isExpanded = textEl.classList.contains('expanded');
                const fullText = textEl.getAttribute('data-full-text');
                
                if (isExpanded) {
                    // Colapsar
                    const shortText = '{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 500) }}';
                    textEl.innerHTML = shortText;
                    textEl.classList.remove('expanded');
                    btnEl.textContent = 'Leia mais';
                    if (fadeEl) fadeEl.style.opacity = '1';
                } else {
                    // Expandir - carregar texto completo
                    if (fullText) {
                        textEl.innerHTML = fullText;
                    }
                    textEl.classList.add('expanded');
                    btnEl.textContent = 'Leia menos';
                    if (fadeEl) fadeEl.style.opacity = '0';
                }
            }

            // ===== FAQ Toggle =====
            function toggleFAQ(element) {
                const faqItem = element.closest('.faq-item');
                const isActive = faqItem.classList.contains('active');
                
                // Fechar todos os outros
                document.querySelectorAll('.faq-item').forEach(item => {
                    if (item !== faqItem) {
                        item.classList.remove('active');
                    }
                });
                
                // Toggle do item atual
                faqItem.classList.toggle('active', !isActive);
            }
            
            // ===== Copiar Link =====
            function copyToClipboard(text) {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(() => {
                        showToast('Link copiado para a área de transferência!', 'success');
                    }).catch(() => {
                        fallbackCopyToClipboard(text);
                    });
                } else {
                    fallbackCopyToClipboard(text);
                }
            }
            
            function fallbackCopyToClipboard(text) {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    showToast('Link copiado para a área de transferência!', 'success');
                } catch (err) {
                    showToast('Erro ao copiar link', 'error');
                }
                document.body.removeChild(textArea);
            }
            
            // ===== Toast Notifications =====
            function showToast(message, type = 'info', duration = 3000) {
                const container = document.getElementById('toast-container');
                if (!container) return;
                
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                
                const icons = {
                    success: 'fa-check-circle',
                    error: 'fa-exclamation-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle'
                };
                
                toast.innerHTML = `
                    <div class="toast-icon">
                        <i class="fas ${icons[type] || icons.info}"></i>
                    </div>
                    <div class="toast-message">${message}</div>
                    <button class="toast-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                container.appendChild(toast);
                
                setTimeout(() => toast.classList.add('show'), 10);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            }

            // ===== Inicializar Galeria =====
            let eventGalleryLightbox = null;
            
            function initGallery() {
                // Inicializar GLightbox uma única vez
                if (typeof GLightbox !== 'undefined') {
                    eventGalleryLightbox = GLightbox({
                        selector: 'a[data-lightbox="event-gallery"]',
                        touchNavigation: true,
                        loop: true,
                        autoplayVideos: false,
                        openEffect: 'fade',
                        closeEffect: 'fade'
                    });
                }
                
                // Adicionar interatividade aos thumbnails
                $('.gallery-thumb').on('click', function(e) {
                    e.preventDefault();
                    $('.gallery-thumb').removeClass('active');
                    $(this).addClass('active');
                    
                    // Atualizar imagem principal
                    const newSrc = $(this).attr('href');
                    const newImgSrc = $(this).find('img').attr('src');
                    $('.gallery-main-image').attr('src', newImgSrc);
                    $('.gallery-main a').attr('href', newSrc);
                    
                    // Abrir lightbox no índice correto
                    if (eventGalleryLightbox) {
                        const index = $('.gallery-thumb').index(this);
                        eventGalleryLightbox.openAt(index);
                    }
                });
            }

            // Função para fazer a requisição AJAX do subtotal (sem debounce)
            // Definida fora do document.ready para estar disponível globalmente
            function setSubTotalAjax() {
                let subtotal = "0,00";
                let _token = '{{ csrf_token() }}';

                let dict = [];
                $("input[type=number].inp-number").each(function() {
                    let $input = $(this);
                    let $card = $input.closest('.lote-card');
                    
                    // Tentar pegar o hash de várias formas (prioridade: input > card)
                    let lote_hash = $input.attr('data-lote-hash') || 
                                   $input.data('lote-hash') ||
                                   $card.attr('lote_hash');
                    
                    let lote_quantity = parseInt($input.val()) || 0;
                    
                    console.log('Processando input:', {
                        input_id: $input.attr('id'),
                        lote_hash: lote_hash,
                        lote_quantity: lote_quantity,
                        card_exists: $card.length > 0
                    });

                    // Só adicionar ao dict se a quantidade for maior que 0 e hash existir
                    if (lote_quantity > 0) {
                        if (!lote_hash) {
                            console.error('Lote hash não encontrado para input:', $input.attr('id'));
                            return; // Pular este input
                        }
                        dict.push({
                            lote_hash: lote_hash,
                            lote_quantity: lote_quantity
                        });
                    }
                });
                
                // Se não houver nenhum lote com quantidade > 0, enviar array vazio
                // mas ainda fazer a requisição para limpar os valores
                console.log('Enviando dict para getSubTotal:', dict);
                console.log('Quantidade de lotes no dict:', dict.length);
                
                // Log detalhado de cada lote
                dict.forEach((item, index) => {
                    console.log(`Lote ${index}:`, {
                        hash: item.lote_hash,
                        quantity: item.lote_quantity
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
                    dataType: 'json',
                    success: function(response) {
                        console.log('Resposta completa do getSubTotal:', response);
                        console.log('Subtotal recebido:', response.subtotal);
                        console.log('Total recebido:', response.total);
                        
                        if (response.success) {
                            console.log('Atualizando valores no resumo...');
                            
                            // Verificar se os elementos existem
                            const $subtotal = $('#subtotal');
                            const $coupon_subtotal = $('#coupon_subtotal');
                            const $total = $('#total');
                            
                            console.log('Elementos encontrados:', {
                                subtotal_exists: $subtotal.length > 0,
                                coupon_subtotal_exists: $coupon_subtotal.length > 0,
                                total_exists: $total.length > 0
                            });
                            
                            if ($subtotal.length > 0) {
                                $subtotal.html(response.subtotal || 'R$ 0,00');
                                console.log('Subtotal atualizado para:', $subtotal.html());
                            } else {
                                console.error('Elemento #subtotal não encontrado!');
                            }
                            
                            if ($coupon_subtotal.length > 0) {
                                $coupon_subtotal.html(response.coupon_subtotal || 'R$ 0,00');
                            }
                            
                            if ($total.length > 0) {
                                $total.html(response.total || 'R$ 0,00');
                                console.log('Total atualizado para:', $total.html());
                            } else {
                                console.error('Elemento #total não encontrado!');
                            }
                            
                            console.log('Valores finais no DOM:', {
                                subtotal: $('#subtotal').html(),
                                total: $('#total').html()
                            });

                            // Handle free tickets display
                            let freeTicketsCount = 0;
                            $("input[type=number].inp-number").each(function() {
                                let quantity = parseInt($(this).val()) || 0;
                                let loteValue = parseFloat($(this).closest('.lote-card').attr('data-value')) || parseFloat($(this).attr('data-value')) || 0;

                                if (loteValue === 0 && quantity > 0) {
                                    freeTicketsCount += quantity;
                                }
                            });

                            if (freeTicketsCount > 0) {
                                $('#free_tickets_count').text(freeTicketsCount);
                                $('#free_tickets_field').show();
                            } else {
                                $('#free_tickets_field').hide();
                            }
                        }

                        if (response.error) {
                            console.error('Erro na resposta do getSubTotal:', response.error);
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

                            // Hide free tickets field on error
                            $('#free_tickets_field').hide();
                        } else if (response.success) {
                            // Se não há erro mas também não há success, verificar se os valores estão zerados
                            if (response.subtotal === 'R$ 0,00' && dict.length > 0) {
                                console.warn('⚠️ AVISO: Subtotal retornou R$ 0,00 mesmo com lotes selecionados!', {
                                    dict: dict,
                                    response: response
                                });
                                console.warn('Verifique os logs do Laravel para mais detalhes');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Tratar erros de rede ou servidor
                        let errorMessage = 'Erro ao processar solicitação. Tente novamente.';
                        
                        if (xhr.status === 429) {
                            // Rate limit - mostrar mensagem mais amigável
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            } else {
                                errorMessage = 'Você está fazendo muitas alterações muito rapidamente. Por favor, aguarde alguns segundos e tente novamente.';
                            }
                            // Não resetar valores em caso de rate limit
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                            // Resetar valores apenas se não for rate limit
                            $("input[type=number].inp-number").each(function() {
                                if ($(this).val() > 0) {
                                    $(this).val(0);
                                }
                            });
                            $('#subtotal').html('R$ 0,00');
                            $('#coupon_subtotal').html('R$ 0,00');
                            $('#total').html('R$ 0,00');
                            $('#free_tickets_field').hide();
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

            $(document).ready(function() {
                // Inicializar funcionalidades
                initCountdown();
                initStickyCTA();
                initGallery();
                
                // Atualizar botões de quantidade ao carregar
                $('.qty-input').each(function() {
                    updateQtyButtons(this);
                });
                
                // Inicializar barras de progresso
                $('.qty-input').each(function() {
                    updateProgressBar(this);
                });
                
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
                            {{ $event->place->get_city()->name }}-{{ optional($event->place->get_city()->state)->uf ?? $event->place->get_city()->uf }}
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
                                const simpleAddress = '{{ $event->place->get_city()->name }}, {{ optional($event->place->get_city()->state)->uf ?? $event->place->get_city()->uf }}, Brasil';
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
                const fullAddress = '{{ $event->place->address }}, {{ $event->place->number }}, {{ $event->place->district }}, {{ $event->place->get_city()->name }}, {{ optional($event->place->get_city()->state)->uf ?? $event->place->get_city()->uf }}, Brasil';
                
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

                // Utility function for debouncing
                function debounce(func, wait) {
                    let timeout;
                    return function executedFunction(...args) {
                        const later = () => {
                            clearTimeout(timeout);
                            func(...args);
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                }

                // Initialize free tickets display on page load (without AJAX call)
                initializeFreeTicketsDisplay();

                // Criar versão com debounce da função setSubTotal (500ms de delay)
                const setSubTotalDebounced = debounce(function() {
                    setSubTotalAjax();
                }, 500);
                
                // Substituir setSubTotal para usar a versão com debounce
                window.setSubTotal = setSubTotalDebounced;

                $('.event_date_nav').click(debounce(function(e) {
                    e.preventDefault();
                    $('.event_date_nav').removeClass('active');
                    $(this).addClass('active');
                    
                    // Tentar obter data-tab do elemento clicado ou do target
                    let clickedElement = $(this);
                    let dataTab = clickedElement.attr('data-tab');
                    
                    // Se não encontrou, tentar no target do evento
                    if (!dataTab && e.target) {
                        dataTab = $(e.target).closest('.event_date_nav').attr('data-tab');
                    }
                    
                    console.log('Elemento clicado:', clickedElement, 'data-tab:', dataTab);
                    
                    if (!dataTab) {
                        console.error('data-tab não encontrado no elemento. Elemento:', clickedElement);
                        console.error('HTML do elemento:', clickedElement[0]?.outerHTML);
                        return;
                    }
                    
                    const eventDateInput = $('#event_date_result');
                    if (eventDateInput.length === 0) {
                        console.error('Input #event_date_result não encontrado');
                        return;
                    }
                    
                    eventDateInput.val(dataTab);
                    let event_date_result = eventDateInput.val();
                    
                    if (!event_date_result) {
                        console.error('event_date_result está vazio');
                        return;
                    }
                    
                    let _token = '{{ csrf_token() }}';

                    $.ajax({
                        url: '{{ route("conference.setEventDate") }}',
                        type: 'POST',
                        data: {
                            event_date_result: event_date_result,
                            _token: _token
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log('Data do evento selecionada:', response);
                            }
                            if (response.error) {
                                console.error('Erro ao selecionar data:', response.error);
                                location.reload();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Erro na requisição setEventDate:', xhr.responseText);
                            if (xhr.status === 400) {
                                const errorMsg = xhr.responseJSON?.error || 'Erro ao selecionar data do evento';
                                alert(errorMsg);
                            }
                        }
                    });
                }));


                // Desabilitado para evitar botões duplicados - usando botões manuais
                // $(".inp-number").inputSpinner({
                //     buttonsOnly: true,
                //     autoInterval: undefined
                // });

                $("input[type=number].inp-number").change(function(e) {
                    // Atualizar botões de quantidade
                    updateQtyButtons(this);
                    
                    // Validação client-side antes de chamar setSubTotal
                    let quantity = parseInt($(this).val()) || 0;
                    let loteHash = $(this).closest('.lote-card').attr('lote_hash') || $(this).attr('data-lote-hash');
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
                });
            });

            function initializeFreeTicketsDisplay() {
                // Initialize free tickets display without making AJAX call
                let freeTicketsCount = 0;
                $("input[type=number].inp-number").each(function() {
                    let quantity = parseInt($(this).val()) || 0;
                    let loteValue = parseFloat($(this).closest('.lote-card').attr('data-value')) || parseFloat($(this).attr('data-value')) || 0;

                    if (loteValue === 0 && quantity > 0) {
                        freeTicketsCount += quantity;
                    }
                });

                if (freeTicketsCount > 0) {
                    $('#free_tickets_count').text(freeTicketsCount);
                    $('#free_tickets_field').show();
                } else {
                    $('#free_tickets_field').hide();
                }
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


            function validSubmition(event) {
                event.preventDefault(); // Prevenir o comportamento padrão do botão

                var count = 0;
                $(".inp-number").each(function() {
                    var value = parseInt($(this).val()) || 0;
                    count += value; // Soma as quantidades em vez de contar campos
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

                // Garantir que o valor seja um número válido
                var event_date_result = parseInt($('#event_date_result').val()) || 0;
                if (event_date_result === 0) {
                    $('#cupomModal').modal('show');
                    $('#cupomModal').css('padding-right', '0');
                    $('body').css('padding-right', '0');
                    $('.navbar').css('padding-right', '0');
                    $('#modal_txt').text('Por favor, selecione ao menos uma data válida.');
                    $('#modal_icon').attr('src', '/assets_conference/imgs/alert.png');
                    return false;
                }

                const form = $('#registration-form');

                // Usar POST em vez de GET para evitar problemas de URL encoding
                form.attr('method', 'POST');
                form.find('input[name="event_date_result"]').val(event_date_result);

                console.log('Valid submission called. Count:', count, 'Date:', event_date_result);
                console.log('Form method set to POST, action:', form.attr('action'));

                // Bloquear duplo clique
                const submitBtn = $('#btn-submit, button[type="submit"]');
                submitBtn.prop('disabled', true);

                // Garantir que a data está salva na sessão antes de submeter
                $.ajax({
                    url: "{{ route('conference.setEventDate') }}",
                    type: "POST",
                    data: {
                        event_date_result: event_date_result,
                        _token: $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        console.log('Event date set successfully in session:', response);
                        console.log('Submitting form via POST...');
                        form.submit();
                    },
                    error: function(xhr) {
                        console.error('Failed to set event date in session', xhr);
                        $('#cupomModal').modal('show');
                        $('#cupomModal').css('padding-right', '0');
                        $('body').css('padding-right', '0');
                        $('.navbar').css('padding-right', '0');
                        const msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Falha ao selecionar a data do evento.';
                        $('#modal_txt').text(msg);
                        $('#modal_icon').attr('src', '/assets_conference/imgs/error.png');
                        submitBtn.prop('disabled', false);
                    }
                });
            }
        </script>
    @endpush

</x-event-layout>
