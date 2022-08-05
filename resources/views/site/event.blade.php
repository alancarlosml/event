<x-event-layout>
    <div class="hero-image">
        <img src="{{ URL::asset('storage/'.$event->banner) }}" alt="{{ $event->name}}" class="img-fluid">
    </div>
    <section id="information-bar">
        <div class="container">
            <div class="row inforation-wrapper">
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul>
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                        </li>
                        <li><span><b>Local</b> {{ $event->place->name}}, {{ $event->place->get_city()->name}}-{{ $event->place->get_city()->uf}}</span></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul>
                        <li>
                            <i class="fa-solid fa-calendar-check"></i>
                        </li>
                        <li><span><b>Data &amp; Hora</b> @if($event->min_event_dates() != $event->max_event_dates()) De {{\Carbon\Carbon::parse($event->min_event_dates())->format('d/m')}} a {{\Carbon\Carbon::parse($event->max_event_dates())->format('d/m')}}@else{{\Carbon\Carbon::parse($event->min_event_dates())->format('d/m')}}@endif, {{\Carbon\Carbon::parse($event->min_time)->format('h:i')}}h</span></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul>
                        <li>
                            <i class="fa-solid fa-list-check"></i>
                        </li>
                        <li><span><b>Categoria</b> {{ $event->category->description}}</span></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 col-md-6 col-xs-12">
                    <ul>
                        <li>
                            <i class="fa-solid fa-id-badge"></i>
                        </li>
                        <li><span><b>Vagas</b> {{ $event->max_tickets}}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section id="about" class="section-padding mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="about-content">
                        <div>
                            <div class="about-text">
                                <h2>Sobre o evento</h2>
                            </div>
                            {!!$event->description!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="intro" class="intro section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-header text-center">
                        <h2 class="section-title wow fadeInUp animated" data-wow-delay="0.2s" style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">Inscrições</h2>
                    </div>
                </div>
            </div>
            <div class="row intro-wrapper">
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <thead>
                                    <th>Lote</th>
                                    <th>Valor</th>
                                    <th>Quantidade</th>
                                </thead>
                                @foreach ($event->lotes as $lote)
                                <tr class="border-bottom">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ps-3 d-flex flex-column">
                                                <p class="fw-bold text-uppercase"> <b>{{$lote->name}} </b></p>
                                                @if($lote->description)<p class="fw-bold"> {{$lote->description}} </p>@endif
                                                <small class="font-weight-bold"> Disponível até {{\Carbon\Carbon::parse($lote->datetime_end)->format('d/m/y \à\s h:i')}} </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <p class="pe-3">
                                                <span class="red">@money($lote->value)</span><br/>
                                                @if ($lote->type == 0)
                                                    <small>+ taxa de @money($lote->value * 0.1)</small> 
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center"> 
                                            <span class="pe-3 ml-2"> 
                                                <input class="ps-2" type="number" value="{{$lote->limit_min}}" min="{{$lote->limit_min}}" max="{{$lote->limit_max}}">
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4 payment-summary">
                    <div class="card px-md-3 px-2 pt-4">
                        <h6>Resumo</h6>
                        <div class="d-flex justify-content-between b-bottom"> 
                            <input type="text" class="ps-2" placeholder="CUPOM">
                            <div class="btn btn-common">Aplicar</div>
                        </div>
                        <div class="d-flex flex-column b-bottom">
                            <div class="d-flex justify-content-between py-3"> <small class="text-muted">Order Summary</small>
                                <p>$122</p>
                            </div>
                            <div class="d-flex justify-content-between pb-3"> <small class="text-muted">Additional Service</small>
                                <p>$22</p>
                            </div>
                            <div class="d-flex justify-content-between"> <small class="text-muted">Total Amount</small>
                                <p>$132</p>
                            </div>
                        </div>
                        <div class="sale my-3"> <span>sale<span class="px-1">expiring</span><span>in</span>:</span><span class="red">21<span class="ps-1">hours</span>,31<span class="ps-1 ">minutes</span></span> </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="google-map-area">
        <div class="row">
            <div class="col-12">
                <div class="section-title-header text-center">
                    <h2 class="section-title wow fadeInUp animated" data-wow-delay="0.2s" style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">Local</h2>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.841148750037!2d-44.311417949527524!3d-2.5584934981266056!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7f6860324886045%3A0x1024f6caa9406aaa!2sUniversidade%20Federal%20do%20Maranh%C3%A3o!5e0!3m2!1spt-BR!2sbr!4v1659628612287!5m2!1spt-BR!2sbr" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>
    <section id="contact-text">
        <div class="container">
            <div class="row contact-wrapper">
                <p><b>{{$event->place->name}}.</b> {{$event->place->address}}, {{$event->place->number}}. Bairro: {{$event->place->district}}. CEP: {{$event->place->zip}}. {{$event->place->get_city()->name}}-{{$event->place->get_city()->uf}} </p>
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
    <section id="contact-map" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-header text-center">
                        <h2 class="section-title wow fadeInUp animated" data-wow-delay="0.2s" style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">Organizador</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="schedule-area row wow fadeInDown animated" data-wow-delay="0.3s" style="visibility: visible;-webkit-animation-delay: 0.3s; -moz-animation-delay: 0.3s; animation-delay: 0.3s;">
                        <div class="schedule-tab-content col-12 clearfix">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="monday" role="tabpanel" aria-labelledby="monday-tab">
                                    <div id="accordion">
                                        <div class="card">
                                            <div id="headingOne">
                                                <div class="collapsed card-header">
                                                    <div class="row">
                                                        <div class="col-sm-2 col-md-2">
                                                            <img src="{{ URL::asset('storage/'.$event->owner->icon) }}" alt="{{ $event->owner->name}}" class="img-fluid">
                                                        </div>
                                                        <div class="col-sm-10 col-md-10">
                                                            <h6>{{$event->owner->name}}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="owner-info">
                                                <div class="card-body">
                                                    <p>{{$event->owner->description}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="wow fadeInDown animated duvida-contato text-center" data-wow-delay="0.2s" style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;"><b>Em caso de alguma dúvida, por favor, entre em contato com o organizador do evento.</b></p>
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="container-form wow fadeInLeft animated" data-wow-delay="0.2s" style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">
                        <div class="form-wrapper">
                            <form role="form" method="post" id="contactForm" name="contact-form" data-toggle="validator" novalidate="true">
                                <div class="row">
                                    <div class="col-md-6 form-line">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name" name="email" placeholder="First Name" required="" data-error="Please enter your name">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-line">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required="" data-error="Please enter your Email">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-line">
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="msg_subject" name="subject" placeholder="Subject" required="" data-error="Please enter your message subject">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="4" id="message" name="message" required="" data-error="Write your message"></textarea>
                                        </div>
                                        <div class="form-submit">
                                            <button type="submit" class="btn btn-common disabled" id="form-submit" style="pointer-events: all; cursor: pointer;"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Message</button>
                                            <div id="msgSubmit" class="h3 text-center hidden"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('head')

    <style>
        #event-list img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        #event-list .single-blog-item {
            border: 1px solid #dfdede;
            box-shadow: 2px 5px 10px #dfdede;
            margin: 15px auto;
            padding: 5px;
            position: relative;
            border-radius: 10px;
        }

        #event-list .blog-content {
            padding: 15px;
        }

        #event-list .blog-content h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        #event-list .blog-content h6 a{
            color:#013289;
            font-weight: 600;
            font-size: 16px;
        }

        #event-list .blog-content h4 a{
            color:#4154f1;
            font-size: 20px;
        }
        
        #event-list .blog-content h4#place_name a{
            color:#333;
            font-size: 16px;
        }
        
        #event-list .blog-date {
            position: absolute;
            background: #4154f1;
            top: 35px;
            /* left: 5px; */
            color: #fff;
            border-radius: 0 25px 25px 0;
            padding: 5px 15px;
            font-weight: 500;
        }

    </style>
    @endpush

</x-event-layout>