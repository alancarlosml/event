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
    
        <section class="inner-page" id="event-list">
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
                    <form method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <h4>Sobre o evento</h4>
                            <div class="form-group">
                                <label for="name">Nome*</label>
                                <input type="text" class="form-control col-6" id="name" name="name" placeholder="Nome" value="">
                            </div>
                            <div class="form-group">
                                <label for="slug">URL do evento*</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon3">https://example.com/use/</span>
                                    </div>
                                    <input type="text" class="form-control col-lg-4 col-sm-12" id="slug" name="slug" placeholder="URL do evento" aria-describedby="basic-addon3" value="">
                                  </div>
                                <small id="slugHelp" class="form-text text-danger d-none">Essa URL já está em uso, por favor, selecione outra.</small>
                            </div>
                            <div class="form-group">
                                <label for="subtitle">Subtítulo</label>
                                <input type="text" class="form-control col-lg-6 col-sm-12" id="subtitle" name="subtitle" placeholder="Subtítulo" value="">
                            </div>
                            <div class="form-group">
                                <label for="description">Descrição*</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-3 col-sm-12">
                                    <label for="category">Categoria*</label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option>Selecione</option>
                                        @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->description}}</option>
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
                            </div>
                            <div class="form-group">
                                <label for="owner_email">Organizador*</label>
                                <input type="text" readonly class="form-control col-lg-6 col-sm-12" id="owner_email" name="owner_email" placeholder="Organizador" value="{{Auth::user()->email}}">
                            </div>
                            <div class="form-group">
                                <label for="banner">Banner do evento*</label><br>
                                <input type="file" id="banner" required>
                            </div>
                            <div class="form-group">
                                <label for="max_tickets">Total máximo de vagas*</label>
                                <input type="number" class="form-control col-lg-2 col-sm-12" id="max_tickets" name="max_tickets" value="{{old('max_tickets')}}" min="0">
                            </div>
                        </div>
                        <hr>
                        <div class="card-body" id="card-date">
                            <h4>Data e hora do evento</h4>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="number">Data</label>
                                    <div class="input-group date" id="datetimepicker_day" data-target-input="nearest">
                                        <input class="form-control datetimepicker-input datetimepicker_day" data-target="#datetimepicker_day" name="date[]" value=""/>
                                        <div class="input-group-append" data-target="#datetimepicker_day" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="number">Hora início</label>
                                    <div class="input-group date" id="datetimepicker_hour_begin" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" data-target="#datetimepicker_hour_begin" name="time_begin[]" value=""/>
                                        <div class="input-group-append" data-target="#datetimepicker_hour_begin" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="number">Hora fim</label>
                                    <div class="input-group date" id="datetimepicker_hour_end" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" data-target="#datetimepicker_hour_end" name="time_end[]" value=""/>
                                        <div class="input-group-append" data-target="#datetimepicker_hour_end" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- @if ($loop->first) --}}
                                    <div class="form-group col-md-2">
                                        <a class="btn btn-success btn-sm mr-1" id="cmd" style="margin-top: 35px" href="javascript:;">
                                            <i class="fa-solid fa-plus"></i>
                                            Adicionar novo
                                        </a>
                                    </div> 
                                {{-- @else
                                    <div class="form-group col-md-2">
                                        <a class="btn btn-danger btn-sm mr-1 btn-remove" style="margin-top: 35px" href="javascript:;">
                                            <i class="fa-solid fa-remove"></i>
                                            Remover
                                        </a>
                                    </div> 
                                @endif --}}
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <h4>Endereço do evento</h4>
                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <label for="place_name">Local*</label>
                                    <input type="text" class="form-control" id="place_name" name="place_name" placeholder="Local" value="" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <a class="btn btn-warning btn-sm mr-1" style="margin-top: 35px" href="javascript:;" id="add_place">
                                        <i class="fa-solid fa-plus"></i>
                                        Não encontrou o local?
                                    </a>
                                </div> 
                            </div>
                            <div id="event_address">
                                <div class="form-row">
                                <div class="form-group col-md-10">
                                    <label for="address">Rua*</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Rua" value="" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="number">Número*</label>
                                    <input type="text" class="form-control" id="number" name="number" placeholder="Número" value="" required>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="district">Bairro*</label>
                                <input type="text" class="form-control" id="district" name="district" placeholder="Bairro" value="" required>
                                </div>
                                <div class="form-group">
                                <label for="complement">Complemento</label>
                                <input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento" value="">
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
                                    <input type="hidden" name="city_id_hidden" id="city_id_hidden" value="">
                                    <input type="hidden" name="area_id_hidden" id="area_id_hidden" value="">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="zip">CEP*</label>
                                        <input type="text" class="form-control" id="zip" name="zip" placeholder="CEP" value="" required>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="pl-5 pr-4 text-right">
                          <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('head')
      <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
      <link href="../../../assets_admin/jquery.datetimepicker.min.css " rel="stylesheet">
          
      @endpush

      @push('footer')
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