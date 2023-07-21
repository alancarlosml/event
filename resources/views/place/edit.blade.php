<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Locais</h1>
                    </div>
                    {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Layout</a></li>
                            <li class="breadcrumb-item active">Fixed Layout</li>
                        </ol>
                    </div> --}}
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
                                <h3 class="card-title">Editar local - {{ $place->name }}</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
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
                                <form method="POST" action="{{ route('place.update', $place->id) }}">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Local</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   placeholder="Local" value="{{ $place->name }}">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-10">
                                                <label for="address">Rua</label>
                                                <input type="text" class="form-control" id="address" name="address"
                                                       placeholder="Rua" value="{{ $place->address }}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="number">Número</label>
                                                <input type="text" class="form-control" id="number" name="number"
                                                       placeholder="Número" value="{{ $place->number }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="district">Bairro</label>
                                            <input type="text" class="form-control" id="district" name="district"
                                                   placeholder="Bairro" value="{{ $place->district }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="complement">Complemento</label>
                                            <input type="text" class="form-control" id="complement" name="complement"
                                                   placeholder="Complemento" value="{{ $place->complement }}">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label for="state">Estado</label>
                                                <select id="state" class="form-control" name="state">
                                                    <option>Selecione</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->id }}"
                                                                @if ($place->city_uf == $state->uf) selected @endif>
                                                            {{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="city">Cidade</label>
                                                <select id="city" class="form-control" name="city_id">
                                                    <option selected>Selecione</option>
                                                    <option>...</option>
                                                </select>
                                                <input type="hidden" name="city_id_hidden" id="city_id_hidden"
                                                       value="{{ $place->city_id }}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="zip">CEP</label>
                                                <input type="text" class="form-control" id="zip" name="zip"
                                                       placeholder="CEP" value="{{ $place->zip }}">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="form-check pb-3">
                                        <div class="custom-switch">
                                            <input type="checkbox" checked="checked" class="custom-control-input"
                                                   name="status" id="status" value="1">
                                            <label class="custom-control-label" for="status">Ativar</label>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @push('footer')
        <script>
            $(document).ready(function() {
                $('#state').on('change', function() {
                    var uf = this.value;
                    $("#city").html('');
                    $.ajax({
                        url: "{{ url('admin/places/get-cities-by-state') }}",
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
                    url: "{{ url('admin/places/get-cities-by-state') }}",
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
