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
                                <h3 class="card-title">Detalhes do local - {{ $place->name }}</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="id">ID</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $place->id }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Nome</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $place->name }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Endereço</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $place->address }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Número</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $place->number }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="zip">Bairro</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $place->zip }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="complement">Complemento</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $place->complement }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="cidade">Cidade</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $city_uf->name }}-{{ $city_uf->uf }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="created_at">Data de criação</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ \Carbon\Carbon::parse($place->created_at)->format('j/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Ativo</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            @if ($place->status == 1)
                                                Sim
                                            @else
                                                Não
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- /.card-body -->
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
</x-app-layout>
