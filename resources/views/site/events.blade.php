<x-site-layout>
    <main id="main">
        <!-- Breadcrumbs -->
        <section class="breadcrumbs">
            <div class="container">
                <h2 class="mt-4">Todos os eventos</h2>
            </div>
        </section>

        <section class="inner-page search" id="event-list">
            <div class="container">
                <header class="section-header">
                    <p>Busque mais eventos</p>
                </header>
                <div class="row gy-3"> <!-- Adicionado gy-3 para espaçamento responsivo -->
                    <div class="info-box bg-light col-12">
                        <div class="container-fluid info-box-content">
                            <form action="enhanced-results.html">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="row gy-2"> <!-- gy-2 para mobile stack -->
                                            <!-- Selects com ARIA labels -->
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label for="category"><b>Categoria</b></label>
                                                <select name="category" id="category" class="form-select" aria-label="Selecione categoria">
                                                    <option value="0">Selecione uma opção</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Área</b></label>
                                                <select name="area_id" id="area_id" class="form-select" placeholder="Área">
                                                    <option value="0">Selecione uma categoria</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Local</b></label>
                                                <select name="state" id="state" class="form-select" placeholder="Estado">
                                                    <option value="0">Selecione uma opção</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->uf }}">{{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Período</b></label>
                                                <select name="period" id="period" class="form-select" placeholder="Período">
                                                    <option value="0">Selecione uma opção</option>
                                                    <option value="any"> Qualquer </option>
                                                    <option value="today"> Hoje </option>
                                                    <option value="tomorrow"> Amanhã </option>
                                                    <option value="week"> Esta semana </option>
                                                    <option value="month"> Este mês </option>
                                                    @php
                                                        $begin = date('m');
                                                        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                                                        date_default_timezone_set('America/Sao_Paulo');
                                                        for ($i = intval($begin) + 1; $i <= 12; $i++) {
                                                            $month = ucfirst(strftime('%B', mktime(0, 0, 0, $i, 10)));
                                                            $month_eng = strtolower(date('F', mktime(0, 0, 0, $i, 10)));
                                                            echo "<option value='$month_eng'>$month</option>";
                                                        }
                                                    @endphp
                                                    <option value="year"> Este ano </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="search" class="form-control" id="event_name_search" placeholder="Nome do evento" aria-label="Buscar por nome do evento">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="event_data">
                    @include('site.events_data')
                </div>
                <div id="loading-indicator" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Carregando...</span>
                    </div>
                    <p class="mt-2">Buscando eventos...</p>
                </div>
                <div class="container d-flex justify-content-center">
                    {{ $events->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('head')
    
    @endpush

    @push('footer')
        <script>

            function getMoreEvents(page) {
                try {
                    // Verificar se os elementos existem antes de tentar acessar seus valores
                    var event_val = '';
                    var category_val = '0';
                    var area_val = '0';
                    var state_val = '0';
                    var period_val = '0';
                    
                    if ($('#event_name_search').length > 0) {
                        event_val = $('#event_name_search').val() || '';
                    }
                    if ($("#category option:selected").length > 0) {
                        category_val = $("#category option:selected").val() || '0';
                    }
                    if ($("#area_id option:selected").length > 0) {
                        area_val = $("#area_id option:selected").val() || '0';
                    }
                    if ($("#state option:selected").length > 0) {
                        state_val = $("#state option:selected").val() || '0';
                    }
                    if ($("#period option:selected").length > 0) {
                        period_val = $("#period option:selected").val() || '0';
                    }
                    
                    console.log('Valores para busca:', {
                        event_val: event_val,
                        category_val: category_val,
                        area_val: area_val,
                        state_val: state_val,
                        period_val: period_val
                    });

                $.ajax({
                    type: "GET",
                    data: {
                        'event_val': event_val,
                        'category_val': category_val,
                        'area_val': area_val,
                        'state_val': state_val,
                        'period_val': period_val
                    },
                    url: "{{ route('event_home.get-more-events') }}" + "?page=" + page,
                    beforeSend: function() {
                        $('#event_data').html(
                            '<div class="d-flex justify-content-center" style="padding: 100px 0"><div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div></div>'
                            );
                    },
                    success: function(data) {
                        if (data && data.events_list) {
                            $('#event_data').html(data.events_list);
                        } else {
                            $('#event_data').html('<div class="col-md-12"><div class="alert alert-warning"><strong>Ops!</strong> Nenhum evento encontrado.</div></div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na busca:', error);
                        $('#event_data').html('<div class="col-md-12"><div class="alert alert-danger"><strong>Erro!</strong> Ocorreu um erro ao buscar eventos.</div></div>');
                    }
                });
            } catch (error) {
                console.error('Erro na função getMoreEvents:', error);
                $('#event_data').html('<div class="col-md-12"><div class="alert alert-danger"><strong>Erro!</strong> Ocorreu um erro ao processar a busca.</div></div>');
            }
        }

        $(document).ready(function() {
            // Verificar se jQuery está carregado
            if (typeof $ === 'undefined') {
                console.error('jQuery não está carregado');
                return;
            }

            // Verificar se os elementos existem
            console.log('Elemento #event_name_search encontrado:', $('#event_name_search').length);
            console.log('Elemento #event_name_search:', $('#event_name_search')[0]);

            if ($('#event_name_search').length === 0) {
                console.error('Elemento #event_name_search não encontrado');
                return;
            }

            // Verificar se o elemento tem o método val
            if (typeof $('#event_name_search').val !== 'function') {
                console.error('Elemento #event_name_search não tem método val');
                return;
            }

            getMoreEvents(1);

            // Debounce function para evitar muitas requisições
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func.apply(this, args); // Preserve the original 'this' context
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Busca com debounce
            $('#event_name_search').on('keyup', debounce(function(e) {
                try {
                    console.log('this:', this); // Debug: log the context
                    console.log('$(this):', $(this)); // Debug: log the jQuery object
                    var $value = $('#event_name_search').val() || ''; // Explicitly use the element
                    console.log('Valor digitado:', $value);
                    getMoreEvents(1);
                } catch (error) {
                    console.error('Erro no evento keyup:', error);
                }
            }, 500));

            // Também adicionar evento de input para garantir
            $('#event_name_search').on('input', debounce(function(e) {
                try {
                    console.log('this:', this);
                    console.log('$(this):', $(this));
                    var $value = $('#event_name_search').val().trim() || ''; // Adicionado trim() para evitar buscas vazias
                    console.log('Valor digitado:', $value);
                    getMoreEvents(1);
                } catch (error) {
                    console.error('Erro no evento input:', error);
                }
            }, 500));

            $('#category').on('change', function(e) {
                getMoreEvents(1);
            });

            $('#area_id').on('change', function(e) {
                getMoreEvents(1);
            });

            $('#state').on('change', function(e) {
                getMoreEvents(1);
            });

            $('#period').on('change', function(e) {
                getMoreEvents(1);
            });

            // Carregar áreas quando categoria for selecionada
            $('#category').on('change', function() {
                var category_id = this.value;
                if (category_id && category_id !== '0') {
                    $("#area_id").html('<option value="0">Selecione uma área</option>');
                    $.ajax({
                        url: "{{ route('event_home.get_areas_by_category') }}",
                        type: "POST",
                        data: {
                            category_id: category_id,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            if (result && result.areas) {
                                $.each(result.areas, function(key, value) {
                                    $("#area_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Erro ao carregar áreas:', error);
                        }
                    });
                } else {
                    $("#area_id").html('<option value="0">Selecione uma categoria</option>');
                }
            });
        });
        </script>
    @endpush

</x-site-layout>
