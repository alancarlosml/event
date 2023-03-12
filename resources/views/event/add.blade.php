<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Eventos</h1>
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
                    <form method="POST" action="{{route('event.store')}}">
                        @csrf
                        <div class="card-body">
                            <h4>Sobre o evento</h4>
                            <div class="form-group">
                                <label for="name">Nome*</label>
                                <input type="text" class="form-control col-lg-6 col-sm-12" id="name" name="name" placeholder="Nome" required>
                            </div>
                            <div class="form-group">
                                <label for="slug">URL do evento*</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon3">http://www.ticketdz6.com.br/</span>
                                    </div>
                                    <input type="text" class="form-control col-lg-4 col-sm-12" id="slug" name="slug" placeholder="URL do evento" aria-describedby="basic-addon3" required>
                                  </div>
                                <small id="slugHelp" class="form-text text-danger d-none">Essa URL já está em uso, por favor, selecione outra.</small>
                            </div>
                            <div class="form-group">
                                <label for="subtitle">Subtítulo</label>
                                <input type="text" class="form-control col-lg-6 col-sm-12" id="subtitle" name="subtitle" placeholder="Subtítulo" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Descrição*</label>
                                <textarea class="form-control" id="description" name="description" required>

                                </textarea>
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
                                <input type="text" class="form-control col-lg-6 col-sm-12" id="owner_email" name="owner_email" placeholder="Organizador" required>
                            </div>
                            <div class="form-group col-lg-6 col-sm-12 p-0">
                                <label for="banner">Banner evento*</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="banner" required>
                                    <label class="custom-file-label" for="banner">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="total">Total máximo de vagas*</label>
                                <input type="number" class="form-control col-lg-2 col-sm-12" id="total" name="total" placeholder="0" required min="0">
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <h4>Data e hora do evento</h4>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="number">Data</label>
                                    <div class="input-group date" id="datetimepicker_day" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="datetimepicker_day_input" data-target="#datetimepicker_day"/>
                                        <div class="input-group-append" data-target="#datetimepicker_day" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="number">Hora início</label>
                                    <div class="input-group date" id="datetimepicker_hour_begin" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker_hour_begin"/>
                                        <div class="input-group-append" data-target="#datetimepicker_hour_begin" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="number">Hora fim</label>
                                    <div class="input-group date" id="datetimepicker_hour_end" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker_hour_end"/>
                                        <div class="input-group-append" data-target="#datetimepicker_hour_end" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <a class="btn btn-success btn-sm mr-1" style="margin-top: 35px" href="#">
                                        <i class="fa-solid fa-plus"></i>
                                        Adicionar novo
                                    </a>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="number">Data</label>
                                    <div class="input-group date" id="datetimepicker_day" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="datetimepicker_day_input" data-target="#datetimepicker_day"/>
                                        <div class="input-group-append" data-target="#datetimepicker_day" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="number">Hora início</label>
                                    <div class="input-group date" id="datetimepicker_hour_begin" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker_hour_begin"/>
                                        <div class="input-group-append" data-target="#datetimepicker_hour_begin" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="number">Hora fim</label>
                                    <div class="input-group date" id="datetimepicker_hour_end" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker_hour_end"/>
                                        <div class="input-group-append" data-target="#datetimepicker_hour_end" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <a class="btn btn-danger btn-sm mr-1" style="margin-top: 35px" href="#">
                                        <i class="fa-solid fa-remove"></i>
                                        Remover
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <h4>Endereço do evento</h4>
                            <div class="form-group">
                              <label for="place_name">Local*</label>
                              <input type="text" class="form-control" id="place_name" name="place_name" placeholder="Local" required>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-10">
                                <label for="address">Rua*</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Rua" required>
                              </div>
                              <div class="form-group col-md-2">
                                <label for="number">Número*</label>
                                <input type="text" class="form-control" id="number" name="number" placeholder="Número" required>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="district">Bairro*</label>
                              <input type="text" class="form-control" id="district" name="district" placeholder="Bairro" required>
                            </div>
                            <div class="form-group">
                              <label for="complement">Complemento</label>
                              <input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento">
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-5">
                                  <label for="state">Estado*</label>
                                  <select id="state" class="form-control" name="state" required>
                                    <option>Selecione</option>
                                    @foreach ($states as $state)
                                      <option value="{{$state->id}}">{{$state->name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              <div class="form-group col-md-5">
                                <label for="city_id">Cidade*</label>
                                <select id="city_id" class="form-control" name="city_id" required>
                                  <option selected>Selecione</option>
                                  <option>...</option>
                                </select>
                              </div>
                              <div class="form-group col-md-2">
                                <label for="zip">CEP*</label>
                                <input type="text" class="form-control" id="zip" name="zip" placeholder="CEP" required>
                              </div>
                            </div>  
                        </div>
                        <hr/>    
                        <div class="card-body">
                            <h4>Campos do formulário de inscrição</h4>
                            {{-- <div class="form-group">
                              <label for="name">Nome</label>
                              <input type="text" class="form-control" id="name" name="name" placeholder="Nome">
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-10">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                              </div>
                              <div class="form-group col-md-2">
                                <label for="number">Número</label>
                                <input type="text" class="form-control" id="number" name="number" placeholder="Número">
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="district">Bairro</label>
                              <input type="text" class="form-control" id="district" name="district" placeholder="Bairro">
                            </div>
                            <div class="form-group">
                              <label for="complement">Complemento</label>
                              <input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="state">Estado</label>
                                    <select id="state" class="form-control" name="state">
                                      <option>Selecione</option>
                                      @foreach ($states as $state)
                                        <option value="{{$state->id}}">{{$state->name}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                <div class="form-group col-md-5">
                                  <label for="city">Cidade</label>
                                  <select id="city" class="form-control" name="city_id">
                                    <option selected>Selecione</option>
                                    <option>...</option>
                                  </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="zip">CEP</label>
                                    <input type="text" class="form-control" id="zip" name="zip" placeholder="CEP">
                                </div>
                            </div>   --}}
                        </div>
                        <hr/>                     
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
                    $.get('{{ route('event.create_slug') }}', 
                        { 'title': $(this).val() }, 
                        function( data ) {
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

                $('#state').on('change', function() {
                    var uf = this.value;
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
                            $.each(result.cities,function(key,value){
                                $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        }
                    });
                });

                $('#category').on('change', function() {
                    var category_id = this.value;
                    $("#area_id").html('');
                    $.ajax({
                        url:"{{url('admin/categories/get-areas-by-category')}}",
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
