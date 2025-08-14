<x-site-layout>
    <main id="main">
        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                </ol>
                <h2>Gerenciar evento</h2>
            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="mb-3 px-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <div class="card-body table-responsive p-0">
                    <ul id="progressbar" class="nav nav-pills">
                        <li class="nav-item active" id="account"><strong>Informações</strong></li>
                        <li class="nav-item" id="personal"><strong>Inscrições</strong></li>
                        <li class="nav-item" id="payment"><strong>Cupons</strong></li>
                        <li class="nav-item" id="confirm"><strong>Publicar</strong></li>
                    </ul>
                    <form method="POST" action="{{ route('event_home.create.step.one') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="card-body">
                            <h4>Sobre o evento</h4>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome do evento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nome do evento" value="{{ $event->name ?? old('name') }}" required>
                                <div class="invalid-feedback" id="name-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">URL do evento <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">https://www.ticketdz6.com.br/</span>
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="URL do evento" value="{{ $event->slug ?? old('slug') }}" required>
                                </div>
                                <div class="invalid-feedback d-none" id="slugHelp">Essa URL já está em uso, por favor, selecione outra.</div>
                                <div class="invalid-feedback" id="slug-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="subtitle" class="form-label">Subtítulo</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="Subtítulo" value="{{ $event->subtitle ?? old('subtitle') }}">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" required>{{ $event->description ?? old('description') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="category" class="form-label">Categoria <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-select" required>
                                        <option value="">Selecione</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if(isset($event) && $event->get_category()->id == $category->id) selected @endif>{{ $category->description }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Selecione uma categoria.</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="area_id" class="form-label">Área <span class="text-danger">*</span></label>
                                    <select name="area_id" id="area_id" class="form-select" required>
                                        <option value="">Selecione</option>
                                        <option>...</option>
                                    </select>
                                    <div class="invalid-feedback">Selecione uma área.</div>
                                </div>
                                <input type="hidden" name="area_id_hidden" id="area_id_hidden" value="{{ $event->area_id ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label for="max_tickets" class="form-label">Total máximo de vagas <span class="text-danger">*</span></label>
                                <input type="number" class="form-control w-25" id="max_tickets" name="max_tickets" value="{{ $event->max_tickets ?? old('max_tickets') }}" min="0" required>
                                <div class="invalid-feedback">Insira um número válido.</div>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Email para contato <span class="text-danger">*</span></label>
                                <input type="email" class="form-control w-50" id="contact" name="contact" placeholder="Contato" value="{{ $event->contact ?? old('contact') }}" required>
                                <div class="invalid-feedback" id="contact-error">Insira um email válido.</div>
                            </div>
                            <input type="hidden" name="admin_id" id="admin_id_hidden" value="{{ Auth::user()->id }}">
                        </div>
                        <hr>

                        <div class="card-body" id="card-date">
                            <h4>Data e hora do evento</h4>
                            @if(isset($eventDate))
                                @foreach ($eventDate as $date)
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="datetimepicker_day_{{ $loop->index }}" class="form-label">Data</label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input class="form-control datetimepicker-input datetimepicker_day" id="datetimepicker_day_{{ $loop->index }}" name="date[]" value="{{ $date['date'] }}" autocomplete="off" required>
                                                <span class="input-group-text" data-target="#datetimepicker_day_{{ $loop->index }}" data-toggle="datetimepicker">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="hidden" name="date_id[]" value="{{ $date['id'] }}">
                                            <div class="invalid-feedback">Selecione uma data.</div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="datetimepicker_hour_begin_{{ $loop->index }}" class="form-label">Hora início</label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" id="datetimepicker_hour_begin_{{ $loop->index }}" name="time_begin[]" value="{{ $date['time_begin'] }}" autocomplete="off" required>
                                                <span class="input-group-text" data-target="#datetimepicker_hour_begin_{{ $loop->index }}" data-toggle="datetimepicker">
                                                    <i class="fa-regular fa-clock"></i>
                                                </span>
                                            </div>
                                            <div class="invalid-feedback">Selecione a hora de início.</div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="datetimepicker_hour_end_{{ $loop->index }}" class="form-label">Hora fim</label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" id="datetimepicker_hour_end_{{ $loop->index }}" name="time_end[]" value="{{ $date['time_end'] }}" autocomplete="off" required>
                                                <span class="input-group-text" data-target="#datetimepicker_hour_end_{{ $loop->index }}" data-toggle="datetimepicker">
                                                    <i class="fa-regular fa-clock"></i>
                                                </span>
                                            </div>
                                            <div class="invalid-feedback">Selecione a hora de fim.</div>
                                        </div>
                                        @if ($loop->first)
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success btn-sm" id="cmd" style="margin-top: 35px;">
                                                    <i class="fa-solid fa-plus"></i> Adicionar novo
                                                </button>
                                            </div>
                                        @else
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm btn-remove" style="margin-top: 35px;">
                                                    <i class="fa-solid fa-trash"></i> Remover
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="datetimepicker_day_0" class="form-label">Data <span class="text-danger">*</span></label>
                                        <div class="input-group" data-target-input="nearest">
                                            <input class="form-control datetimepicker-input datetimepicker_day" id="datetimepicker_day_0" name="date[]" value="" autocomplete="off" required>
                                            <span class="input-group-text" data-toggle="datetimepicker">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" name="date_id[]" value="">
                                        <div class="invalid-feedback">Selecione uma data.</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="datetimepicker_hour_begin_0" class="form-label">Hora início <span class="text-danger">*</span></label>
                                        <div class="input-group" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" id="datetimepicker_hour_begin_0" name="time_begin[]" value="" autocomplete="off" required>
                                            <span class="input-group-text" data-toggle="datetimepicker">
                                                <i class="fa-regular fa-clock"></i>
                                            </span>
                                        </div>
                                        <div class="invalid-feedback">Selecione a hora de início.</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="datetimepicker_hour_end_0" class="form-label">Hora fim <span class="text-danger">*</span></label>
                                        <div class="input-group" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" id="datetimepicker_hour_end_0" name="time_end[]" value="" autocomplete="off" required>
                                            <span class="input-group-text" data-toggle="datetimepicker">
                                                <i class="fa-regular fa-clock"></i>
                                            </span>
                                        </div>
                                        <div class="invalid-feedback">Selecione a hora de fim.</div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm" id="cmd" style="margin-top: 35px;">
                                            <i class="fa-solid fa-plus"></i> Adicionar novo
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <hr>

                        <div class="card-body">
                            <h4>Endereço do evento</h4>
                            <div class="row mb-3">
                                <div class="col-md-10">
                                    <label for="place_name" class="form-label">Local <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="place_name" name="place_name" placeholder="Local" value="{{ $place->name ?? old('place_name') }}" required>
                                    <div class="form-text">Comece a digitar para buscar o local do evento, caso não o encontre, preencha os campos deste formulário.</div>
                                    <div class="invalid-feedback">Insira o nome do local.</div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="button" class="btn btn-warning btn-sm" id="add_place" style="margin-top: 6px">Limpar campos</button>
                                </div>
                            </div>
                            <div id="event_address">
                                <div class="row mb-3">
                                    <div class="col-md-10">
                                        <label for="address" class="form-label">Rua <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Rua" @if($place) readonly @endif value="{{ $place->address ?? old('address') }}" required>
                                        <div class="invalid-feedback">Insira a rua.</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="number" class="form-label">Número <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="number" name="number" placeholder="Número" @if($place) readonly @endif value="{{ $place->number ?? old('number') }}" required>
                                        <div class="invalid-feedback">Insira o número.</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="district" class="form-label">Bairro <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="district" name="district" placeholder="Bairro" @if($place) readonly @endif value="{{ $place->district ?? old('district') }}" required>
                                    <div class="invalid-feedback">Insira o bairro.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="complement" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento" @if($place) readonly @endif value="{{ $place->complement ?? old('complement') }}">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-5">
                                        <label for="state" class="form-label">Estado <span class="text-danger">*</span></label>
                                        <select id="state" class="form-select" name="state" @if($place) disabled @endif required>
                                            <option value="">Selecione</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" @if(isset($place) && $place->get_city()->uf == $state->uf) selected @endif>{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Selecione um estado.</div>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="city" class="form-label">Cidade <span class="text-danger">*</span></label>
                                        <select id="city" class="form-select" name="city_id" @if($place) disabled @endif required>
                                            <option value="">Selecione</option>
                                            <option>...</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione uma cidade.</div>
                                        <input type="hidden" name="city_id_hidden" id="city_id_hidden" value="{{ $place->city_id ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="zip" class="form-label">CEP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="zip" name="zip" placeholder="CEP" @if($place) readonly @endif value="{{ $place->zip ?? old('zip') }}" required>
                                        <div class="invalid-feedback">Insira o CEP.</div>
                                    </div>
                                    <input type="hidden" name="place_id_hidden" id="place_id_hidden" value="{{ $place->id ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="card-body">
                            <h4>Campos do formulário de inscrição</h4>
                            <div class="card p-2 mb-3">
                                <label for="question" class="form-label">Novo campo</label>
                                <div class="row g-0">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" id="question" name="question" placeholder="Nome do campo">
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <select id="option" class="form-select" name="option" style="margin-left: 10px" required>
                                                    @foreach ($options as $option)
                                                        <option value="{{ $option->id }}">{{ $option->option }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6" style="margin-top: 5px">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="required" id="required" value="1">
                                                    <label class="form-check-label" for="required"><b>Obrigatório</b></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="unique" id="unique" value="1">
                                                    <label class="form-check-label" for="unique"><b>Único</b></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 d-none" id="div_new_options">
                                                <label for="new_options" class="form-label">Preencha as opções separadas por vírgula</label>
                                                <input type="text" class="form-control" name="new_options" id="new_options" placeholder="XXX, YYY, ZZZ">
                                            </div>
                                            <div class="col-md-12 d-none" id="div_new_number">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="val_min" class="form-label">Mínimo</label>
                                                        <input type="number" class="form-control" name="val_min" min="0">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="val_max" class="form-label">Máximo</label>
                                                        <input type="number" class="form-control" name="val_max" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-success" id="add_new_field">Adicionar</button>
                                    </div>
                                </div>
                            </div>
                            <div id="card-new-field">
                                @if($questions != "")
                                    @if(isset($questions))
                                        @foreach ($questions as $id => $question)
                                            @if($id < 2)
                                                <div class="mb-3">
                                                    <label for="new_field_{{ $id }}" class="form-label">Campo {{ $id + 1 }}{{ $question->required == 1 ? '*' : '' }}</label>
                                                    <input type="text" class="form-control" name="new_field[]" value="{{ $question->formatted_options }}" readonly>
                                                    <input type="hidden" name="new_field_id[]" value="{{ $question->id }}">
                                                </div>
                                            @else
                                                <div class="row mb-3">
                                                    <div class="col-9">
                                                        <label for="new_field_{{ $id }}" class="form-label">Campo {{ $id + 1 }}{{ $question->required ? '*' : '' }}</label>
                                                        <input type="text" class="form-control new_field" name="new_field[]" value="{{ $question->formatted_options }}" readonly>
                                                        <input type="hidden" name="new_field_id[]" value="{{ $question->id }}">
                                                    </div>
                                                    <div class="col-3 mt-4">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-field me-1">
                                                            <i class="fa-solid fa-trash"></i> Remover
                                                        </button>
                                                        <button type="button" class="btn btn-secondary btn-sm up me-1">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-secondary btn-sm down">
                                                            <i class="fas fa-arrow-down"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="mb-3">
                                            <label for="name_new_field" class="form-label">Campo 1*</label>
                                            <input type="text" class="form-control new_field" name="new_field[]" id="name_new_field" value="Nome; (Tipo: Texto (Até 200 caracteres)); Obrigatório" readonly>
                                            <input type="hidden" name="new_field_id[]" value="">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email_new_field" class="form-label">Campo 2*</label>
                                            <input type="text" class="form-control new_field" name="new_field[]" id="email_new_field" value="E-mail; (Tipo: E-mail); Obrigatório; Único" readonly>
                                            <input type="hidden" name="new_field_id[]" value="">
                                        </div>
                                    @endif
                                @else
                                    <div class="mb-3">
                                        <label for="name_new_field" class="form-label">Campo 1*</label>
                                        <input type="text" class="form-control new_field" name="new_field[]" id="name_new_field" value="Nome; (Tipo: Texto (Até 200 caracteres)); Obrigatório" readonly>
                                        <input type="hidden" name="new_field_id[]" value="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email_new_field" class="form-label">Campo 2*</label>
                                        <input type="text" class="form-control new_field" name="new_field[]" id="email_new_field" value="E-mail; (Tipo: E-mail); Obrigatório; Único" readonly>
                                        <input type="hidden" name="new_field_id[]" value="">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr>

                        <div class="card-body">
                            <h4>Carteira de pagamento</h4>
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="paid" id="inlineRadio_mercadopago" value="0">
                                    <label class="form-check-label" for="inlineRadio_mercadopago">Mercado Pago <a href="https://www.mercadopago.com.br/" target="_blank"><i class="fa-solid fa-up-right-from-square"></i></a></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="paid" id="inlineRadio_nenhuma" value="1">
                                    <label class="form-check-label" for="inlineRadio_nenhuma">Nenhuma (Evento gratuito)</label>
                                </div>
                            </div>

                            @if(isset($mercadoPagoLinked['linked']) && $mercadoPagoLinked['linked'])
                                <div class="mb-3 d-none" id="form_mercadopago">
                                    <label for="contact" id="linked-acc-label" class="form-label">ID da Conta Vinculada: {{ $mercadoPagoLinked['id'] }}</label>
                                    <br>
                                    <a href="https://auth.mercadopago.com.br/authorization?client_id={{ env('MERCADO_PAGO_APP_ID', '') }}&response_type=code&platform_id=mp&redirect_uri=" target="_blank" id="link-acc-button" data-linked="true" class="btn btn-secondary">Vincular outra conta</a>
                                </div>
                            @else
                                <div class="mb-3 d-none" id="form_mercadopago">
                                    <label for="contact" id="linked-acc-label" class="form-label">Vincular conta Mercado Pago</label>
                                    <br>
                                    <a href="https://auth.mercadopago.com.br/authorization?client_id={{ env('MERCADO_PAGO_APP_ID', '') }}&response_type=code&platform_id=mp&redirect_uri={{ env('MERCADO_PAGO_REDIRECT_URI', '') }}" target="_blank" id="link-acc-button" data-linked="false" class="btn btn-success">Vincular conta</a>
                                </div>
                            @endif
                            <input type="hidden" name="mercadopago_link" id="mercadopago_link" value="{{ $mercadopago_link ?? '' }}">
                        </div>

                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Próximo</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
        <link href="{{ asset('assets_admin/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    @endpush

    @push('footer')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
        <script src="{{ asset('assets_admin/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function() {
                // Form validation
                $('form.needs-validation').on('submit', function(e) {
                    if (!this.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                        showToast('Por favor, corrija os erros no formulário.', 'error');
                        $(this).addClass('was-validated');
                        $('button[type="submit"]').prop('disabled', false).text('Próximo');
                        return false;
                    }
                    $(this).addClass('was-validated');
                    $('button[type="submit"]').prop('disabled', true).text('Salvando...');
                });

                $('#description').summernote({
                    placeholder: 'Descreva em detalhes o evento',
                    tabsize: 2,
                    height: 200,
                    codemirror: { theme: 'monokai' },
                    toolbar: [
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['help']]
                    ]
                });

                function debounce(func, wait) {
                    let timeout;
                    return function executedFunction(...args) {
                        const context = this; // Capture the context (this)
                        const later = () => {
                            clearTimeout(timeout);
                            func.apply(context, args); // Apply the correct context
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                }

                // Name validation
                $('#name').on('blur keyup', debounce(function(e) {
                    const name = $(this).val()?.trim() || ''; // Use optional chaining or fallback to empty string
                    const $nameField = $(this);
                    const $nameError = $('#name-error');

                    if (name.length < 3) {
                        $nameField.addClass('is-invalid');
                        $nameError.text('Nome deve ter pelo menos 3 caracteres');
                        return;
                    }

                    if (name.length > 3) {
                        $.get('{{ route('event_home.check_slug') }}', 
                            { 'title': name }, 
                            function(data) {
                                $('#slug').val(data.slug);
                                if (data.slug_exists == '1') {
                                    $('#slug').addClass('is-invalid').removeClass('is-valid');
                                    $('#slugHelp').removeClass('d-none');
                                    showToast('Este slug já está em uso. Escolha outro.', 'warning');
                                } else {
                                    $('#slug').addClass('is-valid').removeClass('is-invalid');
                                    $('#slugHelp').addClass('d-none');
                                    showToast('Slug disponível!', 'success', null, 2000);
                                }
                            }
                        ).fail(function() {
                            showToast('Erro ao verificar slug. Tente novamente.', 'error');
                        });
                    }

                    $nameField.removeClass('is-invalid');
                    $nameError.text('');
                }, 500));

                // Slug validation
                $('#slug').on('blur keyup', debounce(function() {
                    const slug = $(this).val().trim();
                    const $slugField = $(this);
                    const $slugError = $('#slug-error');

                    if (slug.length < 2) {
                        $slugField.addClass('is-invalid');
                        $slugError.text('URL deve ter pelo menos 2 caracteres');
                        return;
                    }

                    if (slug.length > 2) {
                        $.get('{{ route('event_home.create_slug') }}', { 'title': slug }, function(data) {
                            if (data.slug_exists == '1') {
                                $slugField.addClass('is-invalid').removeClass('is-valid');
                                $slugError.text('Esta URL já está em uso');
                                showToast('Este slug já está em uso. Escolha outro.', 'warning');
                            } else {
                                $slugField.addClass('is-valid').removeClass('is-invalid');
                                $slugError.text('');
                                showToast('Slug disponível!', 'success', null, 2000);
                            }
                        }).fail(function() {
                            showToast('Erro ao verificar slug. Tente novamente.', 'error');
                        });
                    }
                }, 500));

                $('#category').on('change', function() {
                    try {
                        const category_id = this.value;
                        if (category_id !== undefined && category_id !== null) {
                            $("#area_id").html('');

                            if (category_id) {
                                $.ajax({
                                    url: "{{ route('event_home.get_areas_by_category') }}",
                                    type: "POST",
                                    data: { category_id: category_id, _token: '{{ csrf_token() }}' },
                                    dataType: 'json',
                                    success: function(result) {
                                        $('#area_id').html('<option value="">Selecione</option>');
                                        $.each(result.areas, function(key, value) {
                                            $("#area_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                                        });
                                        if (result.areas.length > 0) {
                                            showToast(`${result.areas.length} área(s) encontrada(s)`, 'info', null, 2000);
                                        } else {
                                            showToast('Nenhuma área encontrada para esta categoria', 'warning');
                                        }
                                    },
                                    error: function() {
                                        showToast('Erro ao carregar áreas. Tente novamente.', 'error');
                                    }
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Erro ao processar mudança de categoria:', error);
                    }
                });

                $('input[name="paid"]').on('change', function() {
                    try {
                        const value = $(this).val();
                        if (value !== undefined && value !== null) {
                            if (value == 0) {
                                $('#form_mercadopago').removeClass('d-none');
                            } else {
                                $('#form_mercadopago').addClass('d-none');
                            }
                        }
                    } catch (error) {
                        console.error('Erro ao processar mudança de pagamento:', error);
                    }
                });

                // Verificar se o elemento category existe antes de tentar acessar seu valor
                if ($("#category").length > 0) {
                    const category_id = $("#category").val();
                    $("#area_id").html('');
                    $.ajax({
                        url: "{{ route('event_home.get_areas_by_category') }}",
                        type: "POST",
                        data: { category_id: category_id, _token: '{{ csrf_token() }}' },
                        dataType: 'json',
                        success: function(result) {
                            $('#area_id').html('<option value="">Selecione</option>');
                            const area_id = $('#area_id_hidden').val();
                            $.each(result.areas, function(key, value) {
                                $("#area_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            if (area_id) {
                                $('#area_id option[value=' + area_id + ']').attr('selected', 'selected');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Erro ao carregar áreas:', error);
                        }
                    });
                }

                $('#cmd').click(function() {
                    $('#card-date').append(`
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Data</label>
                                <div class="input-group" data-target-input="nearest">
                                    <input class="form-control datetimepicker-input datetimepicker_day" autocomplete="off" name="date[]" value=""/>
                                    <span class="input-group-text" data-toggle="datetimepicker">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Hora início</label>
                                <div class="input-group" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" autocomplete="off" name="time_begin[]" value=""/>
                                    <span class="input-group-text" data-toggle="datetimepicker">
                                        <i class="fa-regular fa-clock"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Hora fim</label>
                                <div class="input-group" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" autocomplete="off" name="time_end[]" value=""/>
                                    <span class="input-group-text" data-toggle="datetimepicker">
                                        <i class="fa-regular fa-clock"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm btn-remove" style="margin-top: 35px;">
                                    <i class="fa-solid fa-trash"></i> Remover
                                </button>
                            </div>
                        </div>
                    `);
                });

                $('#option').change(function() {
                    try {
                        const id_option_select = $(this).val();
                        if (id_option_select !== undefined && id_option_select !== null) {
                            if ([2, 3, 4, 14].includes(parseInt(id_option_select))) {
                                $('#div_new_options').removeClass('d-none');
                                if (id_option_select == 14) {
                                    $('#new_options').val('AC, AL, AP, AM, BA, CE, DF, ES, GO, MA, MT, MS, MG, PA, PB, PR, PE, PI, RJ, RN, RS, RO, RR, SC, SP, SE, TO');
                                }
                            } else {
                                $('#div_new_options').addClass('d-none');
                                if (id_option_select == 14) {
                                    $('#new_options').val('');
                                }
                            }

                            if ([9, 10].includes(parseInt(id_option_select))) {
                                $('#div_new_number').removeClass('d-none');
                            } else {
                                $('#div_new_number').addClass('d-none');
                            }
                        }
                    } catch (error) {
                        console.error('Erro ao processar mudança de opção:', error);
                    }
                });

                let i_field = parseInt($('input.new_field').length);
                $('#add_new_field').click(function() {
                    const field = $(this).parent().parent().find('#question').val();
                    const option = $(this).parent().parent().find('#option').val();
                    const option_text = $(this).parent().parent().find('#option:selected').text();
                    const required = $(this).parent().parent().find('#required').is(":checked");
                    const unique = $(this).parent().parent().find('#unique').is(":checked");

                    if (field === '') {
                        alert('Por favor, preencha o nome do campo!');
                        return false;
                    }

                    const required_star = required ? '*' : '';
                    let field_text = '';
                    let field_name = '';
                    let field_required = required ? '; Obrigatório' : '';
                    let field_unique = unique ? '; Único' : '';
                    let field_options = '';

                    i_field++;

                    $('#question').val('');
                    $('#option').prop('selectedIndex', 0);
                    $('#required').prop('checked', false);
                    $('#unique').prop('checked', false);

                    switch (option) {
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
                            field_options = '; [Opções: ' + $('.val_min_option').val() + '|' + $('.val_max_option').val() + ']';
                            break;
                        case '10':
                            field_text = '(Tipo: Número decimal)';
                            field_name = 'decimal';
                            field_options = '; [Opções: ' + $('.val_min_option').val() + '|' + $('.val_max_option').val() + ']';
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

                    // Create new field row with proper structure
                    const newField = $(`
                        <div class="row mb-3">
                            <div class="col-9">
                                <label class="form-label">Campo ${i_field}${required_star}</label>
                                <input type="text" class="form-control new_field" name="new_field[]" value="${field}; ${field_text}${field_options}${field_required}${field_unique}" readonly>
                                <input type="hidden" name="new_field_id[]" value="">
                            </div>
                            <div class="col-3 d-flex align-items-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-field me-1" title="Remover">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm up me-1" title="Mover para cima">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm down" title="Mover para baixo">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);

                    $('#card-new-field').append(newField);
                    updateFieldNumbers();
                    $('#new_options').val('');
                    $('.val_min_option').val('');
                    $('.val_max_option').val('');
                });

                // Initialize field numbers and button visibility
                updateFieldNumbers();

                // Handle field removal with event delegation
                $(document).on('click', '.btn-remove-field', function() {
                    try {
                        $(this).closest('.row.mb-3').remove();
                        updateFieldNumbers();
                    } catch (error) {
                        console.error('Erro ao remover campo:', error);
                    }
                });

                // Handle field reordering with event delegation
                $(document).on('click', '.up, .down', function(e) {
                    try {
                        e.preventDefault();
                        const $row = $(this).closest('.row.mb-3');
                        
                        if ($(this).hasClass('up')) {
                            $row.insertBefore($row.prev());
                        } else {
                            $row.insertAfter($row.next());
                        }
                        
                        updateFieldNumbers();
                    } catch (error) {
                        console.error('Erro ao mover campo:', error);
                    }
                });

                // Update field numbers and button visibility
                function updateFieldNumbers() {
                    $('.row.mb-3').each(function(index) {
                        const fieldNumber = index + 1;
                        const $label = $(this).find('.form-label');
                        const labelText = $label.text().replace(/Campo \d+/, 'Campo ' + fieldNumber);
                        $label.text(labelText);
                        
                        // Update buttons visibility
                        $(this).find('.up').toggle(index > 0);
                        $(this).find('.down').toggle(index < $('.row.mb-3').length - 1);
                    });
                }

                $('#add_place').click(function() {
                    $('#place_name').val('');
                    $('#address').val('').prop("readonly", false);
                    $('#number').val('').prop("readonly", false);
                    $('#district').val('').prop("readonly", false);
                    $('#complement').val('').prop("readonly", false);
                    $('#zip').val('').prop("readonly", false);
                    $('#state').prop("disabled", false).prop('selectedIndex', 0);
                    $('#city').prop("disabled", false).prop('selectedIndex', 0);
                    $('#city_id_hidden').val('');
                });

                const path = "{{ route('event_home.autocomplete_place') }}";
                $("#place_name").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: path,
                            type: 'GET',
                            dataType: "json",
                            data: { search: request.term },
                            success: function(data) {
                                response(data);
                                if (data.length > 0) {
                                    showToast(`${data.length} local(is) encontrado(s)`, 'info', null, 1500);
                                } else if (request.term.length > 2) {
                                    showToast('Nenhum local encontrado', 'warning', null, 2000);
                                }
                            },
                            error: function() {
                                showToast('Erro ao buscar locais', 'error');
                            }
                        });
                    },
                    select: function(event, ui) {
                        $('#place_name').val(ui.item.label);
                        $('#place_id_hidden').val(ui.item.id);
                        $('#address').val(ui.item.address).prop("readonly", true);
                        $('#number').val(ui.item.number).prop("readonly", true);
                        $('#district').val(ui.item.district).prop("readonly", true);
                        $('#complement').val(ui.item.complement).prop("readonly", true);
                        $('#zip').val(ui.item.zip).prop("readonly", true);
                        $('#state option[value="' + ui.item.uf + '"]').prop("selected", true);
                        $('#state').prop("disabled", true);
                        $('#city').prop("disabled", true);

                        const uf = $("#state").val();
                        $("#city").html('');
                        $.ajax({
                            url: "{{ route('event_home.get_city') }}",
                            type: "POST",
                            data: { uf: uf, _token: '{{ csrf_token() }}' },
                            dataType: 'json',
                            success: function(result) {
                                $('#city').html('<option value="">Selecione</option>');
                                $.each(result.cities, function(key, value) {
                                    $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                                if (ui.item.city_id) {
                                    $('#city option[value="' + ui.item.city_id + '"]').prop("selected", true);
                                    $('#city_id_hidden').val(ui.item.city_id);
                                }
                            }
                        });
                        return false;
                    }
                });

                $('#state').on('change', function() {
                    try {
                        const uf = this.value;
                        if (uf !== undefined && uf !== null) {
                            $("#city").html('');
                            if (uf) {
                                $.ajax({
                                    url: "{{ route('event_home.get_city') }}",
                                    type: "POST",
                                    data: { uf: uf, _token: '{{ csrf_token() }}' },
                                    dataType: 'json',
                                    success: function(result) {
                                        $('#city').html('<option value="">Selecione</option>');
                                        $.each(result.cities, function(key, value) {
                                            $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                                        });
                                        if (result.cities.length > 0) {
                                            showToast(`${result.cities.length} cidade(s) encontrada(s)`, 'info', null, 2000);
                                        } else {
                                            showToast('Nenhuma cidade encontrada para este estado', 'warning');
                                        }
                                    },
                                    error: function() {
                                        showToast('Erro ao carregar cidades. Tente novamente.', 'error');
                                    }
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Erro ao processar mudança de estado:', error);
                    }
                });

                // Verificar se o elemento state existe antes de tentar acessar seu valor
                if ($("#state").length > 0) {
                    const uf = $("#state").val();
                    if (uf !== undefined && uf !== null) {
                        $("#city").html('');
                        $.ajax({
                            url: "{{ route('event_home.get_city') }}",
                            type: "POST",
                            data: { uf: uf, _token: '{{ csrf_token() }}' },
                            dataType: 'json',
                            success: function(result) {
                                $('#city').html('<option value="">Selecione</option>');
                                const city_id = $('#city_id_hidden').val();
                                $.each(result.cities, function(key, value) {
                                    $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                                if (city_id) {
                                    $('#city option[value=' + city_id + ']').attr('selected', 'selected');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Erro ao carregar cidades:', error);
                            }
                        });
                    }
                }

                $('#city').change(function() {
                    try {
                        const city_id = $('#city').val();
                        if (city_id !== undefined && city_id !== null) {
                            $('#city_id_hidden').val(city_id);
                        }
                    } catch (error) {
                        console.error('Erro ao processar mudança de cidade:', error);
                    }
                });

                $('body').on('click', ".btn-remove-field", function() {
                    $(this).parent().parent().remove();
                    i_field--;
                    $("#card-new-field .up:first").addClass('d-none');
                    $("#card-new-field .down:last").addClass('d-none');
                });

                $('body').on('click', ".btn-remove", function() {
                    $(this).parent().parent().remove();
                });

                $('body').on('mousedown', ".datetimepicker_day", function() {
                    $(this).datetimepicker({
                        timepicker: false,
                        format: 'd/m/Y',
                        mask: true,
                        minDate: new Date(),
                        lang: 'pt-BR',
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
                            });
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
                            });
                        }
                    });
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const radioButtons = document.querySelectorAll('input[name="paid"]');
                const formMercadoPago = document.getElementById('form_mercadopago');
                const linkAccButton = document.getElementById('link-acc-button');
                const linkedAccLabel = document.getElementById('linked-acc-label');

                radioButtons.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === "0") {
                            formMercadoPago.classList.remove('d-none');
                            if (linkAccButton.getAttribute('data-linked') === 'false') {
                                const intervalId = setInterval(() => {
                                    fetch('/webhooks/mercado-pago/check-linked-account')
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.linked) {
                                                clearInterval(intervalId);
                                                linkedAccLabel.textContent = 'ID da Conta Vinculada: ' + data.id;
                                                linkAccButton.className = 'btn btn-secondary';
                                                linkAccButton.textContent = 'Vincular outra conta';
                                                linkAccButton.setAttribute('data-linked', 'true');
                                            } else {
                                                console.log('Nenhuma conta vinculada');
                                            }
                                        })
                                        .catch(error => console.error('Erro:', error));
                                }, 5000);
                            }
                        } else {
                            formMercadoPago.classList.add('d-none');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-site-layout>