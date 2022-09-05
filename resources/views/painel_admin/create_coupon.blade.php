<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Criar um novo cupom</h2>
    
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
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-body table-responsive p-0">
                    <form method="POST" action="{{route('event_home.store_coupon', $hash)}}">
                        @csrf
                        <input type="hidden" name="hash_event" value="{{$hash}}">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="code">Código do cupom*</label>
                                <div class="form-group col-3">
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
                                <input type="number" class="form-control col-1" id="limit_buy" name="limit_buy" placeholder="0" min="0">
                            </div>
                            <div class="form-group">
                                <label for="limit_tickets">Limite de inscrições*</label>
                                <input type="number" class="form-control col-1" id="limit_tickets" name="limit_tickets" placeholder="0" min="0">
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
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
      @endpush

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
        $(document).ready(function() {

            $('[data-toggle="tooltip"]').tooltip({
                placement : 'right'
            });

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
                format: 'DD/MM/YYYY hh:mm A' 
            });

            $('#reservationtime_end').datetimepicker({ 
                icons: { time: 'far fa-clock' },
                format: 'DD/MM/YYYY hh:mm A' 
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

</x-site-layout>