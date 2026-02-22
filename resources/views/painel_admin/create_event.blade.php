<x-site-layout>
    <main id="main">
        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/painel/meus-eventos">Meus eventos</a></li>
                    <li class="breadcrumb-item"></li>
                </ol>
                <h2>@if(isset($event) && $event->id) Editar @else Criar @endif {{ isset($event) ? htmlspecialchars($event->name) : 'Novo' }} evento</h2>
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
                <div class="wizard-container">
                    @php
                        $currentStep = 1; // Step atual baseado na rota ou sessão
                        if (request()->routeIs('event_home.create.step.two')) {
                            $currentStep = 2;
                        } elseif (request()->routeIs('event_home.create.step.three') || request()->routeIs('event_home.create.step.four')) {
                            $currentStep = request()->routeIs('event_home.create.step.four') ? 4 : 3;
                        }
                        $progress = ($currentStep / 4) * 100;
                    @endphp
                    
                    <div class="wizard-progress">
                        <div class="wizard-progress-bar" style="width: {{ $progress }}%"></div>
                    </div>
                    
                    <div class="wizard-steps">
                        <div class="step {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
                            <div class="step-number">1</div>
                            <div class="step-label">Informações</div>
                        </div>
                        <div class="step {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}">
                            <div class="step-number">2</div>
                            <div class="step-label">Inscrições</div>
                        </div>
                        <div class="step {{ $currentStep >= 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}">
                            <div class="step-number">3</div>
                            <div class="step-label">Cupons</div>
                        </div>
                        <div class="step {{ $currentStep >= 4 ? 'active' : '' }}">
                            <div class="step-number">4</div>
                            <div class="step-label">Publicar</div>
                        </div>
                    </div>
                    
                    <div class="wizard-content">
                    @php
                        $isEdit = isset($event) && $event->id;
                        $formAction = $isEdit ? route('event_home.my_events_edit.update', $event->hash) : route('event_home.create.step.one');
                        $formMethod = $isEdit ? 'PUT' : 'POST';
                    @endphp
                    <form method="POST" action="{{ $formAction }}" id="event-form" class="needs-validation" novalidate>
                        @if($isEdit)
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="card-body">
                            <h4>Sobre o evento</h4>
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    Nome do evento
                                    <span class="text-danger" aria-label="obrigatório">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Nome do evento"
                                    value="{{ htmlspecialchars($event->name ?? old('name')) }}"
                                    required
                                    aria-describedby="name-help name-error"
                                    autocomplete="off"
                                    minlength="3"
                                    maxlength="255"
                                >
                                <div id="name-help" class="form-text">
                                    Mínimo 3 caracteres, máximo 255
                                </div>
                                <div class="invalid-feedback" id="name-error" role="alert"></div>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">URL personalizada <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ config('app.url') }}/</span>
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="url-personalizada" value="{{ $event->slug ?? old('slug') }}" {{ $isEdit ? 'readonly' : 'required' }}>
                                </div>
                                @if($isEdit)
                                    <small class="form-text text-muted">A URL não pode ser alterada após a criação do evento.</small>
                                @endif
                                <div class="invalid-feedback" id="slug-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="subtitle" class="form-label">Subtítulo</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="Subtítulo do evento (opcional)" value="{{ htmlspecialchars($event->subtitle ?? old('subtitle')) }}">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição do evento <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" required>{{ $event->description ?? old('description') }}</textarea>
                            </div>
                            @if($isEdit)
                            <div class="mb-3">
                                <label for="youtube_video_url" class="form-label">Vídeo de divulgação (YouTube)</label>
                                <input type="url" class="form-control" id="youtube_video_url" name="youtube_video_url" placeholder="Ex: https://www.youtube.com/watch?v=VIDEO_ID" value="{{ old('youtube_video_url', $event->youtube_video_url ?? '') }}">
                                <div class="form-text">Link do vídeo do YouTube exibido na página do evento (opcional).</div>
                            </div>
                            @endif
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
                                        Limpar
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
                                    <div class="col-md-5 me-2 mb-2">
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
                                            <div class="col-md-12" id="div_new_options">
                                                <label for="new_options" class="form-label">Preencha as opções separadas por vírgula</label>
                                                <input type="text" class="form-control" name="new_options" id="new_options" placeholder="XXX, YYY, ZZZ">
                                            </div>
                                            <div class="col-md-12" id="div_new_number">
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
                                @if(isset($questions) && count($questions) > 0)
                                    @foreach ($questions as $question)
                                        @php
                                            $index = $loop->index;
                                        @endphp
                                        @if($index < 2)
                                            <div class="mb-3">
                                                <label for="new_field_{{ $index }}" class="form-label">Campo {{ $index + 1 }}{{ isset($question->required) && $question->required == 1 ? '*' : '' }}</label>
                                                <input type="text" class="form-control new_field" name="new_field[]" value="{{ isset($question->formatted_options) ? $question->formatted_options : (is_array($question) && isset($question['formatted_options']) ? $question['formatted_options'] : '') }}" readonly>
                                                <input type="hidden" name="new_field_id[]" value="{{ isset($question->id) ? $question->id : (is_array($question) && isset($question['id']) ? $question['id'] : '') }}">
                                            </div>
                                        @else
                                            <div class="row mb-3">
                                                <div class="col-9">
                                                    <label for="new_field_{{ $index }}" class="form-label">Campo {{ $index + 1 }}{{ isset($question->required) && $question->required ? '*' : '' }}</label>
                                                    <input type="text" class="form-control new_field" name="new_field[]" value="{{ isset($question->formatted_options) ? $question->formatted_options : (is_array($question) && isset($question['formatted_options']) ? $question['formatted_options'] : '') }}" readonly>
                                                    <input type="hidden" name="new_field_id[]" value="{{ isset($question->id) ? $question->id : (is_array($question) && isset($question['id']) ? $question['id'] : '') }}">
                                                </div>
                                                <div class="col-3" style="margin-top: 35px;">
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
                            </div>
                            <hr>

                            <div class="card-body">
                                <h4>Carteira de pagamento</h4>
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="paid" id="inlineRadio_mercadopago" value="1" {{ isset($event) && $event->paid == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineRadio_mercadopago">Mercado Pago <a href="https://www.mercadopago.com.br/" target="_blank"><i class="fa-solid fa-up-right-from-square"></i></a></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="paid" id="inlineRadio_nenhuma" value="0" {{ isset($event) && $event->paid == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineRadio_nenhuma">Nenhuma (Evento gratuito)</label>
                                    </div>
                                </div>

                                @if(isset($mercadoPagoLinked['linked']) && $mercadoPagoLinked['linked'])
                                    <div class="mb-3 d-none" id="form_mercadopago">
                                        <label for="contact" id="linked-acc-label" class="form-label">ID da Conta Vinculada: {{ $mercadoPagoLinked['id'] }}</label>
                                        <br>
                                        <a href="https://auth.mercadopago.com.br/authorization?client_id={{ env('MERCADO_PAGO_CLIENT_ID', '') }}&response_type=code&platform_id=mp&redirect_uri={{ urlencode(route('mercado-pago.link-account')) }}" target="_blank" id="link-acc-button" data-linked="true" class="btn btn-secondary">Vincular outra conta</a>
                                    </div>
                                @else
                                    <div class="mb-3 d-none" id="form_mercadopago">
                                        <label for="contact" id="linked-acc-label" class="form-label">Vincular conta Mercado Pago</label>
                                        <br>
                                        <a href="https://auth.mercadopago.com.br/authorization?client_id={{ env('MERCADO_PAGO_CLIENT_ID', '') }}&response_type=code&platform_id=mp&redirect_uri={{ urlencode(route('mercado-pago.link-account')) }}" target="_blank" id="link-acc-button" data-linked="false" class="btn btn-success">Vincular conta</a>
                                    </div>
                                @endif
                                <input type="hidden" name="mercadopago_link" id="mercadopago_link" value="{{ $mercadopago_link ?? '' }}">
                            </div>

                            <div class="card-footer text-end">
                                <!-- Progress Bar -->
                                <div class="progress-container mb-3 d-none" id="form-progress">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" id="progress-bar"></div>
                                    </div>
                                    <div class="progress-text" id="progress-text">Salvando...</div>
                                </div>

                                    {{-- <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <span class="btn-text">Próximo</span>
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                    </button> --}}
                            </div>
                        </div>
                    </form>
                    </div>
                    <!-- Wizard Actions -->
                    <div class="wizard-actions">
                        @if($currentStep > 1)
                            <a href="{{ route('event_home.create_event') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Anterior
                            </a>
                        @else
                            <div></div>
                        @endif
                        @if($currentStep < 4)
                            <button type="submit" form="event-form" class="btn btn-primary">
                                Próximo <i class="fas fa-arrow-right"></i>
                            </button>
                        @else
                            <button type="submit" form="event-form" class="btn btn-success">
                                <i class="fas fa-check"></i> Publicar Evento
                            </button>
                        @endif
                    </div>
                </div>
        </section>
    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.css">
        <link href="{{ asset('assets_admin/css/mobile-responsive.css') }}" rel="stylesheet">
        <!-- Modern Admin CSS (includes wizard styles) -->
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
        <style>
            /* Garantir que o dropdown do usuário funcione corretamente */
            #userDropdown {
                pointer-events: auto !important;
            }
            .navbar .dropdown-menu {
                z-index: 1050 !important;
            }
            /* Air Datepicker customizations */
            .air-datepicker {
                z-index: 1060 !important;
            }
            .input-group-text {
                cursor: pointer;
            }
        </style>
    @endpush

    @push('footer')
        <!-- jQuery UI e plugins específicos da página (jQuery e Bootstrap já carregados no layout) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Create Event JavaScript Variables -->
        <script>
            window.routes = {
                check_slug: '{{ route('event_home.check_slug') }}',
                create_slug: '{{ route('event_home.create_slug') }}',
                get_areas_by_category: '{{ route('event_home.get_areas_by_category') }}',
                get_city: '{{ route('event_home.get_city') }}',
                autocomplete_place: '{{ route('event_home.autocomplete_place') }}'
            };
            window.csrf_token = '{{ csrf_token() }}';
        </script>

        <!-- Create Event JavaScript -->
        <script src="{{ asset('assets_admin/js/create_event.js') }}"></script>
        
        <!-- Garantir que o dropdown do usuário funcione corretamente -->
        <script>
            $(document).ready(function() {
                // Garantir que o Bootstrap dropdown seja inicializado corretamente
                var dropdownElementList = [].slice.call(document.querySelectorAll('#userDropdown'));
                var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });
                
                // Prevenir que outros event listeners bloqueiem o dropdown
                $('#userDropdown').on('click', function(e) {
                    e.stopPropagation();
                });
            });
        </script>
    @endpush
</x-site-layout>
