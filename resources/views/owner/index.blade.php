<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Organizadores</h1>
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
                    <h3 class="card-title">Listar todos</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Data Criação</th>
                            <th>Status</th>
                            <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($owners as $owner)
                                <tr>
                                    <td>{{$owner->id}}</td>
                                    <td>{{$owner->name}}</td>
                                    <td>{{$owner->email}}</td>
                                    <td>{{ \Carbon\Carbon::parse($owner->created_at)->format('j/m/Y h:i') }}</td>
                                    <td>@if($owner->status == 1) <span class="badge badge-success">Ativo</span> @else <span class="badge badge-danger">Não ativo</span> @endif</td>
                                    <td>
                                        <div class="d-flex">
                                            <a class="btn btn-primary btn-sm mr-1" href="{{route('owner.show', $owner->id)}}">
                                                <i class="fas fa-eye">
                                                </i>
                                                Detalhes
                                            </a>
                                            {{-- <a href="{{route('owner.show', $student->id)}}" class="btn btn-info m-1">Details</a> --}}
                                            {{-- <a href="{{route('owner.edit', $owner->id)}}" class="btn btn-primary m-1">Editar</a> --}}
                                            <a class="btn btn-info btn-sm mr-1" href="{{route('owner.edit', $owner->id)}}">
                                                <i class="fas fa-pencil-alt">
                                                </i>
                                                Editar
                                            </a>
                                            <form action="{{ route('owner.destroy', $owner->id) }}" method="POST">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <a class="btn btn-danger btn-sm mr-1"  href="javascript:;" onclick="removeData({{$owner->id}})">
                                                    <i class="fas fa-trash">
                                                    </i>
                                                    Remover
                                                </a>
                                                {{-- <a class="btn btn-danger m-1 btn-remove" href="javascript:;" onclick="removeData({{$owner->id}})">Remover</a> --}}
                                                <button class="d-none" id="btn-remove-hidden-{{$owner->id}}">Remover</button>
                                            </form>
                                        </div>
                                    </td>
                                    <div class="modal fade modalMsgRemove" id="modalMsgRemove-{{$owner->id}}" tabindex="-1" role="dialog" aria-labelledby="modalMsgRemoveLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalMsgRemoveLabel">Remoção de usuário</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Deseja realmente remover esse organizador?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" id="btn-remove-ok-{{$owner->id}}" onclick="removeSucc({{$owner->id}})">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

    @push('footer')
        <script>
            function removeData(id){
                $('#modalMsgRemove-' + id).modal('show');
            }

            function removeSucc(id){
                $('#btn-remove-hidden-' + id).click();
            }
            $(document).ready(function() {
            });
        </script>
    @endpush
</x-app-layout>