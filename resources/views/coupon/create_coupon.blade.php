<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Cupons</h1>
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
                    <h3 class="card-title">Incluir novo</h3>

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
                    <form method="POST" action="{{route('event.store_coupon', $id)}}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{$id}}">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="code">Código do cupom*</label>
                                <div class="form-group col-2">
                                    <input type="text" class="form-control" placeholder="Código" aria-label="Código" aria-describedby="basic-addon2" name="code" style="margin-left: -8px" value="{{$coupon_code}}">
                                    {{-- <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">Gerar</button>
                                    </div> --}}
                                    <small id="codeHelp" class="form-text text-muted">Sugestão de código único.</small>
                                </div>
                            </div>
                            <div class="form-row mb-3" style="margin-left: 0;">
                                <label for="discount_type">Valor*</label>
                                <div class="input-group">
                                    <select class="form-control col-1" id="discount_type" name="discount_type">
                                        <option value="0">%</option>
                                        <option value="1">Fixo</option>
                                    </select>
                                    <input type="text" class="form-control col-2 ml-2" id="discount_value" name="discount_value" placeholder="0">
                                </div>
                                <small id="taxHelp" class="form-text text-muted">Em caso de porcentagem (%), use o valor 0.07 para 7%, por exemplo.</small>
                            </div>
                            <div class="form-group">
                                <label for="limit_buy">Limite de compras*</label>
                                <input type="number" class="form-control col-1" id="limit_buy" name="limit_buy" placeholder="0">
                            </div>
                            <div class="form-group">
                                <label for="limit_tickets">Limite de inscrições*</label>
                                <input type="number" class="form-control col-1" id="limit_tickets" name="limit_tickets" placeholder="0">
                            </div>
                            <div class="form-group">
                                <label for="lotes">Marque os lotes que terão este cupom de desconto</label>                      
                                <ul class="list-group">
                                    @foreach($lotes as $lote)
                                    <li class="list-group-item">
                                        <label style="margin-bottom: 0; font-weight:normal">
                                            &nbsp;&nbsp;&nbsp;<input class="form-check-input me-1" type="checkbox" name="lotes[]" value="{{$lote->id}}" aria-label="{{$lote->name}}">
                                            {{$lote->name}}
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="form-check pb-3">
                            <div class="custom-switch">
                                <input type="checkbox" checked="checked" class="custom-control-input" name="status" id="status" value="1">
                                <label class="custom-control-label" for="status">Ativar</label>
                            </div>
                        </div>
                        <!-- /.card-body -->
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
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(function () {

                $('#name').keyup(function(e) {
                    $.get('{{ route('event.check_slug') }}', 
                    { 'title': $(this).val() }, 
                    function( data ) {
                        $('#slug').val(data.slug);
                    }
                    );
                });

                // Summernote
                $('#description').summernote({
                    placeholder: 'Descreva em detalhes o evento',
                    tabsize: 2,
                    height: 200
                });

                // $('#datetimepicker_day_input').inputmask('dd/mm/yyyy');

                $('#datetimepicker_day').datetimepicker({
                    format: 'DD/MM/YYYY'
                });

                $('#datetimepicker_hour_begin').datetimepicker({
                    format: 'LT'
                });

                $('#datetimepicker_hour_end').datetimepicker({
                    format: 'LT'
                });
                
            });
            
        </script>
    @endpush
</x-app-layout>
