<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Gerenciar evento</h2>
    
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
                        <li id="payment"><strong>Cupons</strong></li>
                        <li id="confirm"><strong>Publicar</strong></li>
                    </ul>
                    {{-- {{dd($event->get_category())}} --}}
                    <form method="POST" action="{{ route('event_home.create.step.one') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <h4>Sobre o evento</h4>
                            <div class="form-group">
                                <label for="name">Nome*</label>
                                <input type="text" class="form-control col-12" id="name" name="name" placeholder="Nome" value="{{ $event->name ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="slug">URL do evento*</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon3">https://example.com/use/</span>
                                    </div>
                                    <input type="text" class="form-control col-lg-12 col-sm-12" id="slug" name="slug" placeholder="URL do evento" aria-describedby="basic-addon3" value="{{ $event->slug ?? '' }}" required>
                                  </div>
                                <small id="slugHelp" class="form-text text-danger d-none">Essa URL já está em uso, por favor, selecione outra.</small>
                            </div>
                            <div class="form-group">
                                <label for="subtitle">Subtítulo</label>
                                <input type="text" class="form-control col-lg-12 col-sm-12" id="subtitle" name="subtitle" placeholder="Subtítulo" value="{{ $event->subtitle ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Descrição*</label>
                                <textarea class="form-control" id="description" name="description" required> {{ $event->description ?? '' }}</textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-sm-12">
                                    <label for="category">Categoria*</label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option>Selecione</option>
                                        @foreach ($categories as $category)
                                        <option value="{{$category->id}}" @if(isset($event)) @if($event->get_category()->id == $category->id) selected @endif @endif>{{$category->description}}</option>
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
                                <label for="admin_email">Email para contato*</label>
                                <input type="text" class="form-control col-lg-6 col-sm-12" id="admin_email" name="admin_email" placeholder="Contato" value="{{Auth::user()->email}}">
                            </div>
                            {{-- <div class="form-group">
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
                            </div> --}}
                            <div class="form-group">
                                <label for="max_tickets">Total máximo de vagas*</label>
                                <input type="number" class="form-control col-lg-2 col-sm-12" id="max_tickets" name="max_tickets" value="{{ $event->max_tickets ?? '' }}" min="0">
                            </div>
                            <input type="hidden" name="admin_id" id="admin_id_hidden" value="{{Auth::user()->id}}">
                        </div>
                        <hr>
                        <div class="card-body" id="card-date">
                            <h4>Data e hora do evento</h4>
                            @if(isset($eventDate))
                                @foreach ($eventDate as $date)
                            {{-- {{dd($date['date'])}} --}}
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="number">Data</label>
                                            <div class="input-group date" id="datetimepicker_day_{{$loop->index}}" data-target-input="nearest">
                                                <input class="form-control datetimepicker-input datetimepicker_day" data-target="#datetimepicker_day_{{$loop->index}}" name="date[]" value="{{$date['date']}}"/>
                                                <div class="input-group-append" data-target="#datetimepicker_day_{{$loop->index}}" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="number">Hora início</label>
                                            <div class="input-group date" id="datetimepicker_hour_begin_{{$loop->index}}" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" data-target="#datetimepicker_hour_begin_{{$loop->index}}" name="time_begin[]" value="{{$date['time_begin']}}"/>
                                                <div class="input-group-append" data-target="#datetimepicker_hour_begin_{{$loop->index}}" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="number">Hora fim</label>
                                            <div class="input-group date" id="datetimepicker_hour_end_{{$loop->index}}" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" data-target="#datetimepicker_hour_end_{{$loop->index}}" name="time_end[]" value="{{$date['time_end']}}"/>
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
                                        <label for="number">Data*</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input class="form-control datetimepicker-input datetimepicker_day" name="date[]" value="" required/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="number">Hora início*</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" name="time_begin[]" value="" required/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="number">Hora fim*</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" name="time_end[]" value="" required/>
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
                        {{-- {{dd($uf)}} --}}
                        {{-- {{dd($place->get_city()->uf)}} --}}
                        <div class="card-body">
                            <h4>Endereço do evento</h4>
                            <div class="form-row">
                                <div class="form-group col-md-10">
                                    <label for="place_name">Local*</label>
                                    <input type="text" class="form-control" id="place_name" name="place_name" placeholder="Local" value="{{ $place->name ?? '' }}" required>
                                    <small id="place_nameHelp" class="form-text text-muted">Busque pelo local do evento, caso não o encontre, clique no botão a seguir para cadastrar um novo local.</small>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="place_name">Não encontrou o local?</label><br>
                                    <a class="btn btn-success btn-sm mr-1" style="margin-top: 3px" href="javascript:;" id="add_place">
                                        <i class="fa-solid fa-plus"></i>
                                        Adicionar
                                    </a>
                                </div> 
                            </div>
                            <div id="event_address">
                                <div class="form-row">
                                    <div class="form-group col-md-10">
                                        <label for="address">Rua*</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Rua" @if($place) readonly @endif value="{{ $place->address ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="number">Número*</label>
                                        <input type="text" class="form-control" id="number" name="number" placeholder="Número" @if($place) readonly @endif value="{{ $place->number ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="district">Bairro*</label>
                                    <input type="text" class="form-control" id="district" name="district" placeholder="Bairro" @if($place) readonly @endif value="{{ $place->district ?? '' }}" required>
                                </div>
                               <div class="form-group">
                                    <label for="complement">Complemento</label>
                                    <input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento" @if($place) readonly @endif value="{{ $place->complement ?? '' }}">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label for="state">Estado*</label>
                                        <select id="state" class="form-control" name="state" @if($place) readonly @endif required>
                                            <option>Selecione</option>
                                            @foreach ($states as $state)
                                                <option value="{{$state->uf}}" @if(isset($place)) @if($place->get_city()->uf == $state->uf) selected @endif @endif>{{$state->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="city">Cidade*</label>
                                        <select id="city" class="form-control" name="city_id" @if($place) readonly @endif required>
                                            <option selected>Selecione</option>
                                            <option>...</option>
                                        </select>
                                        <input type="hidden" name="city_id_hidden" id="city_id_hidden" value="{{ $place->city_id ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="zip">CEP*</label>
                                        <input type="text" class="form-control" id="zip" name="zip" placeholder="CEP" @if($place) readonly @endif value="{{ $place->zip ?? '' }}" required>
                                    </div>
                                    <input type="hidden" name="place_id_hidden" id="place_id_hidden" value="{{ $place->id ?? '' }}">
                                </div>  
                            </div>
                        </div>
                        <hr>
                        <div class="card-body" style="padding-right: 34px">
                            <h4>Campos do formulário de inscrição</h4>
                            <div class="card p-2 pr-4 mb-2">
                                <label for="question">Novo campo</label>
                                <div class="form-row" style="margin:0">
                                    <div class="form-group col-md-5">
                                        <input type="text" class="form-control" id="question" name="question" placeholder="Nome do campo" value="">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <select id="option" class="form-control" name="option" required>
                                                    @foreach ($options as $option)
                                                        <option value="{{$option->id}}">{{$option->option}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="required" style="margin-top:2px; padding: 5px 10px; border: solid 1px #ccc; border-radius: 10px;">
                                                    <input type="checkbox" name="required" id="required" value="1"> <b>Obrigatório</b>
                                                </label>
                                                <label for="unique" style="margin-left: 5px; margin-top:2px; padding: 5px 10px; border: solid 1px #ccc; border-radius: 10px;">
                                                    <input type="checkbox" name="unique" id="unique" value="1"> <b>Único</b>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12 col-sm-12" id="div_new_options" style="display: none">
                                                <label for="">Preencha as opções separadas por vírgula</label>
                                                <input type="text" class="form-control" name="new_options" id="new_options">
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12" id="div_new_number" style="display: none">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="">Mínimo</label>
                                                        <input type="number" class="form-control val_min_option" name="val_min" min="0">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="">Máximo</label>
                                                        <input type="number" class="form-control val_max_option" name="val_max" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-1 col-sm-3" style="margin-top: 1px;">
                                        <button type="button" class="btn btn-success" id="add_new_field">Adicionar</button>
                                    </div>
                                </div>
                            </div>
                            <div id="card-new-field" style="margin:0">
                                {{-- {{dd($questions)}} --}}
                                @if($questions != "")
                                    @if(isset($questions))
                                        @foreach ($questions as $id => $question)
                                            @php
                                                $var_options = $question->question . "; (Tipo: " . $question->option->option . ")";
                                                
                                                if($question->value() != null){
                                                    $var_options = $var_options . "; [Opções: " . $question->value()->value . "]";
                                                }
                                                
                                                if($question->required == 1){
                                                    $var_options = $var_options . '; Obrigatório';
                                                }     
                                                if($question->unique == 1){
                                                    $var_options = $var_options . '; Único';
                                                }    
                                                
                                            @endphp
                                            @if($id<2)
                                                <div class="form-group">
                                                    <label for="new_field">Campo {{$id+1}}@if($question->required == 1)* @endif</label>
                                                    <input type="text" class="form-control new_field" name="new_field[]" value="{{$var_options}}" readonly>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="form-group col-9">
                                                        <label for="new_field">Campo {{$id+1}}@if($question->required == 1)* @endif</label>
                                                        <input type="text" class="form-control new_field" name="new_field[]" value="{{$var_options}}" readonly>
                                                    </div>
                                                    <div class="form-group col-3">
                                                        <a class="btn btn-danger btn-sm mr-1 btn-remove-field" style="margin-top: 35px;" href="javascript:;">
                                                            <i class="fa-solid fa-remove"></i> Remover
                                                        </a>
                                                        <a class="btn btn-secondary btn-sm mr-1 up" href="javascript:;" style="margin-top: 35px;" >
                                                            <i class="fas fa-arrow-up"></i>
                                                        </a>
                                                        <a class="btn btn-secondary btn-sm mr-1 down" href="javascript:;" style="margin-top: 35px;" >
                                                            <i class="fas fa-arrow-down"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="form-group">
                                            <label for="name_new_field">Campo 1*</label>
                                            <input type="text" class="form-control new_field" name="new_field[]" id="name_new_field" value="Nome; (Tipo: Texto (Até 200 caracteres)); Obrigatório" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="email_new_field">Campo 2*</label>
                                            <input type="text" class="form-control new_field" name="new_field[]" id="email_new_field" value="E-mail; (Tipo: E-mail); Obrigatório; Único" readonly>
                                        </div>
                                    @endif
                                @else
                                    <div class="form-group">
                                        <label for="name_new_field">Campo 1*</label>
                                        <input type="text" class="form-control new_field" name="new_field[]" id="name_new_field" value="Nome; (Tipo: Texto (Até 200 caracteres)); Obrigatório" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="email_new_field">Campo 2*</label>
                                        <input type="text" class="form-control new_field" name="new_field[]" id="email_new_field" value="E-mail; (Tipo: E-mail); Obrigatório; Único" readonly>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Próximo</button>
                        </div>
                        {{-- <div class="pl-5 pr-4 text-right">
                            <button type="submit" class="btn btn-primary">Próximo</button>
                        </div> --}}
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
                        if(area_id){
                            $('#area_id option[value='+area_id+']').attr('selected','selected');
                        }
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

            $('#option').change(function(){

                var id_option_select = $(this).val();

                if(id_option_select == 2 || id_option_select == 3 || id_option_select == 4){
                    $('#div_new_options').show();
                } else {
                    $('#div_new_options').hide();
                }

                if(id_option_select == 9 || id_option_select == 10){
                    $('#div_new_number').show();
                } else {
                    $('#div_new_number').hide();
                }
            });

            var i_field = parseInt($('input.new_field').length);
            $('#add_new_field').click(function(){
                var field = $(this).parent().parent().find('#question').val();
                var option = $(this).parent().parent().find('#option').val();
                var option_text = $(this).parent().parent().find('#option:selected').text();
                var required = $(this).parent().parent().find('#required').is(":checked");
                var unique = $(this).parent().parent().find('#unique').is(":checked");

                if(field === ''){

                    alert('Por favor, preencha o nome do campo!');
                    return false;
                }

                var required_star = required ? '*':'';
                var field_text = '';
                var field_name = '';
                var field_required = '';
                var field_unique = '';
                var field_options = '';

                if(required){
                    field_required = '; Obrigatório';
                }

                if(unique){
                    field_unique = '; Único';
                }

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
                        field_options = '; [Opções: ' + $('#new_options').val() + ']';
                        break;
                    case '3':
                        field_text = '(Tipo: Marcação)';
                        field_name = 'checkbox';
                        field_options = '; [Opções: ' + $('#new_options').val() + ']';
                        break;
                    case '4':
                        field_text = '(Tipo: Múltipla escolha)';
                        field_name = 'multiselect';
                        field_options = '; [Opções: ' + $('#new_options').val() + ']';
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
                        field_options = '; [Opções: ' + $('.val_min_option').val() + '|' + + $('.val_max_option').val() +']';
                        break;
                    case '10':
                        field_text = '(Tipo: Número decimal)';
                        field_name = 'decimal';
                        field_options = '; [Opções: ' + $('.val_min_option').val() + '|' + + $('.val_max_option').val() +']';
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
                        field_text = '(Tipo: E-mail)';
                        field_name = 'new_email';
                        break;
                    case '14':
                        field_text = '(Tipo: Estados (BRA))';
                        field_name = 'states';
                        break;
                }

                $('#card-new-field').append('<div class="form-row">' +
                    '<div class="form-group col-9">'+
                        '<label for="field_'+i_field+'">Campo ' + i_field + required_star + '</label>' +
                        '<input type="text" class="form-control new_field" name="new_field[]" value="'+field+'; ' + field_text + '' + field_options + '' + field_required + '' + field_unique +'" readonly>' +
                    '</div>'+
                    '<div class="form-group col-3">'+
                        '<a class="btn btn-danger btn-sm mr-1 btn-remove-field" style="margin-top: 35px; margin-left: 17px;" href="javascript:;">'+
                            '<i class="fa-solid fa-remove"></i>'+
                            ' Remover'+
                        '</a>'+
                        '<a class="btn btn-secondary btn-sm mr-1 up" href="javascript:;" style="margin-top: 35px;" >'+
                            '<i class="fas fa-arrow-up"></i>'+
                        '</a>'+
                        '<a class="btn btn-secondary btn-sm mr-1 down" href="javascript:;" style="margin-top: 35px;" >'+
                            '<i class="fas fa-arrow-down"></i>'+
                        '</a>'+
                    '</div>'+
                '</div>');

                $("#card-new-field .up:first").hide();
                $("#card-new-field .down:last").hide();
                $("#card-new-field .down:not(:last)").show();

                $('#new_options').val('');
                $('.val_min_option').val('');
                $('.val_max_option').val('');
            });

            $("#card-new-field .up:first").hide();
            $("#card-new-field .down:last").hide();
            
            $(".up,.down").click(function () {
                
                var $element = this;
                var row = $($element).parents("div:first").parents("div:first");
                
                if($(this).is('.up')){
                    row.insertBefore(row.prev());
                }
                else{
                    row.insertAfter(row.next());
                }

                $("#card-new-field .up:first").hide();
                $("#card-new-field .down:last").hide();
                $("#card-new-field .up:not(:first)").show();
                $("#card-new-field .down:not(:last)").show();
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

                            if(ui.item.city_id){
                                $('#city option[value="'+ui.item.city_id+'"]').prop("selected", true);
                                $('#city_id_hidden').val(ui.item.city_id);
                            }
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

                    if(city_id){
                        $('#city option[value='+city_id+']').attr('selected','selected');
                    }
                }
            });

            $('#city').change(function(){
                city_id = $('#city').val();
                $('#city_id_hidden').val(city_id);
            });


            $('body').on('click',".btn-remove-field", function(){
                $(this).parent().parent().remove();
                i_field = i_field-1;
                $("#card-new-field .up:first").hide();
                $("#card-new-field .down:last").hide();
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