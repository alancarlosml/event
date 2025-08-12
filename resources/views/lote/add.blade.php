<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Lotes</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Incluir novo</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
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
                            <div class="card-body table-responsive p-0">
                                <form method="POST" action="{{ route('lote.store', $id) }}">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $id }}">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="type">Tipo do lote*</label>
                                            <select id="type" class="form-control col-md-3" id="type"
                                                    name="type">
                                                <option selected>Selecione</option>
                                                <option value="0">Pago</option>
                                                <option value="1">Grátis</option>
                                            </select>
                                        </div>
                                        <div class="form-row card-body mb-2"
                                             style="border: solid 1px #ddd; border-radius: 0.25rem;" id="value_div">
                                            <div class="form-group col-md-3">
                                                <label for="tax_parcelamento">Juros do parcelamento* <a
                                                       href="javascript:;" data-toggle="tooltip" data-placement="right"
                                                       title="Tooltip on right"><i
                                                           class="fa-solid fa-circle-question"></i></a></label>
                                                <select id="tax_parcelamento" class="form-control" id="tax_parcelamento"
                                                        name="tax_parcelamento">
                                                    <option selected>Selecione</option>
                                                    <option value="0">Pago pelo participante</option>
                                                    <option value="1">Pago pelo organizador</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="tax_service">Taxa de serviço
                                                    ({{ number_format($taxa_juros * 100, 2, ',') }}%)*</label>
                                                <select id="tax_service" class="form-control" id="tax_service"
                                                        name="tax_service">
                                                    <option selected>Selecione</option>
                                                    <option value="0">Pago pelo participante</option>
                                                    <option value="1">Pago pelo organizador</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="value">Valor do ingresso*</label>
                                                <input type="text" class="form-control" id="value" name="value"
                                                       placeholder="50,00">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="value">Forma de pagamento*</label> <br />
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                                           name="form_pagamento[]" value="1">
                                                    <label class="form-check-label" for="inlineCheckbox1">Cartão de
                                                        crédito</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                                           name="form_pagamento[]" value="2">
                                                    <label class="form-check-label" for="inlineCheckbox2">Boleto
                                                        bancário <a href="javascript:;" data-toggle="tooltip"
                                                           data-placement="right" title="Tooltip on right"><i
                                                               class="fa-solid fa-circle-question"></i></a></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                                           name="form_pagamento[]" value="3">
                                                    <label class="form-check-label" for="inlineCheckbox3">PIX <a
                                                           href="javascript:;" data-toggle="tooltip" data-placement="right"
                                                           title="Tooltip on right"><i
                                                               class="fa-solid fa-circle-question"></i></a></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-10">
                                                <label for="name">Nome do lote*</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Nome">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="quantity">Quantidade*</label>
                                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="0" min="0">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Descrição</label>
                                            <input type="text" class="form-control" id="description" name="description" placeholder="Descrição">
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-12">Limite por compra*</label>
                                            <div class="form-group col-md-3">
                                                Mínimo
                                                <input type="number" class="form-control" id="limit_min"
                                                       name="limit_min" placeholder="0"
                                                       value="{{ old('limit_min') }}" min="0">
                                            </div>
                                            <div class="form-group col-md-3">
                                                Máximo
                                                <input type="number" class="form-control" id="limit_max"
                                                       name="limit_max" placeholder="0"
                                                       value="{{ old('limit_max') }}" min="0">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-12">Período de vendas*</label>
                                            <div class="form-group col-md-3">
                                                Início
                                                <div class="input-group date" id="reservationtime_begin"
                                                     data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input"
                                                           id="reservationtime_begin_input"
                                                           data-target="#reservationtime_begin"
                                                           name="datetime_begin" />
                                                    <div class="input-group-append"
                                                         data-target="#reservationtime_begin"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                Fim
                                                <div class="input-group date" id="reservationtime_end"
                                                     data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input"
                                                           id="reservationtime_end_input"
                                                           data-target="#reservationtime_end" name="datetime_end" />
                                                    <div class="input-group-append" data-target="#reservationtime_end"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="visibility">Visibilidade*</label>
                                            <select id="visibility" class="form-control col-md-3" name="visibility">
                                                <option selected>Selecione</option>
                                                <option value="0">Público</option>
                                                <option value="1">Privado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('event.lotes', $id)}}" class="btn btn-success float-left">Voltar</a>
                                        <button type="submit" class="btn btn-primary float-right">Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
              integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
              integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css"
              integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
                integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"
                integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js"
                integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(function() {

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

                // $('#reservationtime_input').daterangepicker({
                //     timePicker: true,
                //     timePickerIncrement: 30,
                //     locale: {
                //         format: 'MM/DD/YYYY hh:mm A'
                //     }
                // });

                $('#reservationtime_begin').datetimepicker({
                    icons: {
                        time: 'far fa-clock'
                    },
                    format: 'DD/MM/YYYY hh:mm A'
                });

                $('#reservationtime_end').datetimepicker({
                    icons: {
                        time: 'far fa-clock'
                    },
                    format: 'DD/MM/YYYY hh:mm A'
                });

                $("#reservationtime_begin").on("change.datetimepicker", function(e) {
                    $('#reservationtime_end').datetimepicker('minDate', e.date);
                });
                $("#reservationtime_end").on("change.datetimepicker", function(e) {
                    $('#reservationtime_begin').datetimepicker('maxDate', e.date);
                });

            });
        </script>
    @endpush
</x-app-layout>
