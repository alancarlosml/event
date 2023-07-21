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
                                <h3 class="card-title">Listar todas - {{ $category->description }}</h3>

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
                                <div class="card-body">
                                    <div class="form-group text-right">
                                        <a href="{{ route('area.create', $category->id) }}"
                                            class="btn btn-success">Cadastrar nova área</a>
                                    </div>
                                    <table class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($areas as $area)
                                                <tr>
                                                    <td>{{ $area->id }}</td>
                                                    <td>{{ $area->name }}</td>
                                                    <td>
                                                        @if ($area->status == 1)
                                                            <span class="badge badge-success">Ativo</span>
                                                        @else
                                                            <span class="badge badge-danger">Não ativo</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a class="btn btn-info btn-sm mr-1"
                                                                href="{{ route('area.edit', [$category->id, $area->id]) }}">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Editar
                                                            </a>
                                                            <form
                                                                action="{{ route('area.destroy', [$category->id, $area->id]) }}"
                                                                method="POST">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="_token"
                                                                    value="{{ csrf_token() }}">
                                                                <a class="btn btn-danger btn-sm mr-1"
                                                                    href="javascript:;"
                                                                    onclick="removeData({{ $area->id }})">
                                                                    <i class="fas fa-trash"></i>
                                                                    Remover
                                                                </a>
                                                                <button class="d-none"
                                                                    id="btn-remove-hidden-{{ $area->id }}">Remover</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <div class="modal fade modalMsgRemove"
                                                        id="modalMsgRemove-{{ $area->id }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="modalMsgRemoveLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalMsgRemoveLabel">
                                                                        Remoção de cupom</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Deseja realmente remover essa área?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        id="btn-remove-ok-{{ $area->id }}"
                                                                        onclick="removeSucc({{ $area->id }})">Sim</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Não</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
            function removeData(id) {
                $('#modalMsgRemove-' + id).modal('show');
            }

            function removeSucc(id) {
                $('#btn-remove-hidden-' + id).click();
            }
            $(document).ready(function() {

            });
        </script>
    @endpush
</x-app-layout>
