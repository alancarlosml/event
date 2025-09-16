<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">Meus eventos</a></li>
            </ol>
            <h2>Editar lote: {{$lote->name}}</h2>
    
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
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
                        <form method="POST" action="{{route('event_home.lote_update', $lote->hash)}}">
                            @csrf
                            <input type="hidden" name="event_id" value="{{$lote->event_id}}">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="type">Tipo do lote*</label>
                                    <select id="type" class="form-control col-md-3" id="type" name="type">
                                    <option selected>Selecione</option>
                                    <option value="0" @if($lote->type == 0) selected @endif>Pago</option>
                                    <option value="1" @if($lote->type == 1) selected @endif>Grátis</option>
                                    </select>
                                    <input type="hidden" name="type_hidden" id="type_hidden" value="{{$lote->type}}">
                                </div>
                                <div class="row card-body mb-3 mb-2" style="border: solid 1px #ddd; border-radius: 0.25rem;" id="value_div">
                                    <div class="form-group col-md-3">
                                        <label for="tax_parcelamento">Juros do parcelamento*<a href="javascript:;" data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i class="fa-solid fa-circle-question"></i></a></label>
                                        <select id="tax_parcelamento" class="form-control" id="tax_parcelamento" name="tax_parcelamento">
                                            <option selected>Selecione</option>
                                            <option value="0" @if($lote->tax_parcelamento == 0) selected @endif>Pago pelo participante</option>
                                            <option value="1" @if($lote->tax_parcelamento == 1) selected @endif>Pago pelo organizador</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tax_service">Taxa de serviço ({{number_format($taxa_juros*100, 2, ',')}}%)*</label>
                                        <select id="tax_service" class="form-control" id="tax_service" name="tax_service">
                                            <option selected>Selecione</option>
                                            <option value="0" @if($lote->tax_service == 0) selected @endif>Pago pelo participante</option>
                                            <option value="1" @if($lote->tax_service == 1) selected @endif>Pago pelo organizador</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="value">Valor do ingresso*</label>
                                        <input type="text" class="form-control" id="value" name="value" placeholder="00,00" value="{{$lote->value ?? old('value')}}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="value">Forma de pagamento</label> <br/>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="form_pagamento[]" value="1" @if(strpos($lote->form_pagamento, '1') !== false) checked @endif>
                                            <label class="form-check-label" for="inlineCheckbox1">Cartão de crédito</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="form_pagamento[]" value="2" @if(strpos($lote->form_pagamento, '2') !== false) checked @endif>
                                            <label class="form-check-label" for="inlineCheckbox2">Boleto bancário <a href="javascript:;" data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i class="fa-solid fa-circle-question"></i></a></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox3" name="form_pagamento[]" value="3" @if(strpos($lote->form_pagamento, '3') !== false) checked @endif>
                                            <label class="form-check-label" for="inlineCheckbox3">PIX <a href="javascript:;" data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i class="fa-solid fa-circle-question"></i></a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 g-3">
                                    <div class="col-12 col-md-8">
                                        <label for="name" class="form-label">
                                            Nome do lote
                                            <span class="text-danger" aria-label="obrigatório">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="name"
                                            name="name"
                                            placeholder="Nome"
                                            value="{{ $lote->name ?? old('name') }}"
                                            required
                                            aria-describedby="name-help"
                                            minlength="2"
                                            maxlength="255"
                                        >
                                        <div id="name-help" class="form-text">
                                            Mínimo 2 caracteres, máximo 255
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="quantity" class="form-label">
                                            Quantidade
                                            <span class="text-danger" aria-label="obrigatório">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="quantity"
                                            name="quantity"
                                            placeholder="0"
                                            value="{{ $lote->quantity ?? old('quantity') }}"
                                            min="0"
                                            max="999999"
                                            required
                                            aria-describedby="quantity-help"
                                        >
                                        <div id="quantity-help" class="form-text">
                                            Número de ingressos disponíveis
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Descrição</label>
                                    <input type="text" class="form-control" id="description" name="description" placeholder="Descrição" value="{{$lote->description ?? old('description')}}">
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-12">Limite por compra*</label>
                                    <div class="form-group col-md-3">
                                    Mínimo
                                    <input type="number" class="form-control" id="limit_min" name="limit_min" placeholder="0" value="{{old('limit_min', $lote->limit_min)}}" min="0">
                                    </div>
                                    <div class="form-group col-md-3">
                                    Máximo
                                    <input type="number" class="form-control" id="limit_max" name="limit_max" placeholder="0" value="{{old('limit_max', $lote->limit_max)}}" min="0">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-12">Período de vendas*</label>
                                    <div class="form-group col-md-3">
                                        <label for="number">Início</label>
                                        <div class="input-group date" id="datetimepicker_day_begin" data-target-input="nearest">
                                            <input class="form-control datetimepicker-input datetimepicker_day" id="input_datetimepicker_day_begin" data-target="#datetimepicker_day_begin" name="datetime_begin" autocomplete="off" value="{{ \Carbon\Carbon::parse($lote->datetime_begin)->format('d/m/Y H:i') }}"/>
                                            <div class="input-group-append" data-target="#datetimepicker_day_begin" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="number">Fim</label>
                                        <div class="input-group date" id="datetimepicker_day_end" data-target-input="nearest">
                                            <input class="form-control datetimepicker-input datetimepicker_day" id="input_datetimepicker_day_end" data-target="#datetimepicker_day_end" name="datetime_end" autocomplete="off" value="{{ \Carbon\Carbon::parse($lote->datetime_end)->format('d/m/Y H:i') }}"/>
                                            <div class="input-group-append" data-target="#datetimepicker_day_end" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="visibility">Visibilidade*</label>
                                    <select id="visibility" class="form-control col-md-3" name="visibility">
                                    <option selected>Selecione</option>
                                    <option value="0" @if($lote->visibility == 0) selected @endif>Público</option>
                                    <option value="1" @if($lote->visibility == 1) selected @endif>Privado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('event_home.create.step.two') }}" class="btn btn-secondary">Voltar</a>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
            integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.css">
        {{-- <link href="{{ asset('assets_admin/jquery.datetimepicker.min.css') }}" rel="stylesheet"> --}}
      @endpush

      @push('footer')
      
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
            integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        {{-- <script src="{{ asset('assets_admin/jquery.datetimepicker.full.min.js') }}"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js">
        <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.js"></script> --}}
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.js"></script>

        </script>

        <script>
        $(document).ready(function() {

            $('#value').mask("#.##0,00", {
                reverse: true
            });
            
            $('[data-toggle="tooltip"]').tooltip({
                placement : 'right'
            });

            type_hidden_val = $('#type_hidden').val();

            $('#type option[value="' + type_hidden_val + '"]').prop("selected", true);
            if(type_hidden_val == 1){
                $('#value_div').hide();
            }else{
                $('#value_div').show();
            }

            $('#type').change(function(){
                id_type = $(this).val();
                if(id_type == 1){
                    $('#value_div').hide();
                }else{
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
            })
        });
    
    </script>
      
    @endpush

</x-site-layout>
