<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Categorias</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Layout</a></li>
                <li class="breadcrumb-item active">Fixed Layout</li>
                </ol>
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
                    <h3 class="card-title">Detalhes da categoria</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="id">ID</label>
                            <p class="text-muted" style="font-size: 18px">
                                {{$category->id}}
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <p class="text-muted" style="font-size: 18px">
                                {{$category->description}}
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="created_at">Data de criação</label>
                            <p class="text-muted" style="font-size: 18px">
                                {{ \Carbon\Carbon::parse($category->created_at)->format('j/m/Y h:i') }}
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="status">Ativo</label>
                            <p class="text-muted" style="font-size: 18px">
                                @if($category->status == 1) 
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
                <div class="card-footer">
                    Footer
                </div>
                <!-- /.card-footer-->
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
