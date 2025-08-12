<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Inscrição</h1>
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
                                <h3 class="card-title">Editar inscrição - {{ $event->name }}</h3>

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
                                <form method="POST"
                                      action="{{ route('event.participantes.update', $participanteLote->order_item_id) }}">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="number">Número</label>
                                            <input type="text" disabled class="form-control" id="number"
                                                   placeholder="Número" value="{{ $participanteLote->number }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="participante">Participante</label>
                                            <input type="text" disabled class="form-control" id="participante"
                                                   placeholder="Participante"
                                                   value="{{ $participanteLote->participante_name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="lote_id">Lote</label>
                                            <select class="form-control" id="lote_id" name="lote_id">
                                                <option>Selecione</option>
                                                @foreach ($lotes as $lote)
                                                    <option value="{{ $lote->id }}"
                                                            @if ($participanteLote->lote_id == $lote->id) selected @endif>
                                                        {{ $lote->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Situação</label>
                                            <select class="form-control" id="status" name="status">
                                                <option>Selecione</option>
                                                <option value="1"
                                                        @if ($participanteLote->status == 1) selected @endif>Confirmado
                                                </option>
                                                <option value="2"
                                                        @if ($participanteLote->status == 2) selected @endif>Pendente
                                                </option>
                                                <option value="3"
                                                        @if ($participanteLote->status == 3) selected @endif>Cancelado
                                                </option>
                                            </select>
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
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
              integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
                integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script
                src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"
                integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(function() {

                $('#name').keyup(function(e) {
                    $.get('{{ route('event.check_slug') }}', {
                            'title': $(this).val()
                        },
                        function(data) {
                            $('#slug').val(data.slug);
                        }
                    );
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
                                console.log(data);
                                response(data);
                            }
                        });
                    },
                    select: function(event, ui) {
                        console.log(ui.item.uf);
                        $('#place_name').val(ui.item.label);
                        $('#address').val(ui.item.address);
                        $('#number').val(ui.item.number);
                        $('#district').val(ui.item.district);
                        $('#complement').val(ui.item.complement);
                        $('#zip').val(ui.item.zip);

                        $('#state option[value="' + ui.item.uf + '"]').prop("selected", true);

                        var uf = $("#state").val();
                        $("#city").html('');
                        $.ajax({
                            url: "{{ url('places/get-cities-by-state') }}",
                            type: "POST",
                            data: {
                                uf: uf,
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'json',
                            success: function(result) {
                                $('#city').html('<option value="">Selecione</option>');
                                city_id = $('#city_id_hidden').val();

                                $.each(result.cities, function(key, value) {
                                    $("#city").append('<option value="' + value.id +
                                        '">' + value.name + '</option>');
                                });

                                $('#city option[value="' + ui.item.city_id + '"]').prop(
                                    "selected", true);
                            }
                        });

                        return false;
                    }
                });

                // $('#datetimepicker_day_input').inputmask('dd/mm/yyyy');

                $('.datetimepicker_day').datetimepicker({
                    format: 'DD/MM/YYYY'
                });

                $('.datetimepicker_hour_begin').datetimepicker({
                    format: 'LT'
                });

                $('.datetimepicker_hour_end').datetimepicker({
                    format: 'LT'
                });

                $('#state').on('change', function() {
                    var uf = this.value;
                    $("#city").html('');
                    $.ajax({
                        url: "{{ url('places/get-cities-by-state') }}",
                        type: "POST",
                        data: {
                            uf: uf,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            console.log(result);
                            $('#city').html('<option value="">Selecione</option>');
                            $.each(result.cities, function(key, value) {
                                $("#city").append('<option value="' + value.id + '">' +
                                    value.name + '</option>');
                            });
                        }
                    });
                });

                var uf = $("#state").val();
                $("#city").html('');
                $.ajax({
                    url: "{{ url('places/get-cities-by-state') }}",
                    type: "POST",
                    data: {
                        uf: uf,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#city').html('<option value="">Selecione</option>');
                        city_id = $('#city_id_hidden').val();

                        $.each(result.cities, function(key, value) {
                            $("#city").append('<option value="' + value.id + '">' + value.name +
                                '</option>');
                        });

                        $('#city option[value=' + city_id + ']').attr('selected', 'selected');
                    }
                });

            });
        </script>
    @endpush
</x-app-layout>
