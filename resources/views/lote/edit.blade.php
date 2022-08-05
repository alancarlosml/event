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
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Layout</a></li>
                <li class="breadcrumb-item active">Fixed Layout</li>
                </ol>
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
                    <h3 class="card-title">Editar lote - {{$lote->name}}</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-body table-responsive p-0">
                    <form method="POST" action="{{route('lote.update', $lote->id)}}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{$lote->event_id}}">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="type">Tipo do lote*</label>
                                <select id="type" class="form-control col-md-3" id="type" name="type">
                                  <option selected>Selecione</option>
                                  <option value="0" @if($lote->type == 0) selected @endif>Pago</option>
                                  <option value="1" @if($lote->type == 1) selected @endif>Grátis</option>
                                </select>
                                <input type="hidden" name="type_hidden" id="type_hidden" value="{{$lote->type}}">
                            </div>
                            <div class="form-row card-body mb-2" style="border: solid 1px #ddd; border-radius: 0.25rem;" id="value_div">
                                <div class="form-group col-md-3">
                                    <label for="tax_parcelamento">Juros do parcelamento*<a href="javascript:;" data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i class="fa-solid fa-circle-question"></i></a></label>
                                    <select id="tax_parcelamento" class="form-control" id="tax_parcelamento" name="tax_parcelamento">
                                        <option selected>Selecione</option>
                                        <option value="0" @if($lote->tax_parcelamento == 0) selected @endif>Pago pelo participante</option>
                                        <option value="1" @if($lote->tax_parcelamento == 1) selected @endif>Pago pelo organizador</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="tax_service">Taxa de serviço (7,0%)*</label>
                                    <select id="tax_service" class="form-control" id="tax_service" name="tax_service">
                                        <option selected>Selecione</option>
                                        <option value="0" @if($lote->tax_service == 0) selected @endif>Pago pelo participante</option>
                                        <option value="1" @if($lote->tax_service == 1) selected @endif>Pago pelo organizador</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="value">Valor do ingresso*</label>
                                    <input type="text" class="form-control" id="value" name="value" placeholder="50,00" value="{{$lote->value}}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="value">Forma de pagamento</label> <br/>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="form_pagamento[]" value="0">
                                        <label class="form-check-label" for="inlineCheckbox1">Cartão de crédito</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="form_pagamento[]" value="1">
                                        <label class="form-check-label" for="inlineCheckbox2">Boleto bancário <a href="javascript:;" data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i class="fa-solid fa-circle-question"></i></a></label>
                                      </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="name">Nome do lote*</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nome" value="{{$lote->name}}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="quantity">Quantidade*</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="0" value="{{$lote->quantity}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="Descrição" value="{{$lote->description}}">
                            </div>
                            <div class="form-row">
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
                            <div class="form-row">
                                <label class="col-md-12">Período de vendas*</label>
                                <div class="form-group col-md-3">
                                    Início
                                    <div class="input-group date" id="reservationtime_begin" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="reservationtime_begin_input" data-target="#reservationtime_begin" name="datetime_begin"/>
                                        <div class="input-group-append" data-target="#reservationtime_begin" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="reservationtime_begin_hidden" id="reservationtime_begin_hidden" value="{{$lote->datetime_begin}}">
                                </div>
                                <div class="form-group col-md-3">
                                    Fim
                                    <div class="input-group date" id="reservationtime_end" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="reservationtime_end_input" data-target="#reservationtime_end" name="datetime_end"/>
                                        <div class="input-group-append" data-target="#reservationtime_end" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="reservationtime_end_hidden" id="reservationtime_end_hidden" value="{{$lote->datetime_end}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="visibility">Visibilidade*</label>
                                <select id="visibility" class="form-control col-md-3" name="visibility">
                                  <option selected>Selecione</option>
                                  <option value="0" @if($lote->visibility == 0) selected @endif>Público</option>
                                  <option value="1" @if($lote->visibility == 1) selected @endif>Privado</option>
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
        
                        <div class="card-footer">
                          <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    Footer
                </div>
                <!-- /.card-footer-->
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(function () {

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

                // $('#reservationtime_input').daterangepicker({
                //     timePicker: true,
                //     timePickerIncrement: 30,
                //     locale: {
                //         format: 'MM/DD/YYYY hh:mm A'
                //     }
                // });
     
                $('#reservationtime_begin').datetimepicker({ 
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY hh:mm A',
                    defaultDate: $('#reservationtime_begin_hidden').val()
                });

                $('#reservationtime_end').datetimepicker({ 
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY hh:mm A',
                    defaultDate: $('#reservationtime_end_hidden').val()
                });

                $("#reservationtime_begin").on("change.datetimepicker", function (e) {
                    $('#reservationtime_end').datetimepicker('minDate', e.date);
                });
                $("#reservationtime_end").on("change.datetimepicker", function (e) {
                    $('#reservationtime_begin').datetimepicker('maxDate', e.date);
                });

            });
            
        </script>
    @endpush
</x-app-layout>
