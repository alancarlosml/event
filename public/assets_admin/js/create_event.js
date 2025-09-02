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

            $.get(window.routes.check_slug, { title: name })
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

            $.get(window.routes.create_slug, { title: slug })
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
                url: window.routes.get_areas_by_category,
                type: "POST",
                data: { category_id: categoryId, _token: window.csrf_token },
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
                url: window.routes.get_city,
                type: "POST",
                data: { uf: uf, _token: window.csrf_token },
                dataType: 'json',
                success: function(result) {
                    const $citySelect = $('#city');
                    const currentSelectedValue = $citySelect.val(); // Preserve current selection
                    $citySelect.html('<option value="">Selecione a cidade</option>');
                    $.each(result.cities, function(key, value) {
                        $citySelect.append(`<option value="${value.id}">${value.name}</option>`);
                    });

                    // Restore the previously selected value if it exists in the new options
                    if (currentSelectedValue && $citySelect.find(`option[value="${currentSelectedValue}"]`).length > 0) {
                        $citySelect.val(currentSelectedValue);
                    }

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

        // Load initial cities if state is selected and preserve existing selection
        if ($('#state').val()) {
            const currentCityValue = $('#city').val();
            // Only load cities if no city is currently selected (to preserve existing selection)
            if (!currentCityValue) {
                loadCities($('#state').val());
            }
        }
    }

    // Initialize place autocomplete
    function initPlaceAutocomplete() {
        $('#place_name').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: window.routes.autocomplete_place,
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
            const $row = $(this).closest('.row.mb-3');
            const dateId = $row.find('input[name="date_id[]"]').val();

            // If this is an existing date (has an ID), mark it for deletion
            if (dateId && dateId !== '') {
                // Add to deletion list
                if (!$('#deleted_dates').length) {
                    $('form').append('<input type="hidden" id="deleted_dates" name="deleted_dates" value="">');
                }
                const currentDeleted = $('#deleted_dates').val();
                const newDeleted = currentDeleted ? currentDeleted + ',' + dateId : dateId;
                $('#deleted_dates').val(newDeleted);
            }

            // Remove the row from DOM
            $row.remove();
        });
    }

    // Initialize Mercado Pago handling
    function initMercadoPago() {
        const $formMercadoPago = $('#form_mercadopago');
        const $linkAccButton = $('#link-acc-button');
        const $linkedAccLabel = $('#linked-acc-label');

        $('input[name="paid"]').on('change', function() {
            $formMercadoPago.toggleClass('d-none', this.value !== '1');
            if (this.value === '1' && $linkAccButton.attr('data-linked') === 'false') {
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

    // Initialize form submission with loading states
    function initFormSubmission() {
        const $form = $('form.needs-validation');
        const $submitBtn = $('#submit-btn');
        const $btnText = $('.btn-text');
        const $spinner = $('.spinner-border');
        const $progressContainer = $('#form-progress');
        const $progressBar = $('#progress-bar');
        const $progressText = $('#progress-text');

        $form.on('submit', function(e) {
            // Show loading state
            $submitBtn.prop('disabled', true);
            $btnText.text('Salvando...');
            $spinner.removeClass('d-none');
            $progressContainer.removeClass('d-none');

            // Simulate progress (in a real app, this would be based on actual upload progress)
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90; // Don't reach 100% until actually complete

                $progressBar.css('width', progress + '%');
                $progressText.text('Salvando... ' + Math.round(progress) + '%');
            }, 200);

            // Store interval for cleanup
            $form.data('progressInterval', progressInterval);
        });

        // Handle form submission completion (success/error)
        $(window).on('beforeunload', function() {
            const interval = $form.data('progressInterval');
            if (interval) {
                clearInterval(interval);
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
    initFormSubmission();
});
