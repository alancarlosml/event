<x-site-layout>
    <main id="main">
        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/painel/meus-eventos">Meus eventos</a></li>
                    @if(isset($event) && $event->id)
                    <li class="breadcrumb-item active">Editar evento</li>
                    @else
                    <li class="breadcrumb-item active">Novo evento</li>
                    @endif
                </ol>
                <h2>@if(isset($event) && $event->id) Editar @else Criar @endif evento</h2>
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
                    @php
                        $isEdit = isset($event) && $event->id;
                        $formAction = $isEdit ? route('event_home.my_events_edit.update', $event->hash) : route('event_home.create.step.one');
                        $formMethod = $isEdit ? 'PUT' : 'POST';
                    @endphp
                    <form method="POST" action="{{ $formAction }}" class="needs-validation" novalidate>
                        @if($isEdit)
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="card-body">
                            <h4>Sobre o evento</h4>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome do evento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nome do evento" value="{{ $event->name ?? old('name') }}" required>
                                <div class="invalid-feedback" id="name-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">URL personalizada <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ config('app.url') }}</span>
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="url-personalizada" value="{{ $event->slug ?? old('slug') }}" {{ $isEdit ? 'readonly' : 'required' }}>
                                </div>
                                @if($isEdit)
                                    <small class="form-text text-muted">A URL não pode ser alterada após a criação do evento.</small>
                                @endif
                                <div class="invalid-feedback" id="slug-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="subtitle" class="form-label">Subtítulo</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="Subtítulo do evento (opcional)" value="{{ $event->subtitle ?? old('subtitle') }}">
                            </div>
                            @if($isEdit && $event->banner)
                            <div class="mb-3">
                                <label class="form-label">Banner atual</label>
                                <div>
                                    <img src="{{ asset('storage/' . $event->banner) }}" alt="Banner do evento" class="img-fluid mb-2" style="max-height: 200px;">
                                </div>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label for="banner" class="form-label">{{ $isEdit ? 'Alterar banner' : 'Banner do evento' }}</label>
                                <input class="form-control" type="file" id="banner" name="banner" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição do evento <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" required>{{ $event->description ?? old('description') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">E-mail de contato <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="contact" name="contact" placeholder="contato@exemplo.com" value="{{ $event->contact ?? old('contact') }}" required>
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
                            @php
                                // Para compatibilidade com o formato antigo
                                $eventDates = $dates ?? ($eventDate ?? []);
                            @endphp
                            @if(count($eventDates) > 0)
                                @foreach ($eventDates as $index => $date)
                                    @php
                                        $rawDate = is_object($date) ? ($date->date ?? null) : ($date['date'] ?? null);
                                        if ($rawDate instanceof \Carbon\Carbon) {
                                            $dateValue = $rawDate->format('d/m/Y');
                                        } else {
                                            $dateValue = is_string($rawDate) ? $rawDate : '';
                                        }
                                        $timeBegin = is_object($date) ? ($date->time_begin ?? '') : ($date['time_begin'] ?? '');
                                        $timeEnd = is_object($date) ? ($date->time_end ?? '') : ($date['time_end'] ?? '');
                                        $dateId = is_object($date) ? ($date->id ?? '') : ($date['id'] ?? '');
                                    @endphp
                                    <div class="row mb-3 g-3" data-date-index="{{ $index }}">
                                        <input type="hidden" name="date_id[]" value="{{ $dateId }}">
                                        <div class="col-md-3 pe-3">
                                            <label for="datetimepicker_day_{{$index}}" class="form-label">Data <span class="text-danger">*</span></label>
                                            <div class="input-group date" id="datetimepicker_day_{{$index}}" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                                <input type="text" class="form-control datetimepicker-input datetimepicker_day" name="date[]" value="{{ $dateValue }}" autocomplete="off" required>
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 pe-3">
                                            <label for="datetimepicker_begin_{{$index}}" class="form-label">Hora início <span class="text-danger">*</span></label>
                                            <div class="input-group date" id="datetimepicker_begin_{{$index}}" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                                <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" name="time_begin[]" value="{{ $timeBegin }}" autocomplete="off" required>
                                                <span class="input-group-text">
                                                    <i class="fas fa-clock"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 pe-3">
                                            <label for="datetimepicker_end_{{$index}}" class="form-label">Hora fim <span class="text-danger">*</span></label>
                                            <div class="input-group date" id="datetimepicker_end_{{$index}}" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                                <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" name="time_end[]" value="{{ $timeEnd }}" autocomplete="off" required>
                                                <span class="input-group-text">
                                                    <i class="fas fa-clock"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            @if($index === 0)
                                                <button type="button" class="btn btn-primary d-block" id="add-date">
                                                    <i class="fas fa-plus"></i> Adicionar
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-danger d-block remove-date">
                                                    <i class="fas fa-trash"></i> Remover
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row mb-3 g-3" data-date-index="0">
                                    <input type="hidden" name="date_id[]" value="">
                                    <div class="col-md-3 pe-3">
                                        <label for="datetimepicker_day_0" class="form-label">Data <span class="text-danger">*</span></label>
                                        <div class="input-group date" id="datetimepicker_day_0" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_day" name="date[]" autocomplete="off" required>
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 pe-3">
                                        <label for="datetimepicker_begin_0" class="form-label">Hora início <span class="text-danger">*</span></label>
                                        <div class="input-group date" id="datetimepicker_begin_0" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" name="time_begin[]" autocomplete="off" required>
                                            <span class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 pe-3">
                                        <label for="datetimepicker_end_0" class="form-label">Hora fim <span class="text-danger">*</span></label>
                                        <div class="input-group date" id="datetimepicker_end_0" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" name="time_end[]" autocomplete="off" required>
                                            <span class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-primary d-block" id="add-date">
                                            <i class="fas fa-plus"></i> Adicionar
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <hr>

                        <div class="card-body">
                            <h4>Endereço do evento</h4>
                            <div class="row mb-3 g-3">
                                <div class="col-md-10 pe-3">
                                    <label for="place_name" class="form-label">Local <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="place_name" name="place_name" placeholder="Local" value="{{ $event->place->name ?? old('place_name') }}" required>
                                    <small id="place_nameHelp" class="form-text text-muted">Comece a digitar para buscar o local do evento, caso não o encontre, preencha os campos deste formulário.</small>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label><br>
                                    <a class="btn btn-warning btn-sm" id="add_place" href="javascript:;">
                                        Limpar campos
                                    </a>
                                </div>  
                            </div>
                            <div id="event_address">
                                <div class="row mb-3 g-3">
                                    <div class="col-md-10 pe-3">
                                        <label for="address" class="form-label">Rua <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Rua" @if(isset($event->place)) readonly @endif value="{{ $event->place->address ?? old('address') }}" required>
                                    </div>
                                    <div class="col-md-2 pe-3">
                                        <label for="number" class="form-label">Número <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="number" name="number" placeholder="Número" @if(isset($event->place)) readonly @endif value="{{ $event->place->number ?? old('number') }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3 g-3">
                                    <div class="col-md-6 pe-3">
                                        <label for="district" class="form-label">Bairro <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="district" name="district" placeholder="Bairro" @if(isset($event->place)) readonly @endif value="{{ $event->place->district ?? old('district') }}" required>
                                    </div>
                                    <div class="col-md-6 pe-3">
                                        <label for="complement" class="form-label">Complemento</label>
                                        <input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento" @if(isset($event->place)) readonly @endif value="{{ $event->place->complement ?? old('complement') }}">
                                    </div>
                                </div>
                                <div class="row mb-3 g-3">
                                    <div class="col-md-5 pe-3">
                                        <label for="state" class="form-label">Estado <span class="text-danger">*</span></label>
                                        <select id="state" class="form-select" name="state" @if(isset($event->place)) disabled @endif required>
                                            <option value="">Selecione</option>
                                            @foreach ($states as $state)
                                                <option value="{{$state->id}}"
                                                    @if(isset($event->place) && optional($event->place->get_city)->uf == $state->id)
                                                        selected
                                                    @endif>
                                                    {{$state->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5 pe-3">
                                        <label for="city" class="form-label">Cidade <span class="text-danger">*</span></label>
                                        <select id="city" class="form-select" name="city_id" @if(isset($event->place)) disabled @endif required>
                                            <option value="">Selecione</option>
                                            @if(isset($event->place) && $event->place->get_city)
                                                <option value="{{ $event->place->city_id }}" selected>
                                                    {{ optional($event->place->get_city)->name }}
                                                </option>
                                            @endif
                                        </select>
                                        <input type="hidden" name="city_id_hidden" id="city_id_hidden" value="{{ $event->place->city_id ?? '' }}">
                                    </div>
                                    <div class="col-md-2 pe-3">
                                        <label for="zip" class="form-label">CEP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="zip" name="zip" placeholder="CEP" @if(isset($event->place)) readonly @endif value="{{ $event->place->zip ?? old('zip') }}" required>
                                    </div>
                                    <input type="hidden" name="place_id_hidden" id="place_id_hidden" value="{{ $event->place->id ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="card-body">
                            <h4>Campos do formulário de inscrição</h4>
                            <div class="card p-2 mb-3">
                                <label for="question" class="form-label">Novo campo</label>
                                <div class="row g-0">
                                    <div class="col-md-5" style="margin-right: 10px; margin-bottom: 10px">
                                        <input type="text" class="form-control" id="question" name="question" placeholder="Nome do campo">
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <select id="option" class="form-select" name="option" required>
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
                                                    <input type="text" class="form-control new_field" name="new_field[]" value="{{ $question->formatted_options }}" readonly>
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
                    // Utility function for debouncing
                    function debounce(func, wait) {
                        let timeout;
                        return function(...args) {
                            const context = this;
                            clearTimeout(timeout);
                            timeout = setTimeout(() => func.apply(context, args), wait);
                        };
                    }

                    // Utility function to show toast notifications
                    function showToast(message, type, title = null, timeout = 3000) {
                        // Implementation of toast (assuming a toast library is used, e.g., Bootstrap toast)
                        console.log(`[${type}] ${title || ''}: ${message}`);
                        // Add actual toast implementation if needed
                    }

                    // Initialize Summernote editor
                    function initSummernote() {
                        try {
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
                        } catch (error) {
                            console.error('Erro ao inicializar Summernote:', error);
                        }
                    }

                    // Initialize form validation
                    function initFormValidation() {
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
                    }

                    // Initialize datetimepickers
                    function initDateTimePickers() {
                        $(document).on('focus', '.datetimepicker_day', function() {
                            $(this).datetimepicker({
                                timepicker: false,
                                format: 'd/m/Y',
                                mask: true,
                                minDate: new Date(),
                                lang: 'pt-BR'
                            });
                        });

                        $(document).on('focus', '.datetimepicker_hour_begin', function() {
                            $(this).datetimepicker({
                                datepicker: false,
                                format: 'H:i',
                                mask: true
                            });
                        });

                        $(document).on('focus', '.datetimepicker_hour_end', function() {
                            $(this).datetimepicker({
                                datepicker: false,
                                format: 'H:i',
                                mask: true
                            });
                        });
                    }

                    // Initialize name validation
                    function initNameValidation() {
                        $('#name').on('blur keyup', debounce(function() {
                            const name = $(this).val()?.trim() || '';
                            const $nameField = $(this);
                            const $nameError = $('#name-error');

                            if (name.length < 3) {
                                $nameField.addClass('is-invalid').removeClass('is-valid');
                                $nameError.text('Nome deve ter pelo menos 3 caracteres');
                                return;
                            }

                            $.get('{{ route('event_home.check_slug') }}', { title: name })
                                .done(data => {
                                    $('#slug').val(data.slug);
                                    if (data.slug_exists == '1') {
                                        $('#slug').addClass('is-invalid').removeClass('is-valid');
                                        showToast('Este slug já está em uso. Escolha outro.', 'warning');
                                    } else {
                                        $('#slug').addClass('is-valid').removeClass('is-invalid');
                                        showToast('Slug disponível!', 'success', null, 2000);
                                    }
                                })
                                .fail(() => showToast('Erro ao verificar slug. Tente novamente.', 'error'));

                            $nameField.removeClass('is-invalid');
                            $nameError.text('');
                        }, 500));
                    }

                    // Initialize slug validation
                    function initSlugValidation() {
                        $('#slug').on('blur keyup', debounce(function() {
                            const slug = $(this).val().trim();
                            const $slugField = $(this);
                            const $slugError = $('#slug-error');

                            if (slug.length < 2) {
                                $slugField.addClass('is-invalid').removeClass('is-valid');
                                $slugError.text('URL deve ter pelo menos 2 caracteres');
                                return;
                            }

                            $.get('{{ route('event_home.create_slug') }}', { title: slug })
                                .done(data => {
                                    if (data.slug_exists == '1') {
                                        $slugField.addClass('is-invalid').removeClass('is-valid');
                                        $slugError.text('Esta URL já está em uso');
                                        showToast('Este slug já está em uso. Escolha outro.', 'warning');
                                    } else {
                                        $slugField.addClass('is-valid').removeClass('is-invalid');
                                        $slugError.text('');
                                        showToast('Slug disponível!', 'success', null, 2000);
                                    }
                                })
                                .fail(() => showToast('Erro ao verificar slug. Tente novamente.', 'error'));
                        }, 500));
                    }

                    // Initialize category and area handling
                    function initCategoryArea() {
                        function loadAreas(categoryId) {
                            if (!categoryId) return;
                            $.ajax({
                                url: "{{ route('event_home.get_areas_by_category') }}",
                                type: "POST",
                                data: { category_id: categoryId, _token: '{{ csrf_token() }}' },
                                dataType: 'json',
                                success: function(result) {
                                    const $areaSelect = $('#area_id');
                                    $areaSelect.html('<option value="">Selecione</option>');
                                    $.each(result.areas, function(key, value) {
                                        $areaSelect.append(`<option value="${value.id}">${value.name}</option>`);
                                    });
                                    const areaId = $('#area_id_hidden').val();
                                    if (areaId) $areaSelect.val(areaId);
                                    showToast(`${result.areas.length} área(s) encontrada(s)`, 'info', null, 2000);
                                },
                                error: () => showToast('Erro ao carregar áreas. Tente novamente.', 'error')
                            });
                        }

                        $('#category').on('change', function() {
                            loadAreas(this.value);
                        });

                        // Load initial areas if category is selected
                        if ($('#category').val()) {
                            loadAreas($('#category').val());
                        }
                    }

                    // Initialize state and city handling
                    function initStateCity() {
                        function loadCities(uf, callback = null) {
                            if (!uf) {
                                $('#city').html('<option value="">Selecione um estado primeiro</option>');
                                if (callback) callback();
                                return;
                            }
                            $.ajax({
                                url: "{{ route('event_home.get_city') }}",
                                type: "POST",
                                data: { uf: uf, _token: '{{ csrf_token() }}' },
                                dataType: 'json',
                                success: function(result) {
                                    const $citySelect = $('#city');
                                    $citySelect.html('<option value="">Selecione a cidade</option>');
                                    $.each(result.cities, function(key, value) {
                                        $citySelect.append(`<option value="${value.id}">${value.name}</option>`);
                                    });
                                    showToast(`${result.cities.length} cidade(s) encontrada(s)`, 'info', null, 2000);
                                    if (callback) callback();
                                },
                                error: () => {
                                    showToast('Erro ao carregar cidades. Tente novamente.', 'error');
                                    if (callback) callback();
                                }
                            });
                        }
                        // Expose globally so other initializers (e.g., place autocomplete) can call it
                        window.loadCities = loadCities;

                        $('#state').on('change', function() {
                            loadCities(this.value);
                        });

                        $('#city').on('change', function() {
                            $('#city_id_hidden').val(this.value);
                        });

                        // Load initial cities if state is selected
                        if ($('#state').val()) {
                            loadCities($('#state').val());
                        }
                    }

                    // Initialize place autocomplete
                    function initPlaceAutocomplete() {
                        $('#place_name').autocomplete({
                            source: function(request, response) {
                                $.ajax({
                                    url: "{{ route('event_home.autocomplete_place') }}",
                                    type: 'GET',
                                    dataType: "json",
                                    data: { search: request.term },
                                    success: function(data) {
                                        console.log('Autocomplete data received:', data); // For debugging
                                        response($.map(data, function(item) {
                                            // Backend returns `value` as the place name
                                            return {
                                                label: item.value,
                                                value: item.value,
                                                id: item.id,
                                                address: item.address,
                                                number: item.number,
                                                district: item.district,
                                                complement: item.complement,
                                                zip: item.zip,
                                                city_id: item.city_id,
                                                uf: item.uf
                                            };
                                        }));
                                        showToast(`${data.length} local(is) encontrado(s)`, 'info', null, 1500);
                                    },
                                    error: () => showToast('Erro ao buscar locais', 'error')
                                });
                            },
                            minLength: 2,
                            select: function(event, ui) {
                                $('#place_name').val(ui.item.label);
                                $('#place_id_hidden').val(ui.item.id);
                                $('#address').val(ui.item.address).prop('readonly', true);
                                $('#number').val(ui.item.number).prop('readonly', true);
                                $('#district').val(ui.item.district).prop('readonly', true);
                                $('#complement').val(ui.item.complement).prop('readonly', true);
                                $('#zip').val(ui.item.zip).prop('readonly', true);
                                $('#state').val(ui.item.uf).prop('disabled', true);
                                loadCities(ui.item.uf, () => {
                                    $('#city').val(ui.item.city_id).prop('disabled', true);
                                    $('#city_id_hidden').val(ui.item.city_id);
                                });
                                return false;
                            }
                        });

                        $('#add_place').on('click', function() {
                            $('#place_name, #address, #number, #district, #complement, #zip').val('').prop('readonly', false);
                            $('#state, #city').prop('disabled', false).val('');
                            $('#city_id_hidden, #place_id_hidden').val('');
                        });
                    }

                    // Initialize dynamic fields
                    function initDynamicFields() {
                        let fieldCount = $('input.new_field').length;

                        function updateFieldNumbers() {
                            // Only renumber labels inside the dynamic fields container
                            const $container = $('#card-new-field');
                            $container.children('.row.mb-3').each(function(index) {
                                const fieldNumber = index + 1;
                                const $label = $(this).find('.form-label').first();
                                const original = $label.text();
                                // Only adjust labels that start with "Campo"
                                const updated = original.replace(/^Campo\s+\d+/, `Campo ${fieldNumber}`);
                                $label.text(updated);
                                $(this).find('.up').toggle(index > 0);
                                $(this).find('.down').toggle(index < $container.children('.row.mb-3').length - 1);
                            });
                        }

                        $('#option').on('change', function() {
                            const id = parseInt(this.value);
                            $('#div_new_options').toggle([2, 3, 4, 14].includes(id));
                            if (id === 14) {
                                $('#new_options').val('AC, AL, AP, AM, BA, CE, DF, ES, GO, MA, MT, MS, MG, PA, PB, PR, PE, PI, RJ, RN, RS, RO, RR, SC, SP, SE, TO');
                            } else {
                                $('#new_options').val('');
                            }
                            $('#div_new_number').toggle([9, 10].includes(id));
                        });

                        $('#add_new_field').on('click', function() {
                            const field = $('#question').val().trim();
                            const option = $('#option').val();
                            const optionText = $('#option option:selected').text();
                            const required = $('#required').is(':checked');
                            const unique = $('#unique').is(':checked');

                            if (!field) {
                                showToast('Por favor, preencha o nome do campo!', 'error');
                                return;
                            }

                            const fieldConfig = {
                                1: { text: '(Tipo: Texto (Até 200 caracteres))', name: 'text' },
                                2: { text: '(Tipo: Seleção)', name: 'select', options: `; [Opções: ${$('#new_options').val()}]` },
                                3: { text: '(Tipo: Marcação)', name: 'checkbox', options: `; [Opções: ${$('#new_options').val()}]` },
                                4: { text: '(Tipo: Múltipla escolha)', name: 'multiselect', options: `; [Opções: ${$('#new_options').val()}]` },
                                5: { text: '(Tipo: CPF)', name: 'cpf' },
                                6: { text: '(Tipo: CNPJ)', name: 'cnpj' },
                                7: { text: '(Tipo: Data)', name: 'date' },
                                8: { text: '(Tipo: Telefone)', name: 'phone' },
                                9: { text: '(Tipo: Número inteiro)', name: 'integer', options: `; [Opções: ${$('#val_min').val()}|${$('#val_max').val()}]` },
                                10: { text: '(Tipo: Número decimal)', name: 'decimal', options: `; [Opções: ${$('#val_min').val()}|${$('#val_max').val()}]` },
                                11: { text: '(Tipo: Arquivo)', name: 'file' },
                                12: { text: '(Tipo: Textarea (+ de 200 caracteres))', name: 'textarea' },
                                13: { text: '(Tipo: E-mail)', name: 'new_email' },
                                14: { text: '(Tipo: Estados (BRA))', name: 'states' }
                            };

                            const config = fieldConfig[option] || {};
                            const fieldText = `${field}; ${config.text}${config.options || ''}${required ? '; Obrigatório' : ''}${unique ? '; Único' : ''}`;
                            fieldCount++;

                            $('#card-new-field').append(`
                                <div class="row mb-3">
                                    <div class="col-9">
                                        <label class="form-label">Campo ${fieldCount}${required ? '*' : ''}</label>
                                        <input type="text" class="form-control new_field" name="new_field[]" value="${fieldText}" readonly>
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

                            $('#question, #new_options, #val_min, #val_max').val('');
                            $('#option').prop('selectedIndex', 0);
                            $('#required, #unique').prop('checked', false);
                            updateFieldNumbers();
                        });

                        $(document).on('click', '.btn-remove-field', function() {
                            $(this).closest('.row.mb-3').remove();
                            fieldCount--;
                            updateFieldNumbers();
                        });

                        $(document).on('click', '.up', function() {
                            const $row = $(this).closest('.row.mb-3');
                            $row.insertBefore($row.prev());
                            updateFieldNumbers();
                        });

                        $(document).on('click', '.down', function() {
                            const $row = $(this).closest('.row.mb-3');
                            $row.insertAfter($row.next());
                            updateFieldNumbers();
                        });

                        updateFieldNumbers();
                    }

                    // Initialize date adding
                    function initDateAdding() {
                        $('#add-date').on('click', function() {
                            const index = $('.row.mb-3[data-date-index]').length;
                            $('#card-date').append(`
                                <div class="row mb-3 g-3" data-date-index="${index}">
                                    <input type="hidden" name="date_id[]" value="">
                                    <div class="col-md-3 pe-3">
                                        <label for="datetimepicker_day_${index}" class="form-label">Data <span class="text-danger">*</span></label>
                                        <div class="input-group date" id="datetimepicker_day_${index}" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_day" name="date[]" autocomplete="off" required>
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 pe-3">
                                        <label for="datetimepicker_begin_${index}" class="form-label">Hora início <span class="text-danger">*</span></label>
                                        <div class="input-group date" id="datetimepicker_begin_${index}" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" name="time_begin[]" autocomplete="off" required>
                                            <span class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 pe-3">
                                        <label for="datetimepicker_end_${index}" class="form-label">Hora fim <span class="text-danger">*</span></label>
                                        <div class="input-group date" id="datetimepicker_end_${index}" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                            <input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" name="time_end[]" autocomplete="off" required>
                                            <span class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger d-block remove-date">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </div>
                                </div>
                            `);
                        });

                        $(document).on('click', '.remove-date', function() {
                            $(this).closest('.row.mb-3').remove();
                        });
                    }

                    // Initialize Mercado Pago handling
                    function initMercadoPago() {
                        const $formMercadoPago = $('#form_mercadopago');
                        const $linkAccButton = $('#link-acc-button');
                        const $linkedAccLabel = $('#linked-acc-label');

                        $('input[name="paid"]').on('change', function() {
                            $formMercadoPago.toggleClass('d-none', this.value !== '0');
                            if (this.value === '0' && $linkAccButton.attr('data-linked') === 'false') {
                                const intervalId = setInterval(() => {
                                    $.get('/webhooks/mercado-pago/check-linked-account')
                                        .done(data => {
                                            if (data.linked) {
                                                clearInterval(intervalId);
                                                $linkedAccLabel.text(`ID da Conta Vinculada: ${data.id}`);
                                                $linkAccButton.removeClass('btn-success').addClass('btn-secondary').text('Vincular outra conta').attr('data-linked', 'true');
                                            }
                                        })
                                        .fail(error => console.error('Erro ao verificar conta Mercado Pago:', error));
                                }, 5000);
                            }
                        });
                    }

                    // Initialize all functionality
                    initSummernote();
                    initFormValidation();
                    initDateTimePickers();
                    initNameValidation();
                    initSlugValidation();
                    initCategoryArea();
                    initStateCity();
                    initPlaceAutocomplete();
                    initDynamicFields();
                    initDateAdding();
                    initMercadoPago();
                });
            </script>
        @endpush
</x-site-layout>