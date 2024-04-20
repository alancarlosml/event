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
                                <h3 class="card-title">Editar evento - {{ $event->name }}</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
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
                                <form method="POST" action="{{ route('event.update', $event->id) }}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <h4>Sobre o evento</h4>
                                        <div class="form-group">
                                            <label for="name">Nome do evento*</label>
                                            <input type="text" class="form-control col-lg-6 col-sm-12" id="name" name="name" placeholder="Nome do evento" value="{{ $event->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="slug">URL do evento*</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"
                                                          id="basic-addon3">http://www.ticketdz6.com.br/</span>
                                                </div>
                                                <input type="text" class="form-control col-lg-4 col-sm-12" id="slug" name="slug" placeholder="URL do evento" aria-describedby="basic-addon3" value="{{ $event->slug }}">
                                            </div>
                                            <small id="slugHelp" class="form-text text-danger d-none">Essa URL já está em uso, por favor, selecione outra.</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="subtitle">Subtítulo</label>
                                            <input type="text" class="form-control col-lg-6 col-sm-12" id="subtitle" name="subtitle" placeholder="Subtítulo" value="{{ $event->subtitle }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Descrição*</label>
                                            <textarea class="form-control" id="description" name="description">
												{{ $event->description }}
											</textarea>
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
											<input type="hidden" name="area_id_hidden" id="area_id_hidden" value="{{ $event->area_id ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="contact">Email para contato*</label>
                                            <input type="text" class="form-control col-lg-6 col-sm-12" id="contact" name="contact" placeholder="Contato" value="{{ $event->contact ?? old('contact') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="banner">Banner do evento*</label><br>
                                            @if($event->banner == "")
												<input class="form-control" type="file" id="banner" name="banner">
											@else
											<div class="form-group">
												<img src="{{ asset('storage/'.$event->banner) }}" alt="Banner evento" class="img-fluid img-thumbnail" style="width: 400px">
												<a href="{{route('event_home.delete_file_event', $event->id)}}" class="btn btn-danger ml-1">Excluir</a>
											</div>
											@endif
                                        </div>
                                        <div class="form-group">
                                            <label for="max_tickets">Total máximo de vagas*</label>
                                            <input type="number" class="form-control col-lg-2 col-sm-12"
                                                   id="max_tickets" name="max_tickets"
                                                   value="{{ old('max_tickets', $event->max_tickets) }}"
                                                   min="0">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="card-body" id="card-date">
                                        <h4>Data e hora do evento</h4>
										@if(count($dates) > 0)
											@foreach ($dates as $date)
												<div class="form-row">
													<div class="form-group col-md-3">
														<label for="number">Data</label>
														<div class="input-group date"
															id="datetimepicker_day_{{ $date->id }}"
															data-target-input="nearest">
															<input
																class="form-control datetimepicker-input datetimepicker_day"
																data-target="#datetimepicker_day_{{ $date->id }}"
																name="date[]" value="{{ $date->date }}" />
															<div class="input-group-append"
																data-target="#datetimepicker_day_{{ $date->id }}"
																data-toggle="datetimepicker">
																<div class="input-group-text"><i
																	class="fa fa-calendar"></i></div>
															</div>
														</div>
														<input type="hidden" name="date_id[]" value="">
													</div>
													<div class="form-group col-md-2">
														<label for="number">Hora início</label>
														<div class="input-group date"
															id="datetimepicker_hour_begin_{{ $date->id }}"
															data-target-input="nearest">
															<input type="text"
																class="form-control datetimepicker-input datetimepicker_hour_begin"
																data-target="#datetimepicker_hour_begin_{{ $date->id }}"
																name="time_begin[]" value="{{ $date->time_begin }}" />
															<div class="input-group-append"
																data-target="#datetimepicker_hour_begin_{{ $date->id }}"
																data-toggle="datetimepicker">
																<div class="input-group-text"><i
																	class="fa-regular fa-clock"></i></div>
															</div>
														</div>
													</div>
													<div class="form-group col-md-2">
														<label for="number">Hora fim</label>
														<div class="input-group date"
															id="datetimepicker_hour_end_{{ $date->id }}"
															data-target-input="nearest">
															<input type="text"
																class="form-control datetimepicker-input datetimepicker_hour_end"
																data-target="#datetimepicker_hour_end_{{ $date->id }}"
																name="time_end[]" value="{{ $date->time_end }}" />
															<div class="input-group-append"
																data-target="#datetimepicker_hour_end_{{ $date->id }}"
																data-toggle="datetimepicker">
																<div class="input-group-text"><i
																	class="fa-regular fa-clock"></i></div>
															</div>
														</div>
													</div>
													@if ($loop->first)
														<div class="form-group col-md-2">
															<a class="btn btn-success btn-sm mr-1" id="cmd"
															style="margin-top: 35px" href="javascript:;">
																<i class="fa-solid fa-plus"></i>
																Adicionar novo
															</a>
														</div>
													@else
														<div class="form-group col-md-2">
															<a class="btn btn-danger btn-sm mr-1 btn-remove"
															style="margin-top: 35px" href="javascript:;">
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
													<input type="hidden" name="date_id[]" value="">
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
												<input type="text" class="form-control" id="place_name" name="place_name" placeholder="Local" value="{{ $event->place->name ?? old('place_name') }}" required>
												<small id="place_nameHelp" class="form-text text-muted">Comece a digitar para buscar o local do evento, caso não o encontre, preencha os campos deste formulário.</small>
											</div>
											<div class="form-group col-md-2">
												<label for="place_name">&nbsp;</label><br>
												<a class="btn btn-warning btn-sm mr-1" style="margin-top: 3px" href="javascript:;" id="add_place">
													Limpar campos
												</a>
											</div>  
										</div>
										<div id="event_address">
											<div class="form-row">
												<div class="form-group col-md-10">
													<label for="address">Rua*</label>
													<input type="text" class="form-control" id="address" name="address" placeholder="Rua" @if($event->place) readonly @endif value="{{ $event->place->address ?? old('address') }}" required>
												</div>
												<div class="form-group col-md-2">
													<label for="number">Número*</label>
													<input type="text" class="form-control" id="number" name="number" placeholder="Número" @if($event->place) readonly @endif value="{{ $event->place->number ?? old('number') }}" required>
												</div>
											</div>
											<div class="form-group">
												<label for="district">Bairro*</label>
												<input type="text" class="form-control" id="district" name="district" placeholder="Bairro" @if($event->place) readonly @endif value="{{ $event->place->district ?? old('district') }}" required>
											</div>
										   <div class="form-group">
												<label for="complement">Complemento</label>
												<input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento" @if($event->place) readonly @endif value="{{ $event->place->complement ?? old('complement') }}">
											</div>
											<div class="form-row">
												<div class="form-group col-md-5">
													<label for="state">Estado*</label>
													<select id="state" class="form-control" name="state" @if($event->place) readonly @endif required>
														<option>Selecione</option>
														@foreach ($states as $state)
															<option value="{{$state->id}}" @if(isset($event->place)) @if($event->place->get_city()->uf == $state->uf) selected @endif @endif>{{$state->name}}</option>
														@endforeach
													</select>
												</div>
												<div class="form-group col-md-5">
													<label for="city">Cidade*</label>
													<select id="city" class="form-control" name="city_id" @if($event->place) readonly @endif required>
														<option selected>Selecione</option>
														<option>...</option>
													</select>
													<input type="hidden" name="city_id_hidden" id="city_id_hidden" value="{{ $event->place->city_id ?? '' }}">
												</div>
												<div class="form-group col-md-2">
													<label for="zip">CEP*</label>
													<input type="text" class="form-control" id="zip" name="zip" placeholder="CEP" @if($event->place) readonly @endif value="{{ $event->place->zip ?? old('zip') }}" required>
												</div>
												<input type="hidden" name="place_id_hidden" id="place_id_hidden" value="{{ $event->place->id ?? '' }}">
											</div>  
										</div>
									</div>
                                    <hr>
									<div class="card-body">
										<h4>Configurar taxa específica</h4>
										<div class="form-row">
											<div class="form-group col-md-10">
												<label for="tax">Taxa de juros*</label>
                                                <input type="text" class="form-control" id="tax" name="tax" placeholder="Ex: 7" value="{{ $event->tax * 100 }}">
                                                <small id="taxHelp" class="form-text text-muted">Ex: 7 para 7%.</small>
											</div>
                                        </div>
                                    </div>
                                    {{-- <div class="card-body">
                                        <h4>Endereço do evento</h4>
                                        <div class="form-row">
                                            <div class="form-group col-md-10">
                                                <label for="place_name">Local*</label>
                                                <input type="text" class="form-control" id="place_name"
                                                       name="place_name" placeholder="Local"
                                                       value="{{ $event->place_name }}" required>
                                            </div>
                                            @if (!$event->place_name)
                                                <div class="form-group col-md-2">
                                                    <a class="btn btn-warning btn-sm mr-1" style="margin-top: 35px"
                                                       href="javascript:;" id="add_place">
                                                        <i class="fa-solid fa-plus"></i>
                                                        Não encontrou o local?
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <div id="event_address"
                                             @if (!$event->place_name) class="d-none" @endif>
                                            <div class="form-row">
                                                <div class="form-group col-md-10">
                                                    <label for="address">Rua*</label>
                                                    <input type="text" class="form-control" id="address"
                                                           name="address" placeholder="Rua"
                                                           value="{{ $event->place_address }}" required>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="number">Número*</label>
                                                    <input type="text" class="form-control" id="number"
                                                           name="number" placeholder="Número"
                                                           value="{{ $event->place_number }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="district">Bairro*</label>
                                                <input type="text" class="form-control" id="district"
                                                       name="district" placeholder="Bairro"
                                                       value="{{ $event->place_district }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="complement">Complemento</label>
                                                <input type="text" class="form-control" id="complement"
                                                       name="complement" placeholder="Complemento"
                                                       value="{{ $event->place_complement }}">
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-5">
                                                    <label for="state">Estado*</label>
                                                    <select id="state" class="form-control" name="state" required>
                                                        <option>Selecione</option>
														@foreach ($states as $state)
															<option value="{{$state->id}}" @if(isset($event->place)) @if($event->place->get_city()->uf == $state->uf) selected @endif @endif>{{$state->name}}</option>
														@endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <label for="city">Cidade*</label>
                                                    <select id="city" class="form-control" name="city_id" required>
                                                        <option selected>Selecione</option>
                                                        <option>...</option>
                                                    </select>
                                                    <input type="hidden" name="city_id_hidden" id="city_id_hidden" value="{{ $event->city_id }}">
                                                    <input type="hidden" name="area_id_hidden" id="area_id_hidden" value="{{ $event->area_id }}">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="zip">CEP*</label>
                                                    <input type="text" class="form-control" id="zip" name="zip" placeholder="CEP" value="{{ $event->place_zip }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <hr />
                                    <div class="form-check pb-3">
                                        <div class="custom-switch">
                                            <input type="checkbox" checked="checked" class="custom-control-input"
                                                   name="status" id="status" value="1">
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
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
              integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="{{ asset('assets_admin/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    @endpush

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
                integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="{{ asset('assets_admin/jquery.datetimepicker.full.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"
                integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(function() {

                $('#name').keyup(function(e) {
                    $.get('{{ route('event.check_slug') }}', {
                            'title': $(this).val()
                        },
                        function(data) {
                            $('#slug').val(data.slug);
                            if (data.slug_exists == '1') {
                                $('#slug').removeClass('is-valid');
                                $('#slug').addClass('is-invalid');
                                $('#slugHelp').removeClass('d-none');
                            } else {
                                $('#slug').removeClass('is-invalid');
                                $('#slug').addClass('is-valid');
                                $('#slugHelp').addClass('d-none');
                            }
                        }
                    );
                });

                $('#slug').keyup(function(e) {
                    $.get('{{ route('event.create_slug') }}', {
                            'title': $(this).val()
                        },
                        function(data) {
                            if (data.slug_exists == '1') {
                                $('#slug').removeClass('is-valid');
                                $('#slug').addClass('is-invalid');
                            } else {
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
                        url: "{{ route('category.getAreas') }}",
                        type: "POST",
                        data: {
                            category_id: category_id,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            $('#area_id').html('<option value="">Selecione</option>');
                            $.each(result.areas, function(key, value) {
                                $("#area_id").append('<option value="' + value.id + '">' + value.name + '</option>');
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

                $('#add_place').click(function() {
                    $('#event_address').toggle();
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

                var path = "{{ route('event.autocomplete_place') }}";
                $("#place_name").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: path,
                            type: 'GET',
                            dataType: "json",
                            data: {
                                search: request.term
                            },
                            success: function(data) {
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
							url:"{{route('place.get_city')}}",
							type: "GET",
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

                $('#cmd').click(function() {
                    $('#card-date').append('<div class="form-row">' +
                        '<div class="form-group col-md-3">' +
                        '<label for="number">Data</label>' +
                        '<div class="input-group date" data-target-input="nearest">' +
                        '<input class="form-control datetimepicker-input datetimepicker_day" name="date[]" value=""/>' +
                        '<div class="input-group-append" data-toggle="datetimepicker">' +
                        '<div class="input-group-text"><i class="fa fa-calendar"></i></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="form-group col-md-2">' +
                        '<label for="number">Hora início</label>' +
                        '<div class="input-group date" data-target-input="nearest">' +
                        '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" name="time_begin[]" value=""/>' +
                        '<div class="input-group-append" data-toggle="datetimepicker">' +
                        '<div class="input-group-text"><i class="fa-regular fa-clock"></i></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="form-group col-md-2">' +
                        '<label for="number">Hora fim</label>' +
                        '<div class="input-group date" data-target-input="nearest">' +
                        '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" name="time_end[]" value=""/>' +
                        '<div class="input-group-append" data-toggle="datetimepicker">' +
                        '<div class="input-group-text"><i class="fa-regular fa-clock"></i></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="form-group col-md-2">' +
                        '<a class="btn btn-danger btn-sm mr-1 btn-remove" style="margin-top: 35px" href="javascript:;">' +
                        '<i class="fa-solid fa-remove"></i>' +
                        ' Remover' +
                        '</a>' +
                        '</div>' +
                        '</div>'
                    );
                });

                $('body').on('mousedown', ".datetimepicker_day", function() {
                    $(this).datetimepicker({
                        timepicker: false,
                        format: 'd/m/Y',
                        mask: true
                    });
                });

                $('body').on('mousedown', ".datetimepicker_hour_begin", function() {
                    $(this).datetimepicker({
                        datepicker: false,
                        format: 'H:i',
                        mask: true,
                        onShow: function(ct) {
                            this.setOptions({
                                maxTime: $(this).val() ? $(this).val() : false
                            })
                        }
                    });
                });

                $('body').on('mousedown', ".datetimepicker_hour_end", function() {
                    $(this).datetimepicker({
                        datepicker: false,
                        format: 'H:i',
                        mask: true,
                        onShow: function(ct) {
                            this.setOptions({
                                minTime: $(this).val() ? $(this).val() : false
                            })
                        }
                    });
                });

                $('body').on('click', ".btn-remove", function() {
                    $(this).parent().parent().remove();
                });

                $('#state').on('change', function() {
                    var uf = this.value;
                    $("#city").html('');
                    $.ajax({
                        url: "{{ route('place.get_city') }}",
                        type: "GET",
                        data: {
                            uf: uf,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            console.log(result);
                            $('#city').html('<option value="">Selecione</option>');
                            $.each(result.cities, function(key, value) {
                                $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                });

                var uf = $("#state").val();
                $("#city").html('');
                $.ajax({
                    url: "{{ route('place.get_city') }}",
                    type: "GET",
                    data: {
                        uf: uf,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#city').html('<option value="">Selecione</option>');
                        city_id = $('#city_id_hidden').val();

                        $.each(result.cities, function(key, value) {
                            $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        $('#city option[value=' + city_id + ']').attr('selected', 'selected');
                    }
                });

                var category_id = $("#category").val();
                $("#area_id").html('');
                $.ajax({
                    url: "{{ route('category.getAreas') }}",
                    type: "POST",
                    data: {
                        category_id: category_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#area_id').html('<option value="">Selecione</option>');
                        area_id = $('#area_id_hidden').val();
                        $.each(result.areas, function(key, value) {
                            $("#area_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $('#area_id option[value=' + area_id + ']').attr('selected', 'selected');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
