<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Áreas</h1>
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
                                <h3 class="card-title">Incluir nova - {{ $category->description }}</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
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
                            <div class="card-body table-responsive p-0">
                                <form method="POST" action="{{ route('area.store', $category->id) }}">
                                    @csrf
                                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Nome</label>
                                            <input type="text" class="form-control col-4" id="name"
                                                name="name" placeholder="Descrição">
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
                                    <!-- /.card-body -->
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
</x-app-layout>
