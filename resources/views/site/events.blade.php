<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <h2 class="mt-4"> Todos os eventos</h2>

            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page search" id="event-list">
            <div class="container">
                <header class="section-header">
                    <p>Busque mais eventos</p>
                </header>
                <div class="row">
                    {{-- <h6 class="text-left display-5">Busque mais eventos</h6> --}}
                    <div class="info-box bg-light">
                        <div class="container-fluid info-box-content">
                            <form action="enhanced-results.html">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Categoria</b></label>
                                                <select name="category" id="category" class="custom-select sources"
                                                        placeholder="Categoria">
                                                    <option value="0">Selecione uma opção</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Área</b></label>
                                                <select name="area_id" id="area_id" class="custom-select sources"
                                                        placeholder="Área">
                                                    <option value="0">Selecione uma categoria</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Local</b></label>
                                                <select name="state" id="state" class="custom-select sources"
                                                        placeholder="Estado">
                                                    <option value="0">Selecione uma opção</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->uf }}">{{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Período</b></label>
                                                <select name="period" id="period" class="custom-select sources"
                                                        placeholder="Período">
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
                                                        <input type="search" class="form-control"
                                                               id="event_name_search" placeholder="Nome do evento" />
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-2 text-right">
                                                <input class="btn btn-primary" id="event_button_search" type="submit" value="Buscar" style="width: 100%;">
                                            </div>  --}}
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

                var event_val = $('#event_name_search').val();
                var category_val = $("#category option:selected").val();
                var area_val = $("#area_id option:selected").val();
                var state_val = $("#state option:selected").val();
                var period_val = $("#period option:selected").val();

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
                        $('#event_data').html(data.events_list);
                    }
                });
            }

            $(document).ready(function() {

                getMoreEvents(1);
                $('#event_name_search').on('keyup', function() {
                    $value = $(this).val();
                    getMoreEvents(1);
                });
                $('#category').on('change', function(e) {
                    getMoreEvents(1);
                });
                $('#area_id').on('change', function(e) {
                    getMoreEvents();
                });
                $('#state').on('change', function(e) {
                    getMoreEvents();
                });
                $('#period').on('change', function(e) {
                    getMoreEvents();
                });

                $('#category').next().find('.custom-options').click(function() {
                    category_id = $(this).children('.selection').attr('data-value');
                    $("#category").find('option').attr("selected", false);
                    $('#category option[value="' + category_id + '"]').attr('selected', 'selected');
                    $("#area_id").html('');
                    $.ajax({
                        url: "{{ route('event_home.get_areas_by_category') }}",
                        type: "POST",
                        data: {
                            category_id: category_id,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            // $('#area_id').html('<option value="">Selecione</option>'); 
                            $.each(result.areas, function(key, value) {
                                $("#area_id").append('<option value="' + value.id + '">' +
                                    value.name + '</option>');
                            });

                            var template = '';
                            $('#area_id').find("option").each(function() {
                                template += '<span class="custom-option ' + $(this).attr(
                                        "class") + '" data-value="' + $(this).attr(
                                    "value") + '">' + $(this).html() + '</span>';
                            });

                            $('#area_id').next().children('.custom-options').html(template);
                        }
                    });
                });

                $('#category').on('change', function() {
                    var category_id = this.value;
                    $("#area_id").html('');
                    $.ajax({
                        url: "{{ route('event_home.get_areas_by_category') }}",
                        type: "POST",
                        data: {
                            category_id: category_id,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            $('#area_id').html('<option value="">Selecione</option>');
                            $.each(result.areas, function(key, value) {
                                $("#area_id").append('<option value="' + value.id + '">' +
                                    value.name + '</option>');
                            });
                        }
                    });
                });

                $('#area_id').next().find('.custom-options').click(function() {
                    area_id = $(this).children('.selection').attr('data-value');
                    $("#area_id").find('option').attr("selected", false);
                    $('#area_id option[value="' + area_id + '"]').attr('selected', 'selected');
                    $('#area_id').next().find('.custom-options').parents(".custom-select-wrapper").find(
                        "select").val($(this).data("value"));
                    $('#area_id').next().find('.custom-options').parents(".custom-select-wrapper").find(
                        "select").find(".custom-option").removeClass("selection");
                });

                var el_custom_option = $('#area_id').next().children('.custom-select-trigger').next();
                $(el_custom_option).on('click', 'span', function() {
                    $(el_custom_option).children('span').removeClass("selection");
                    $(this).addClass("selection");
                    $(this).parents(".custom-select").removeClass("opened");
                    $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
                });
            });
        </script>
    @endpush

</x-site-layout>
