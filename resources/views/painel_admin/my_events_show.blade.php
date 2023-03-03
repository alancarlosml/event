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
    
        <section class="inner-page pt-0" id="create-event-form">
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Default box -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 mt-1"><b>Detalhes do comprador</b></h5>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <div class="container">
                                        <div class="row">
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="id">CPF</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{$order->participante->cpf}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Nome</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{$order->participante->name}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Email</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{$order->participante->email}} @else - @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="subtitle">Telefone</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{$order->participante->phone}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Data criação</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{ \Carbon\Carbon::parse($order->participante->created_at)->format('d/m/Y H:i') }} @else - @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <!-- /.card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 mt-1"><b>Detalhes da venda</b></h5>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <div class="container">
                                        <div class="row">
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="id">Hash da venda</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->order_hash)){{$order->order_hash}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Hash Mercado Pago</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->gatway_hash)){{$order->gatway_hash}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Referência Mercado Pago</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->gatway_reference)){{$order->gatway_reference}} @else - @endif
                                                    </p>
                                                </div>
                                                
                                            </div>
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="subtitle">Status da compra</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->situacao)) @if($order->situacao == 1) Confirmado @elseif($order->situacao == 2) Pendente @elseif($order->situacao == 3) Cancelado @endif @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="slug">Forma pagamento</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->gatway_payment_method)) @if($order->gatway_payment_method == 'credit') Crédito @elseif($order->gatway_payment_method == 'boleto') Boleto @elseif($order->gatway_payment_method == 'pix') Pix @else  Não informado @endif @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="slug">Data da compra</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->created_at)){{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }} @else - @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            @if(isset($order->order_items))
                            @foreach($order->order_items as $k => $order_item)
                            <!-- /.card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 mt-1"><b>Participante #{{$k+1}}</b></h5>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <div class="container">
                                        <div class="row">
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="id">Hash participante</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        {{$order_item->hash}}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="id">Nº inscrição</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        {{$order_item->number}}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Lote</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order_item->lote)){{$order_item->lote->name}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Valor do ingresso</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @money($order_item->value)
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Taxa de serviço</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @money($order_item->value)
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Valor recebido</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @money($order_item->value)
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Data uso</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if($order_item->date_use){{ \Carbon\Carbon::parse($order_item->date_use)->format('d/m/Y H:i') }} @else Não utilizado @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Status da compra do participante</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if($order_item->status == 1) Confirmado @elseif($order_item->status == 2) Pendente @elseif($order_item->status == 3) Cancelado  @elseif($order_item->status == 4) Estorno @else - @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @if(isset($order_item->answers))
                                            <div class="card-body col-6">
                                                @foreach($order_item->answers as $answer)
                                                <div class="form-group">
                                                    <label for="subtitle">{{$answer->question->question}}</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        {{$answer->answer}}
                                                    </p>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <!-- /.card -->
                            @endforeach
                            @endif
                        </div>
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
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="../../../assets_admin/jquery.datetimepicker.min.css " rel="stylesheet">
          
      @endpush

      @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="../../../assets_admin/jquery.datetimepicker.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
        $(document).ready(function() {

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
                $('#card-date').append('<div class="form-row">' + 
                        '<div class="form-group col-md-3">' +
                            '<label for="number">Data</label>'+
                            '<div class="input-group date" data-target-input="nearest">'+
                                '<input class="form-control datetimepicker-input datetimepicker_day" name="date[]" value=""/>'+
                                '<div class="input-group-append" data-toggle="datetimepicker">'+
                                    '<div class="input-group-text"><i class="fa fa-calendar"></i></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group col-md-2">'+
                            '<label for="number">Hora início</label>'+
                            '<div class="input-group date" data-target-input="nearest">'+
                                '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" name="time_begin[]" value=""/>'+
                                '<div class="input-group-append" data-toggle="datetimepicker">'+
                                    '<div class="input-group-text"><i class="fa-regular fa-clock"></i></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group col-md-2">'+
                            '<label for="number">Hora fim</label>'+
                            '<div class="input-group date" data-target-input="nearest">'+
                                '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" name="time_end[]" value=""/>'+
                                '<div class="input-group-append" data-toggle="datetimepicker">'+
                                    '<div class="input-group-text"><i class="fa-regular fa-clock"></i></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group col-md-2">'+
                            '<a class="btn btn-danger btn-sm mr-1 btn-remove" style="margin-top: 35px" href="javascript:;">'+
                                '<i class="fa-solid fa-remove"></i>'+
                                ' Remover'+
                            '</a>'+
                        '</div>'+ 
                    '</div>'
                );
            });

            var i_field = 2;
            $('#add_new_field').click(function(){
                var field = $(this).parent().parent().find('#question').val();
                var option = $(this).parent().parent().find('#option').val();
                var option_text = $(this).parent().parent().find('#option:selected').text();
                var required = $(this).parent().parent().find('#required').is(":checked");
                var unique = $(this).parent().parent().find('#unique').is(":checked");

                if(field === ''){

                    alert('preencha');
                    return false;
                }

                var required_star = required ? '*':'';
                var field_text = '';
                var field_name = '';
                i_field = i_field+1;

                $('#question').val('');
                $('#option').prop('selectedIndex',0);
                $('#required').prop('checked',false);
                $('#unique').prop('checked',false);

                switch(option){
                    case '1':
                        field_text = '(Tipo: Texto (Até 200 caracteres))';
                        field_name = 'text';
                        break;
                    case '2':
                        field_text = '(Tipo: Seleção)';
                        field_name = 'select';
                        break;
                    case '3':
                        field_text = '(Tipo: Marcação)';
                        field_name = 'checkbox';
                        break;
                    case '4':
                        field_text = '(Tipo: Múltipla escolha)';
                        field_name = 'multiselect';
                        break;
                    case '5':
                        field_text = '(Tipo: CPF)';
                        field_name = 'cpf';
                        break;
                    case '6':
                        field_text = '(Tipo: CNPJ)';
                        field_name = 'cnpj';
                        break;
                    case '7':
                        field_text = '(Tipo: Data)';
                        field_name = 'date';
                        break;
                    case '8':
                        field_text = '(Tipo: Telefone)';
                        field_name = 'phone';
                        break;
                    case '9':
                        field_text = '(Tipo: Número inteiro)';
                        field_name = 'integer';
                        break;
                    case '10':
                        field_text = '(Tipo: Número decimal)';
                        field_name = 'decimal';
                        break;
                    case '11':
                        field_text = '(Tipo: Arquivo)';
                        field_name = 'file';
                        break;
                    case '12':
                        field_text = '(Tipo: Textarea (+ de 200 caracteres))';
                        field_name = 'textearea';
                        break;
                    case '13':
                        field_text = '(Tipo: Email)';
                        field_name = 'new_email';
                        break;
                    case '14':
                        field_text = '(Tipo: Estados (BRA))';
                        field_name = 'states';
                        break;
                }

                $('#card-new-field').append('<div class="form-row">' +
                    '<div class="form-group col-10">'+
                        '<label for="field_'+i_field+'">Campo ' + i_field + required_star + '</label>' +
                        '<input type="text" class="form-control" name="'+field_name+'_new_field" value="'+field+' '+field_text +'" readonly>' +
                    '</div>'+
                    '<div class="form-group col-2">'+
                        '<a class="btn btn-danger btn-sm mr-1 btn-remove-field" style="margin-top: 35px" href="javascript:;">'+
                            '<i class="fa-solid fa-remove"></i>'+
                            ' Remover'+
                        '</a>'+
                    '</div>'+
                '</div>');
            });

            $('#add_place').click(function(){
                $('#place_name').val('');
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