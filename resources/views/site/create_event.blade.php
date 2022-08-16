<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Criar um novo evento</h2>
    
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
                    <ul id="progressbar">
                        <li class="active" id="account"><strong>Informações</strong></li>
                        <li id="personal"><strong>Inscrições</strong></li>
                        <li id="payment"><strong>Cupom</strong></li>
                        <li id="confirm"><strong>Fim</strong></li>
                    </ul>
                    <form method="POST" action="{{ route('event_home.create.step.one') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <h4>Sobre o evento</h4>
                            <div class="form-group">
                                <label for="name">Nome*</label>
                                <input type="text" class="form-control col-12" id="name" name="name" placeholder="Nome" value="{{ $event->name ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="slug">URL do evento*</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon3">https://example.com/use/</span>
                                    </div>
                                    <input type="text" class="form-control col-lg-12 col-sm-12" id="slug" name="slug" placeholder="URL do evento" aria-describedby="basic-addon3" value="{{ $event->slug ?? '' }}">
                                  </div>
                                <small id="slugHelp" class="form-text text-danger d-none">Essa URL já está em uso, por favor, selecione outra.</small>
                            </div>
                            <div class="form-group">
                                <label for="subtitle">Subtítulo</label>
                                <input type="text" class="form-control col-lg-12 col-sm-12" id="subtitle" name="subtitle" placeholder="Subtítulo" value="{{ $event->subtitle ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Descrição*</label>
                                <textarea class="form-control" id="description" name="description"> {{ $event->description ?? '' }}</textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-sm-12">
                                    <label for="category">Categoria*</label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option>Selecione</option>
                                        @foreach ($categories as $category)
                                        <option value="{{$category->id}}" @if(isset($event->category)) @if($event->category == $category->id) selected @endif @endif>{{$category->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-sm-12">
                                    <label for="area_id">Área*</label>
                                    <select name="area_id" id="area_id" class="form-control" required>
                                        <option>Selecione</option>
                                        <option>...</option>
                                    </select>
                                </div>
                                {{-- {{dd($event)}} --}}
                                <input type="hidden" name="area_id_hidden" id="area_id_hidden" value="{{ $event->area_id ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="owner_email">Organizador*</label>
                                <input type="text" readonly class="form-control col-lg-6 col-sm-12" id="owner_email" name="owner_email" placeholder="Organizador" value="{{Auth::user()->email}}">
                            </div>
                            <div class="form-group">
                                <label for="banner">Banner do evento*</label><br/>
                                @if(!isset($event->banner))
                                    <input type="file" id="banner" required>
                                    <label class="custom-file-label" for="banner">Choose file</label>
                                @else
                                <div class="form-group">
                                    <img src="{{ asset('storage/'.$event->banner) }}" alt="Banner evento" class="img-fluid img-thumbnail" style="width: 400px">
                                    <a href="{{route('event_home.delete_file', $event->id)}}" class="btn btn-danger">Excluir</a>
                                </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="max_tickets">Total máximo de vagas*</label>
                                <input type="number" class="form-control col-lg-2 col-sm-12" id="max_tickets" name="max_tickets" value="{{ $event->max_tickets ?? '' }}" min="0">
                            </div>
                        </div>
                        <hr>
                        <div class="card-body" id="card-date">
                            <h4>Data e hora do evento</h4>
                            {{-- {{dd($eventDate->date[0])}} --}}
                            @if(isset($eventDate))
                                @foreach ($eventDate as $date)
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="number">Data</label>
                                            <div class="input-group date" id="datetimepicker_day_{{$loop->index}}" data-target-input="nearest">
                                                <input class="form-control datetimepicker-input datetimepicker_day" data-target="#datetimepicker_day_{{$loop->index}}" name="date[]" value="{{$eventDate->date[0]}}"/>
                                                <div class="input-group-append" data-target="#datetimepicker_day_{{$loop->index}}" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="number">Hora início</label>
                                            <div class="input-group date" id="datetimepicker_hour_begin_{{$loop->index}}" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" data-target="#datetimepicker_hour_begin_{{$loop->index}}" name="time_begin[]" value="{{$eventDate->time_begin[0]}}"/>
                                                <div class="input-group-append" data-target="#datetimepicker_hour_begin_{{$loop->index}}" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="number">Hora fim</label>
                                            <div class="input-group date" id="datetimepicker_hour_end_{{$loop->index}}" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" data-target="#datetimepicker_hour_end_{{$loop->index}}" name="time_end[]" value="{{$eventDate->time_end[0]}}"/>
                                                <div class="input-group-append" data-target="#datetimepicker_hour_end_{{$loop->index}}" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <input class="datepicker_recurring_time_begin"/>
                                        <input class="datepicker_recurring_time_end"/> --}}
                                        @if ($loop->first)
                                            <div class="form-group col-md-2">
                                                <a class="btn btn-success btn-sm mr-1" id="cmd" style="margin-top: 35px" href="javascript:;">
                                                    <i class="fa-solid fa-plus"></i>
                                                    Adicionar novo
                                                </a>
                                            </div> 
                                        @else
                                            <div class="form-group col-md-2">
                                                <a class="btn btn-danger btn-sm mr-1 btn-remove" style="margin-top: 35px" href="javascript:;">
                                                    <i class="fa-solid fa-remove"></i>
                                                    Remover
                                                </a>
                                            </div> 
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="number">Data</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input class="form-control datetimepicker-input datetimepicker_day" name="date[]" value=""/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="number">Hora início</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" name="time_begin[]" value=""/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="number">Hora fim</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" name="time_end[]" value=""/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <a class="btn btn-success btn-sm mr-1" id="cmd" style="margin-top: 35px" href="javascript:;">
                                            <i class="fa-solid fa-plus"></i>
                                            Adicionar novo
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <hr>
                        <div class="card-body">
                            <h4>Endereço do evento</h4>
                            <div class="form-row">
                                <div class="form-group col-md-10">
                                    <label for="place_name">Local*</label>
                                    <input type="text" class="form-control" id="place_name" name="place_name" placeholder="Local" value="{{ $place->place_name ?? '' }}" required>
                                    <small id="place_nameHelp" class="form-text text-muted">Busque pelo local do evento, caso não o encontre, clique no botão a seguir para cadastrar um novo local.</small>
                                </div>
                                <div class="form-group col-md-2">
                                    <a class="btn btn-warning btn-sm mr-1" style="margin-top: 35px" href="javascript:;" id="add_place">
                                        <i class="fa-solid fa-plus"></i>
                                        Não encontrou o local?
                                    </a>
                                </div> 
                            </div>
                            <div id="event_address" style="display: none">
                                <div class="form-row">
                                    <div class="form-group col-md-10">
                                        <label for="address">Rua*</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Rua" value="{{ $place->address ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="number">Número*</label>
                                        <input type="text" class="form-control" id="number" name="number" placeholder="Número" value="{{ $place->number ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="district">Bairro*</label>
                                    <input type="text" class="form-control" id="district" name="district" placeholder="Bairro" value="{{ $place->district ?? '' }}" required>
                                </div>
                               <div class="form-group">
                                    <label for="complement">Complemento</label>
                                    <input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento" value="{{ $place->complement ?? '' }}">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label for="state">Estado*</label>
                                        <select id="state" class="form-control" name="state" required>
                                            <option>Selecione</option>
                                            @foreach ($states as $state)
                                                <option value="{{$state->uf}}">{{$state->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="city">Cidade*</label>
                                        <select id="city" class="form-control" name="city_id" required>
                                            <option selected>Selecione</option>
                                            <option>...</option>
                                        </select>
                                        <input type="hidden" name="city_id_hidden" id="city_id_hidden" value="{{ $place->city_id ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="zip">CEP*</label>
                                        <input type="text" class="form-control" id="zip" name="zip" placeholder="CEP" value="{{ $place->zip ?? '' }}" required>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <hr>
                        <div class="card-body" style="padding-right: 34px">
                            <h4>Campos do formulário de inscrição</h4>
                            <label for="question">Novo campo</label>
                            <div class="form-row" style="margin:0">
                                <div class="form-group col-md-5">
                                    <input type="text" class="form-control" id="question" name="question" placeholder="Nome" value="">
                                </div>
                                <div class="form-group col-md-3">
                                    <select id="option" class="form-control" name="option" required>
                                        @foreach ($options as $option)
                                            <option value="{{$option->id}}">{{$option->option}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3 col-sm-9">
                                    <label for="required" style="margin-top:2px; padding: 5px 10px; border: solid 1px #ccc; border-radius: 10px;">
                                        <input type="checkbox" name="required" id="required" value="1"> <b>Obrigatório</b>
                                    </label>
                                    <label for="unique" style="margin-left: 5px; margin-top:2px; padding: 5px 10px; border: solid 1px #ccc; border-radius: 10px;">
                                        <input type="checkbox" name="unique" id="unique" value="1"> <b>Único</b>
                                    </label>
                                </div>
                                <div class="form-group col-md-1 col-sm-3" style="margin-top: 1px;">
                                    <button type="button" class="btn btn-success" id="add_new_field">Adicionar</button>
                                </div>
                            </div>
                            <div id="card-new-field" style="margin:0">
                                <div class="form-group">
                                    <label for="name_new_field">Campo 1*</label>
                                    <input type="text" class="form-control" name="name_new_field" id="name_new_field" value="Nome" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="email_new_field">Campo 2*</label>
                                    <input type="text" class="form-control" name="email_new_field" id="email_new_field" value="Email" readonly>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- /.card-body -->
                        <div class="pl-5 pr-4 text-right">
                            <button type="submit" class="btn btn-primary">Próximo</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

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
                    $('#event_address').toggle();                    
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
                    $('#address').val(ui.item.address);
                    $('#number').val(ui.item.number);
                    $('#district').val(ui.item.district);
                    $('#complement').val(ui.item.complement);
                    $('#zip').val(ui.item.zip);

                    $('#state option[value="'+ui.item.uf+'"]').prop("selected", true);
                    
                    var uf = $("#state").val();
                    $("#city").html('');
                    $.ajax({
                        url:"{{url('admin/places/get-cities-by-state')}}",
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
                        }
                    });

                    return false;
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