<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Editar cupom: {{$coupon->code}}</h2>
    
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
                        <li class="active" id="payment"><strong>Cupons</strong></li>
                        <li id="confirm"><strong>Publicar</strong></li>
                    </ul>
                    <div class="card-body">
                        <form method="POST" action="{{route('event_home.update_coupon', $hash)}}">
                            @csrf
                            <input type="hidden" name="event_id" value="{{$event_id}}">
                            <div class="card-body">
                                <div class="form-row mb-3">
                                    <label for="code" class="ml-2">Código do cupom*</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control col-2" placeholder="Código" aria-label="Código" aria-describedby="basic-addon2" name="code" value="{{$coupon->code ?? old('code')}}" style="margin-left: 5px">
                                        {{-- <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button">Gerar</button>  
                                        </div> --}}
                                    </div>
                                    <small id="codeHelp" class="form-text text-muted ml-2">Sugestão de código único.</small>
                                </div>
                                <div class="form-row mb-3" style="margin-left: 0;">
                                    <label for="discount_type">Valor*</label>
                                    <div class="input-group">
                                        <select class="form-control col-1" id="discount_type" name="discount_type">
                                            <option value="0" @if($coupon->discount_type == 0) selected @endif>%</option>
                                            <option value="1" @if($coupon->discount_type == 1) selected @endif>Fixo</option>
                                        </select>
                                        <input type="text" class="form-control col-2 ml-2" id="discount_value" name="discount_value" placeholder="0" value="{{$coupon->discount_value * 100 ?? old('discount_value')}}">
                                    </div>
                                    <small id="taxHelp" class="form-text text-muted">
                                        Em caso de porcentagem (%), use por exemplo o valor 7 para 7%.
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="limit_buy">Limite de compras*</label>
                                    <input type="number" class="form-control col-1" id="limit_buy" name="limit_buy" placeholder="0" value="{{$coupon->limit_buy ?? old('limit_buy')}}" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="limit_tickets">Limite de inscrições*</label>
                                    <input type="number" class="form-control col-1" id="limit_tickets" name="limit_tickets" placeholder="0" value="{{$coupon->limit_tickets ?? old('limit_tickets')}}" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="lotes">Marque os lotes que terão este cupom de desconto</label>                      
                                    <ul class="list-group">
                                        @php
                                            $a = array();
                                        @endphp
                                        @foreach($coupon->lotes as $lote_coupons)
                                            {{-- {{$lote_coupons->id}} --}}
                                            @php array_push($a, $lote_coupons->id) @endphp
                                        @endforeach  

                                        @foreach($lotes as $lote)
                                            <li class="list-group-item">
                                                <label style="margin-bottom: 0; font-weight:normal">
                                                    &nbsp;&nbsp;&nbsp;<input class="form-check-input me-1" @if(in_array($lote->id, $a)) checked @endif type="checkbox" value="{{$lote->id}}" aria-label="{{$lote->name}}" name="lotes[]">
                                                    {{$lote->name}}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="form-check pb-3">
                                <div class="custom-switch">
                                    <input type="checkbox" @if($coupon->status == 1) checked="checked" @else value="1" @endif class="custom-control-input" name="status" id="status">
                                    <label class="custom-control-label" for="status">Ativar</label>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('event_home.create.step.three') }}" class="btn btn-primary">Voltar</a>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </form> 
                    </div>
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
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
        $(document).ready(function() {

            $('#discount_value').mask("#.##0,00", {
                reverse: true
            });

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