<x-site-layout>
    <main id="main">
        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                    <li>Criar lote</li>
                </ol>
                <h2>Criar novo lote</h2>
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
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('event_home.create_lote_store') }}" id="lote-form" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="event_id" value="">
                            
                            <div class="card-body">
                                <h4>Informações do lote</h4>
                                
                                <div class="mb-3">
                                    <label for="type" class="form-label">
                                        Tipo do lote
                                        <span class="text-danger" aria-label="obrigatório">*</span>
                                    </label>
                                    <select id="type" class="form-select" name="type" required>
                                        <option value="">Selecione</option>
                                        <option value="0" @if(old('type') == '0') selected @endif>Pago</option>
                                        <option value="1" @if(old('type') == '1') selected @endif>Grátis</option>
                                    </select>
                                    <div class="invalid-feedback">Selecione o tipo do lote.</div>
                                </div>
                                
                                <div class="card p-3 mb-3" id="value_div" style="border: 1px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa;">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="tax_parcelamento" class="form-label">
                                                Juros do parcelamento
                                                <span class="text-danger">*</span>
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" title="Define quem paga os juros do parcelamento">
                                                    <i class="fa-solid fa-circle-question text-muted"></i>
                                                </a>
                                            </label>
                                            <select id="tax_parcelamento" class="form-select" name="tax_parcelamento" required>
                                                <option value="">Selecione</option>
                                                <option value="0" @if(old('tax_parcelamento') == '0') selected @endif>Pago pelo participante</option>
                                                <option value="1" @if(old('tax_parcelamento') == '1') selected @endif>Pago pelo organizador</option>
                                            </select>
                                            <div class="invalid-feedback">Selecione uma opção.</div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="tax_service" class="form-label">
                                                Taxa de serviço ({{ number_format($taxa_juros * 100, 2, ',', '.') }}%)
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select id="tax_service" class="form-select" name="tax_service" required>
                                                <option value="">Selecione</option>
                                                <option value="0" @if(old('tax_service') == '0') selected @endif>Pago pelo participante</option>
                                                <option value="1" @if(old('tax_service') == '1') selected @endif>Pago pelo organizador</option>
                                            </select>
                                            <div class="invalid-feedback">Selecione uma opção.</div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="value" class="form-label">
                                                Valor do ingresso
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="value" 
                                                name="value" 
                                                placeholder="00,00" 
                                                value="{{ old('value') }}"
                                                required
                                            >
                                            <div class="invalid-feedback">Insira um valor válido.</div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label class="form-label">
                                                Forma de pagamento
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="form_pagamento[]" value="1" @if(in_array('1', old('form_pagamento', []))) checked @endif>
                                                <label class="form-check-label" for="inlineCheckbox1">Cartão de crédito</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="form_pagamento[]" value="2" @if(in_array('2', old('form_pagamento', []))) checked @endif>
                                                <label class="form-check-label" for="inlineCheckbox2">
                                                    Boleto bancário
                                                    <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" title="Informações sobre boleto">
                                                        <i class="fa-solid fa-circle-question text-muted"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3" name="form_pagamento[]" value="3" @if(in_array('3', old('form_pagamento', []))) checked @endif>
                                                <label class="form-check-label" for="inlineCheckbox3">
                                                    PIX
                                                    <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" title="Informações sobre PIX">
                                                        <i class="fa-solid fa-circle-question text-muted"></i>
                                                    </a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3 g-3">
                                    <div class="col-md-8">
                                        <label for="name" class="form-label">
                                            Nome do lote
                                            <span class="text-danger" aria-label="obrigatório">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="name"
                                            name="name"
                                            placeholder="Nome do lote"
                                            value="{{ htmlspecialchars(old('name')) }}"
                                            required
                                            aria-describedby="name-help"
                                            minlength="2"
                                            maxlength="255"
                                        >
                                        <div id="name-help" class="form-text">
                                            Mínimo 2 caracteres, máximo 255
                                        </div>
                                        <div class="invalid-feedback">Insira um nome válido.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="quantity" class="form-label">
                                            Quantidade
                                            <span class="text-danger" aria-label="obrigatório">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="quantity"
                                            name="quantity"
                                            placeholder="0"
                                            value="{{ old('quantity') }}"
                                            min="0"
                                            max="999999"
                                            required
                                            aria-describedby="quantity-help"
                                        >
                                        <div id="quantity-help" class="form-text">
                                            Número de ingressos disponíveis
                                        </div>
                                        <div class="invalid-feedback">Insira uma quantidade válida.</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descrição</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="description" 
                                        name="description" 
                                        placeholder="Descrição do lote (opcional)" 
                                        value="{{ htmlspecialchars(old('description')) }}"
                                        maxlength="255"
                                    >
                                </div>
                                
                                <div class="row mb-3 g-3">
                                    <div class="col-md-6">
                                        <label for="limit_min" class="form-label">
                                            Limite mínimo por compra
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            id="limit_min" 
                                            name="limit_min" 
                                            placeholder="0" 
                                            value="{{ old('limit_min', 0) }}" 
                                            min="0"
                                            required
                                        >
                                        <div class="invalid-feedback">Insira um valor válido.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="limit_max" class="form-label">
                                            Limite máximo por compra
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            id="limit_max" 
                                            name="limit_max" 
                                            placeholder="0" 
                                            value="{{ old('limit_max', 0) }}" 
                                            min="0"
                                            required
                                        >
                                        <div class="invalid-feedback">Insira um valor válido.</div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3 g-3">
                                    <div class="col-md-6">
                                        <label for="datetime_begin" class="form-label">
                                            Data e hora de início
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group date" id="datetimepicker_day_begin" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                            <input 
                                                type="text" 
                                                class="form-control datetimepicker-input datetimepicker_day" 
                                                id="input_datetimepicker_day_begin"
                                                name="datetime_begin" 
                                                autocomplete="off" 
                                                required
                                            >
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                        </div>
                                        <div class="invalid-feedback">Selecione uma data e hora válidas.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="datetime_end" class="form-label">
                                            Data e hora de fim
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group date" id="datetimepicker_day_end" data-td-target="datetimepicker" data-td-toggle="datetimepicker">
                                            <input 
                                                type="text" 
                                                class="form-control datetimepicker-input datetimepicker_day" 
                                                id="input_datetimepicker_day_end"
                                                name="datetime_end" 
                                                autocomplete="off" 
                                                required
                                            >
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                        </div>
                                        <div class="invalid-feedback">Selecione uma data e hora válidas.</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="visibility" class="form-label">
                                        Visibilidade
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="visibility" class="form-select" name="visibility" required>
                                        <option value="">Selecione</option>
                                        <option value="0" @if(old('visibility') == '0') selected @endif>Público</option>
                                        <option value="1" @if(old('visibility') == '1') selected @endif>Privado</option>
                                    </select>
                                    <div class="invalid-feedback">Selecione uma opção.</div>
                                </div>
                            </div>
                            
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('event_home.create.step.two') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar
                                </a>
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <i class="fas fa-save me-2"></i>Criar lote
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.css">
        <link rel="stylesheet" href="{{ asset('assets_admin/css/painel-admin-improvements.css') }}" type="text/css">
        <style>
            /* Corrigir overflow horizontal */
            body {
                overflow-x: hidden;
            }
            
            .container {
                max-width: 100%;
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .row {
                margin-left: 0;
                margin-right: 0;
            }
            
            .row > * {
                padding-left: 12px;
                padding-right: 12px;
            }
            
            /* Garantir que selects tenham setinha */
            .form-select {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                background-size: 16px 12px;
                padding-right: 2.5rem;
            }
            
            /* Ícone do calendário maior e padronizado */
            .input-group-text {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .input-group-text i.fas.fa-calendar {
                font-size: 1rem;
                color: #6c757d;
            }
            
            /* Invalid feedback deve estar oculto por padrão */
            .invalid-feedback {
                display: none;
            }
            
            /* Só mostrar quando o campo for inválido */
            .was-validated .form-control:invalid ~ .invalid-feedback,
            .was-validated .form-select:invalid ~ .invalid-feedback,
            .form-control.is-invalid ~ .invalid-feedback,
            .form-select.is-invalid ~ .invalid-feedback {
                display: block;
            }
        </style>
    @endpush

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.js"></script>

        <script>
        $(document).ready(function() {
            // Máscara para valor monetário
            $('#value').mask("#.##0,00", {
                reverse: true
            });

            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Controlar exibição do bloco de valor
            $('#type').change(function() {
                var id_type = $(this).val();
                if (id_type == 1) {
                    $('#value_div').hide();
                    // Remover required dos campos quando oculto
                    $('#value_div select, #value_div input').removeAttr('required');
                } else {
                    $('#value_div').show();
                    // Adicionar required quando visível
                    $('#tax_parcelamento, #tax_service, #value').attr('required', 'required');
                }
            });
            
            // Verificar estado inicial
            var initialType = $('#type').val();
            if (initialType == '1') {
                $('#value_div').hide();
                $('#value_div select, #value_div input').removeAttr('required');
            }

            // Configuração do datepicker
            const localePt_Br = {
                days: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                daysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                daysMin: ['Do', 'Se', 'Te', 'Qu', 'Qu', 'Se', 'Sa'],
                months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                today: 'Hoje',
                clear: 'Cancelar',
                dateFormat: 'dd/MM/yyyy',
                timeFormat: 'HH:mm',
                firstDay: 1
            };

            var dpMin = new AirDatepicker('#input_datetimepicker_day_begin', {
                timepicker: true,
                minDate: new Date(),
                locale: localePt_Br,
                onSelect({date}) {
                    if (dpMax) {
                        dpMax.update({
                            minDate: date
                        });
                    }
                }
            });

            var dpMax = new AirDatepicker('#input_datetimepicker_day_end', {
                timepicker: true,
                minDate: new Date(),
                locale: localePt_Br,
                onSelect({date}) {
                    if (dpMin) {
                        dpMin.update({
                            maxDate: date
                        });
                    }
                }
            });
            
            // Validação do formulário - só adicionar was-validated no submit
            (function() {
                'use strict';
                var form = document.getElementById('lote-form');
                if (form) {
                    // Remover was-validated se existir (caso tenha sido adicionado antes)
                    form.classList.remove('was-validated');
                    
                    // Validar no submit
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        // Só adicionar was-validated quando o formulário for submetido
                        form.classList.add('was-validated');
                    }, false);
                    
                    // Validar campos individualmente ao sair (blur)
                    var inputs = form.querySelectorAll('input, select, textarea');
                    inputs.forEach(function(input) {
                        input.addEventListener('blur', function() {
                            if (form.classList.contains('was-validated')) {
                                if (this.checkValidity()) {
                                    this.classList.remove('is-invalid');
                                    this.classList.add('is-valid');
                                } else {
                                    this.classList.remove('is-valid');
                                    this.classList.add('is-invalid');
                                }
                            }
                        });
                    });
                }
            })();
        });
        </script>
    @endpush

</x-site-layout>
