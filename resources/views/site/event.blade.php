<x-event-layout>
    <div class="hero-image" id="home">
        <img src="{{ URL::asset('storage/' . $event->banner) }}" alt="{{ $event->name }}" class="img-fluid">
    </div>
    <section id="information-bar">
        <div class="container">
            <div class="row inforation-wrapper">
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul>
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                        </li>
                        <li><span><b>Local</b> {{ $event->place->name }},
                                {{ $event->place->get_city()->name }}-{{ $event->place->get_city()->uf }}</span></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul>
                        <li>
                            <i class="fa-solid fa-calendar-check"></i>
                        </li>
                        <li><span><b>Data &amp; Hora</b>
                                @if ($event->min_event_dates() != $event->max_event_dates())
                                    De {{ \Carbon\Carbon::parse($event->min_event_dates())->format('d/m') }} a
                                    {{ \Carbon\Carbon::parse($event->max_event_dates())->format('d/m') }}@else{{ \Carbon\Carbon::parse($event->min_event_dates())->format('d/m') }}
                                @endif,
                                {{ \Carbon\Carbon::parse($event->min_event_time())->format('H:i') }}h
                            </span></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul>
                        <li>
                            <i class="fa-solid fa-list-check"></i>
                        </li>
                        <li><span><b>Categoria</b> {{ $event->area->category->description }}</span></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul>
                        <li>
                            <i class="fa-solid fa-id-badge"></i>
                        </li>
                        <li><span><b>Total de vagas</b> {{ $event->max_tickets }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-4">
                <div class="mx-auto">
                    {{-- {{dd($event->max_event_dates())}} --}}
                    @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                        <a href="#inscricoes" class="btn btn-common sub-btn">Inscreva-se</a>
                    @else
                        <button class="btn btn-common sub-btn disabled">Encerrado</button>
                    @endif
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
                        <h2 class="section-title wow fadeInUp animated" data-wow-delay="0.2s" style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">
                            Inscrições
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row intro-wrapper">
                <div class="col-lg-8 col-sm-12">
                    @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                        <ul class="nav nav-tabs">
                            @foreach ($event->event_dates as $event_date)
                                <li class="nav-item">
                                    <a class="nav-link event_date_nav @if ($total_dates == 1) active @endif" href="javascript:;" data-tab="{{ $event_date->id }}">{{ \Carbon\Carbon::parse($event_date->date)->format('d/m') }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <input type="hidden" name="event_date_result" id="event_date_result" value="@if ($total_dates == 1) {{ $date_min->id }} @endif">
                    <div class="table-responsive">
                        <table class="table">
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
                                                               max="{{ $lote->limit_max }}" />
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
                                <input type="text" class="form-control ps-2" placeholder="CUPOM" aria-label="CUPOM"
                                       aria-describedby="button-cupom" id="inp_coupon">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn_apply_coupon" style="font-weight: 700"
                                            type="button" id="button-cupom"
                                            onclick="setCoupon('{{ $event->hash }}')">Aplicar</button>
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
                        style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">
                        Local</h2>
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
                <div class="col-12" id="map_canvas">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.841148750037!2d-44.311417949527524!3d-2.5584934981266056!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7f6860324886045%3A0x1024f6caa9406aaa!2sUniversidade%20Federal%20do%20Maranh%C3%A3o!5e0!3m2!1spt-BR!2sbr!4v1659628612287!5m2!1spt-BR!2sbr"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
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
                                                                <img src="{{ URL::asset('storage/' . $event->owner->icon) }}"
                                                                     alt="{{ $event->owner->name }}"
                                                                     class="img-fluid">
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

                                    {{-- <div class="col-md-12">
                                        
                                        <div class="form-submit">
                                            <button type="submit" class="btn btn-common" id="form-submit" style="pointer-events: all; cursor: pointer;"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar</button>
                                            <div id="msgSubmit" class="h3 text-center hidden"></div>
                                        </div>
                                    </div> --}}
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
                        {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&#10005;</span>
                        </button> --}}
                    </div>
                    {{-- <div class="modal-body">
                    ...
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" id="modal_close" class="btn btn-secondary"
                                data-dismiss="modal">Ok</button>
                        {{-- <button type="button" class="btn btn-primary">Ok</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('event_name')
        {{ $event->name }}
    @endpush

    @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyCkUOdZ5y7hMm0yrcCQoCvLwzdM6M8s5qk">
        </script>

        <script>
            // var geocoder;
            // var map;
            // var address = "San Diego, CA";

            // function initialize() {
            // geocoder = new google.maps.Geocoder();
            // var latlng = new google.maps.LatLng(-34.397, 150.644);
            // var myOptions = {
            //     zoom: 8,
            //     center: latlng,
            //     mapTypeControl: true,
            //     mapTypeControlOptions: {
            //     style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            //     },
            //     navigationControl: true,
            //     mapTypeId: google.maps.MapTypeId.ROADMAP
            // };
            // map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
            // if (geocoder) {
            //     geocoder.geocode({
            //     'address': address
            //     }, function(results, status) {
            //     if (status == google.maps.GeocoderStatus.OK) {
            //         if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
            //         map.setCenter(results[0].geometry.location);

            //         var infowindow = new google.maps.InfoWindow({
            //             content: '<b>' + address + '</b>',
            //             size: new google.maps.Size(150, 50)
            //         });

            //         var marker = new google.maps.Marker({
            //             position: results[0].geometry.location,
            //             map: map,
            //             title: address
            //         });
            //         google.maps.event.addListener(marker, 'click', function() {
            //             infowindow.open(map, marker);
            //         });

            //         } else {
            //         alert("No results found");
            //         }
            //     } else {
            //         alert("Geocode was not successful for the following reason: " + status);
            //     }
            //     });
            // }
            // }
            // google.maps.event.addDomListener(window, 'load', initialize);

            $(document).ready(function() {

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
                            location.reload();
                            // $('#subtotal').html(response.subtotal);
                        }
                    },
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
