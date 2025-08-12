<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="index.html">Home</a></li>
                    <li>Eventos</li>
                </ol>
                <h2>Criar novo lote</h2>

            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="form-group pl-3 pr-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erros encontrados:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body table-responsive p-0">
                    <ul id="progressbar">
                        <li class="active" id="account"><strong>Informações</strong></li>
                        <li class="active" id="personal"><strong>Inscrições</strong></li>
                        <li id="payment"><strong>Cupons</strong></li>
                        <li id="confirm"><strong>Publicar</strong></li>
                    </ul>
                    <div class="card-body">
                        <form method="POST" action="{{ route('event_home.create_lote_store') }}">
                            @csrf
                            <input type="hidden" name="event_id" value="">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="type">Tipo do lote*</label>
                                    <select id="type" class="form-control col-md-3" id="type" name="type">
                                        <option value="0">Pago</option>
                                        <option value="1">Grátis</option>
                                    </select>
                                </div>
                                <div class="form-row card-body mb-2"
                                     style="border: solid 1px #ddd; border-radius: 0.25rem;" id="value_div">
                                    <div class="form-group col-md-3">
                                        <label for="tax_parcelamento">Juros do parcelamento* <a href="javascript:;"
                                               data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i
                                                   class="fa-solid fa-circle-question"></i></a></label>
                                        <select id="tax_parcelamento" class="form-control" id="tax_parcelamento"
                                                name="tax_parcelamento">
                                            <option selected value="">Selecione</option>
                                            <option value="0">Pago pelo participante</option>
                                            <option value="1">Pago pelo organizador</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tax_service">Taxa de serviço
                                            ({{ number_format($taxa_juros * 100, 2, ',') }}%)*</label>
                                        <select id="tax_service" class="form-control" id="tax_service"
                                                name="tax_service">
                                            <option selected value="">Selecione</option>
                                            <option value="0">Pago pelo participante</option>
                                            <option value="1">Pago pelo organizador</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="value">Valor do ingresso*</label>
                                        <input type="text" class="form-control" id="value" name="value" placeholder="00,00" value="{{ $lote->value ?? old('value') }}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="value">Forma de pagamento*</label> <br />
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="form_pagamento[]" value="1">
                                            <label class="form-check-label" for="inlineCheckbox1">Cartão de crédito</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="form_pagamento[]" value="2">
                                            <label class="form-check-label" for="inlineCheckbox2">Boleto bancário 
                                                <a href="javascript:;" data-toggle="tooltip" data-placement="right" title="Tooltip on right">
                                                    <i class="fa-solid fa-circle-question"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                                   name="form_pagamento[]" value="3">
                                            <label class="form-check-label" for="inlineCheckbox3">PIX 
                                                <a href="javascript:;" data-toggle="tooltip" data-placement="right" title="Tooltip on right">
                                                    <i class="fa-solid fa-circle-question"></i>
                                                </a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-10">
                                        <label for="name">Nome do lote*</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nome" value="{{ $lote->name ?? old('name') }}">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="quantity">Quantidade*</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="0" min="0" value="{{ $lote->quantity ?? old('quantity') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descrição</label>
                                    <input type="text" class="form-control" id="description" name="description" placeholder="Descrição" value="{{ $lote->description ?? old('description') }}">
                                </div>
                                <div class="form-row">
                                    <label class="col-md-12">Limite por compra*</label>
                                    <div class="form-group col-md-3">
                                        Mínimo
                                        <input type="number" class="form-control" id="limit_min" name="limit_min" placeholder="0" value="{{ $lote->limit_min ?? old('limit_min') }}" min="0">
                                    </div>
                                    <div class="form-group col-md-3">
                                        Máximo
                                        <input type="number" class="form-control" id="limit_max" name="limit_max" placeholder="0" value="{{ $lote->limit_max ?? old('limit_max') }}" min="0">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="col-md-12">Período de vendas*</label>
                                    <div class="form-group col-md-3">
                                        <label for="number">Início</label>
                                        <div class="input-group date" id="datetimepicker_day_begin"
                                             data-target-input="nearest">
                                            <input class="form-control datetimepicker-input datetimepicker_day"
                                                   id="input_datetimepicker_day_begin"
                                                   data-target="#datetimepicker_day_begin" name="datetime_begin"
                                                   autocomplete="off" />
                                            {{-- <div class="input-group-append" data-target="#datetimepicker_day_begin"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="number">Fim</label>
                                        <div class="input-group date" id="datetimepicker_day_end"
                                             data-target-input="nearest">
                                            <input class="form-control datetimepicker-input datetimepicker_day"
                                                   id="input_datetimepicker_day_end"
                                                   data-target="#datetimepicker_day_end" name="datetime_end"
                                                   autocomplete="off" />
                                            {{-- <div class="input-group-append" data-target="#datetimepicker_day_end"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div> --}}
                                        </div>
                                    </div>
                                    {{-- <div class="form-group col-md-3">
                                        Início
                                        <div class="input-group date" id="reservationtime_begin" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" id="reservationtime_begin_input" data-target="#reservationtime_begin" name="datetime_begin"/>
                                            <div class="input-group-append" data-target="#reservationtime_begin" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        Fim
                                        <div class="input-group date" id="reservationtime_end" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" id="reservationtime_end_input" data-target="#reservationtime_end" name="datetime_end"/>
                                            <div class="input-group-append" data-target="#reservationtime_end" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="form-group">
                                    <label for="visibility">Visibilidade*</label>
                                    <select id="visibility" class="form-control col-md-3" name="visibility">
                                        <option selected value="">Selecione</option>
                                        <option value="0">Público</option>
                                        <option value="1">Privado</option>
                                    </select>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            {{-- <div class="form-check pb-3">
                                <label for="visibility">Visibilidade</label>
                                <div class="custom-switch">
                                    <input type="checkbox" checked="checked" class="custom-control-input" name="visibility" id="visibility" value="1">
                                    <label class="custom-control-label" for="visibility">Público</label>
                                </div>
                            </div> --}}

                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('event_home.create.step.two') }}" class="btn btn-primary">Voltar</a>
                                <button type="submit" class="btn btn-primary">Criar lote</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
              integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        {{-- <link href="../../../assets_admin/jquery.datetimepicker.min.css " rel="stylesheet"> --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.css">
    @endpush

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
                integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        {{-- <script src="../../../assets_admin/jquery.datetimepicker.full.min.js"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.js"></script>

        <script>
            $(document).ready(function() {

                $('#value').mask("#.##0,00", {
                    reverse: true
                });

                $('[data-toggle="tooltip"]').tooltip({
                    placement: 'right'
                });

                $('#type').change(function() {
                    id_type = $(this).val();
                    if (id_type == 1) {
                        $('#value_div').hide();
                    } else {
                        $('#value_div').show();
                    }
                });

                const localePt_Br = {
                    days: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                    daysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    daysMin: ['Do', 'Se', 'Te', 'Qu', 'Qu', 'Se', 'Sa'],
                    months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                    monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    today: 'Hoje',
                    clear: 'Cancelar',
                    onlyTimepicker: true,
                    dateFormat: 'dd/MM/yyyy',
                    timeFormat: 'HH:mm',
                    firstDay: 1
                }

                dpMin = new AirDatepicker('#input_datetimepicker_day_begin', {
                    timepicker: true,
                    minDate: new Date(),
                    locale: localePt_Br,
                    onSelect({date}) {
                        dpMax.update({
                            minDate: date
                        })
                    }
                    //onlyTimepicker: true
                })

                dpMax = new AirDatepicker('#input_datetimepicker_day_end', {
                    timepicker: true,
                    minDate: new Date(),
                    locale: localePt_Br,
                    onSelect({date}) {
                        dpMin.update({
                            maxDate: date
                        })
                    }
                    //onlyTimepicker: true
                })

                // $('#reservationtime_input').daterangepicker({
                //     timePicker: true,
                //     timePickerIncrement: 30,
                //     locale: {
                //         format: 'MM/DD/YYYY hh:mm A'
                //     }
                // });

                // $('body').on('mousedown', "#input_datetimepicker_day_begin", function() {
                //     $(this).datetimepicker({
                //         timepicker: true,
                //         format: 'd/m/Y H:i',
                //         mask: true,
                //         minDate: new Date(),
                //         /*onShow: function(ct) {
                //             this.setOptions({
                //                 maxDate: $('#input_datetimepicker_day_end').val() ? $(
                //                     '#input_datetimepicker_day_end').val() : false
                //             })
                //         },*/
                //     });
                // });

                // $('body').on('mousedown', "#input_datetimepicker_day_end", function() {
                //     $(this).datetimepicker({
                //         timepicker: true,
                //         format: 'd/m/Y H:i',
                //         mask: true,
                //         minDate:new Date(),
                //     });
                // });

                // $('#reservationtime_begin').datetimepicker({ 
                //     icons: { time: 'far fa-clock' },
                //     format: 'DD/MM/YYYY hh:mm A' 
                // });

                // $('#reservationtime_end').datetimepicker({ 
                //     icons: { time: 'far fa-clock' },
                //     format: 'DD/MM/YYYY hh:mm A' 
                // });

                // $("#reservationtime_begin").on("change.datetimepicker", function (e) {
                //     $('#reservationtime_end').datetimepicker('minDate', e.date);
                // });
                // $("#reservationtime_end").on("change.datetimepicker", function (e) {
                //     $('#reservationtime_begin').datetimepicker('maxDate', e.date);
                // });
            });
        </script>
    @endpush

</x-site-layout>
