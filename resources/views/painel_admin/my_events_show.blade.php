<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">Meus eventos</a></li>
            </ol>
            <h2>Detalhes do evento: {{$event->name}}</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="mb-3 px-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                            <strong>Erros encontrados:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                </div>
                
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0">Detalhes do evento</h4>
                        <a href="#" class="btn btn-outline-secondary btn-sm" onclick="window.print();" data-bs-toggle="tooltip" title="Imprimir">
                            <i class="fas fa-print me-2"></i>Imprimir
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="id" class="form-label fw-bold text-muted small">ID</label>
                                <p class="mb-0 fs-6">{{$event->id}}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-bold text-muted small">Status</label>
                                <p class="mb-0">
                                    @if($event->status == 1) 
                                        <span class="badge bg-success">Ativo</span>
                                    @else 
                                        <span class="badge bg-secondary">Inativo</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-bold text-muted small">Nome</label>
                                <p class="mb-0 fs-6">{{$event->name}}</p>
                            </div>
                            <div class="col-md-12">
                                <label for="subtitle" class="form-label fw-bold text-muted small">Subtítulo</label>
                                <p class="mb-0 fs-6">{{$event->subtitle ?? 'Não informado'}}</p>
                            </div>
                            <div class="col-md-12">
                                <label for="slug" class="form-label fw-bold text-muted small">URL do evento</label>
                                <p class="mb-0 fs-6">
                                    <a href="{{env('APP_URL') . '/' . $event->slug}}" target="_blank" class="text-decoration-none">
                                        {{env('APP_URL') . '/' . $event->slug}}
                                        <i class="fa-solid fa-up-right-from-square ms-2"></i>
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="form-label fw-bold text-muted small">Descrição</label>
                                <div class="text-muted">
                                    {!!$event->description!!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="banner" class="form-label fw-bold text-muted small">Banner</label>
                                <div>
                                    @if (isset($event->banner) && $event->banner)
                                        <img src="{{ asset('storage/'.$event->banner) }}" alt="Banner evento" class="img-fluid rounded" style="max-width: 400px; max-height: 200px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">Sem banner cadastrado</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="max_tickets" class="form-label fw-bold text-muted small">N° máximo de ingressos</label>
                                <p class="mb-0 fs-6">{{$event->max_tickets}}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="place" class="form-label fw-bold text-muted small">Local do evento</label>
                                <p class="mb-0 fs-6">{{$event->place->name ?? 'Não informado'}}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="responsible" class="form-label fw-bold text-muted small">Responsável</label>
                                <p class="mb-0 fs-6">
                                    @php
                                        $admin = $event->get_participante_admin();
                                    @endphp
                                    @if($admin)
                                        {{$admin->name}}<br>
                                        <small class="text-muted">{{$admin->email}}</small>
                                    @else
                                        <span class="text-muted">Não informado</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label for="created_at" class="form-label fw-bold text-muted small">Data de criação</label>
                                <p class="mb-0 fs-6">{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Lotes</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Valor</th>
                                        <th>Taxa</th>
                                        <th>Preço final</th>
                                        <th>Quantidade</th>
                                        <th>Visibilidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($event->lotes->count() > 0)
                                        @foreach($event->lotes as $lote)
                                            <tr>
                                                <td>{{$lote->id}}</td>
                                                <td>
                                                    <div class="fw-bold">{{$lote->name}}</div>
                                                    <small class="text-muted">{{ Str::limit($lote->description, 50) }}</small>
                                                </td>
                                                <td>@money($lote->value)</td>
                                                <td>@money($lote->tax)</td>
                                                <td><span class="fw-bold text-success">@money($lote->final_value)</span></td>
                                                <td>{{$lote->quantity}}</td>
                                                <td>
                                                    @if($lote->visibility == 0) 
                                                        <span class="badge bg-primary">Público</span> 
                                                    @else 
                                                        <span class="badge bg-secondary">Privado</span> 
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="fas fa-ticket-alt fa-2x mb-2"></i><br>
                                                Nenhum lote cadastrado.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- <hr> --}}
                        {{-- <label for="cupons">Cupons</label>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Código</th>
                                        <th>Tipo desconto</th>
                                        <th>Valor desconto</th>
                                        <th>Limite de compras</th>
                                        <th>Limite de inscrições</th>
                                        <th>Lotes</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($event->coupons as $coupon)
                                        <tr>
                                            <td>{{$coupon->id}}</td>
                                            <td>{{$coupon->code}}</td>
                                            <td>@if($coupon->discount_type == 0) Porcentagem @elseif($coupon->discount_type == 1) Fixo @endif</td>
                                            <td>@if($coupon->discount_type == 0) {{$coupon->discount_value*100}}% @elseif($coupon->discount_type == 1) @money($coupon->discount_value) @endif</td>
                                            <td>{{$coupon->limit_buy}}</td>
                                            <td>{{$coupon->limit_tickets}}</td>
                                            <td>
                                                <ul class="list-group list-group-flush">
                                                    @foreach($coupon->lotes as $lote)
                                                    <li>{{$lote->name}}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>@if($coupon->status == 1) <span class="badge badge-success">Ativo</span> @else <span class="badge badge-danger">Não ativo</span> @endif</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}
                    </div>
                    <div class="card-footer bg-white border-top d-flex justify-content-between py-3">
                        <a href="{{ route('event_home.my_events') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="{{ asset('assets_admin/jquery.datetimepicker.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/print.css') }}" media="print" type="text/css"/>
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
      @endpush

      @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="{{ asset('assets_admin/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
        $(document).ready(function() {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            $('#description').summernote({
                placeholder: 'Descreva em detalhes o evento',
                tabsize: 2,
                height: 200
            });

            $('#name').keyup(function(e) {
                $.get('{{ route('event_home.check_slug') }}', 
                    { 'title': $(this).val() }, 
                    function( data ) {
                        $('#slug').val(data.slug);
                        if(data.slug_exists == '1'){
                            $('#slug').removeClass('is-valid');
                            $('#slug').addClass('is-invalid');
                            $('#slugHelp').removeClass('d-none');
                        }else{
                            $('#slug').removeClass('is-invalid');
                            $('#slug').addClass('is-valid');
                            $('#slugHelp').addClass('d-none');
                        }
                    }
                );
            });

            $('#slug').keyup(function(e) {
                $.get('{{ route('event_home.create_slug') }}', 
                    { 'title': $(this).val() }, 
                    function( data ) {
                        if(data.slug_exists == '1'){
                            $('#slug').removeClass('is-valid');
                            $('#slug').addClass('is-invalid');
                        }else{
                            $('#slug').removeClass('is-invalid');
                            $('#slug').addClass('is-valid');
                        }
                    }
                );
            });

            $('#category').on('change', function() {
                var category_id = this.value;
                $("#area_id").html('');
                $.ajax({
                    url:"{{route('event_home.get_areas_by_category')}}",
                    type: "POST",
                    data: {
                        category_id: category_id,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        $('#area_id').html('<option value="">Selecione</option>'); 
                        $.each(result.areas,function(key,value){
                            $("#area_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
            });

            var category_id = $("#category").val();
                $("#area_id").html('');
                $.ajax({
                    url:"{{route('event_home.get_areas_by_category')}}",
                    type: "POST",
                    data: {
                        category_id: category_id,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        $('#area_id').html('<option value="">Selecione</option>'); 
                        area_id = $('#area_id_hidden').val();
                        $.each(result.areas,function(key,value){
                            $("#area_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                        $('#area_id option[value='+area_id+']').attr('selected','selected');
                    }
                });

            $('#cmd').click(function(){
                $('#card-date').append('<div class="row">' + 
                        '<div class="form-group col-md-3">' +
                            '<label for="number">Data</label>'+
                            '<div class="input-group date" data-target-input="nearest">'+
                                '<input class="form-control datetimepicker-input datetimepicker_day" autocomplete="off" name="date[]" value=""/>'+
                                '<div class="input-group-append" data-toggle="datetimepicker">'+
                                    '<div class="input-group-text"><i class="fa fa-calendar"></i></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group col-md-2">'+
                            '<label for="number">Hora início</label>'+
                            '<div class="input-group date" data-target-input="nearest">'+
                                '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" autocomplete="off" name="time_begin[]" value=""/>'+
                                '<div class="input-group-append" data-toggle="datetimepicker">'+
                                    '<div class="input-group-text"><i class="fa-regular fa-clock"></i></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group col-md-2">'+
                            '<label for="number">Hora fim</label>'+
                            '<div class="input-group date" data-target-input="nearest">'+
                                '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" autocomplete="off" name="time_end[]" value=""/>'+
                $('#address').val('');
                $('#address').prop("readonly", false);
                $('#number').val('');
                $('#number').prop("readonly", false);
                $('#district').val('');
                $('#district').prop("readonly", false);
                $('#complement').val('');
                $('#complement').prop("readonly", false);
                $('#zip').val('');
                $('#zip').prop("readonly", false);
                
                $('#state').prop("disabled", false);
                $('#state').prop('selectedIndex',0);
                $('#city').prop("disabled", false);
                $('#city').prop('selectedIndex',0);
                $('#city_id_hidden').val('');
                
                // $('#state option[value="'+ui.item.uf+'"]').prop("selected", true);
                // $('#state').prop("readonly", true);
                // $('#city').prop("readonly", true);                 
            });

            var path = "{{route('event_home.autocomplete_place')}}";
            $("#place_name").autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: path,
                        type: 'GET',
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function( data ) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    $('#place_name').val(ui.item.label);
                    $('#place_id_hidden').val(ui.item.id);
                    $('#address').val(ui.item.address);
                    $('#address').prop("readonly", true);
                    $('#number').val(ui.item.number);
                    $('#number').prop("readonly", true);
                    $('#district').val(ui.item.district);
                    $('#district').prop("readonly", true);
                    $('#complement').val(ui.item.complement);
                    $('#complement').prop("readonly", true);
                    $('#zip').val(ui.item.zip);
                    $('#zip').prop("readonly", true);
                    
                    $('#state option[value="'+ui.item.uf+'"]').prop("selected", true);
                    $('#state').prop("disabled", true);
                    $('#city').prop("disabled", true);
                    
                    var uf = $("#state").val();
                    $("#city").html('');
                    $.ajax({
                        url:"{{route('event_home.get_city')}}",
                        type: "POST",
                        data: {
                            uf: uf,
                            _token: '{{csrf_token()}}' 
                        },
                        dataType : 'json',
                        success: function(result){
                            $('#city').html('<option value="">Selecione</option>'); 
                            city_id = $('#city_id_hidden').val();

                            $.each(result.cities,function(key,value){
                                $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
                            });

                            $('#city option[value="'+ui.item.city_id+'"]').prop("selected", true);
                            $('#city_id_hidden').val(ui.item.city_id);
                        }
                    });

                    return false;
                }
            });

            $('#state').on('change', function() {
                var uf = this.value;
                $("#city").html('');
                $.ajax({
                    url: "{{route('event_home.get_city')}}",
                    type: "POST",
                    data: {
                        uf: uf,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        $('#city').html('<option value="">Selecione</option>'); 
                        $.each(result.cities,function(key,value){
                            $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
            });

            var uf = $("#state").val();
            $("#city").html('');
            $.ajax({
                url: "{{route('event_home.get_city')}}",
                type: "POST",
                data: {
                    uf: uf,
                    _token: '{{csrf_token()}}' 
                },
                dataType : 'json',
                success: function(result){
                    $('#city').html('<option value="">Selecione</option>'); 
                    city_id = $('#city_id_hidden').val();

                    $.each(result.cities,function(key,value){
                        $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
                    });

                    $('#city option[value='+city_id+']').attr('selected','selected');
                }
            });

            $('body').on('click',".btn-remove-field", function(){
                $(this).parent().parent().remove();
                i_field = i_field-1;
            });

            $('body').on('click',".btn-remove", function(){
                $(this).parent().parent().remove();
            });

            $('body').on('mousedown',".datetimepicker_day", function(){
                $(this).datetimepicker({
                    timepicker:false,
                    format:'d/m/Y',
                    mask:true
                });
            });

            $('body').on('mousedown',".datetimepicker_hour_begin", function(){
                $(this).datetimepicker({
                    datepicker:false,
                    format:'H:i',
                    mask:true,
                    onShow:function( ct ){
                        this.setOptions({
                            maxTime:$(this).val()?$(this).val():false
                        })
                    }
                });
            });

            $('body').on('mousedown',".datetimepicker_hour_end", function(){
                $(this).datetimepicker({
                    datepicker:false,
                    format:'H:i',
                    mask:true,
                    onShow:function( ct ){
                        this.setOptions({
                            minTime:$(this).val()?$(this).val():false
                        })
                    }
                });
            });
        });
    
    </script>
      
    @endpush

</x-site-layout>