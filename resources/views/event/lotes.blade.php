<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Eventos</h1>
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
                                <h3 class="card-title">Detalhes dos lotes - {{ $event->name }}</h3>

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
                                <div class="card-body">
                                    <div class="form-group text-right">
                                        <a href="{{ route('lote.create', $event->id) }}" class="btn btn-success">Cadastrar novo lote</a>
                                    </div>
                                    <table class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th style="width: 10%">Ordem</th>
                                                <th>Nome</th>
                                                <th>Valor</th>
                                                <th>Taxa</th>
                                                <th>Preço final</th>
                                                <th>Quantidade</th>
                                                <th>Visibilidade</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lotes as $lote)
                                                <tr>
                                                    <td>{{ $lote->id }}</td>
                                                    <td style="width: 10%"><input type="number" class="order_lote"
                                                            id="{{ $lote->id }}" value="{{ $lote->order }}"
                                                            style="width: 30%" min="1"></td>
                                                    <td>
                                                        <b>{{ $lote->name }}</b><br />
                                                        {{ $lote->description }}
                                                    </td>
                                                    <td>@money($lote->value)</td>
                                                    <td>@money($lote->tax)</td>
                                                    <td>@money($lote->final_value)</td>
                                                    <td>{{ $lote->quantity }}</td>
                                                    <td>
                                                        @if ($lote->visibility == 0)
                                                            Público
                                                        @else
                                                            Privado
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a class="btn btn-info btn-sm mr-1"
                                                                href="{{ route('lote.edit', $lote->id) }}">
                                                                <i class="fas fa-pencil-alt">
                                                                </i>
                                                                Editar
                                                            </a>
                                                            <form action="{{ route('lote.destroy', $lote->id) }}" method="POST">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <a class="btn btn-danger btn-sm mr-1" href="javascript:;" onclick="removeData({{ $lote->id }})">
                                                                    <i class="fas fa-trash"></i> Remover
                                                                </a>
                                                                <button class="d-none" id="btn-remove-hidden-{{ $lote->id }}">Remover</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <div class="modal fade modalMsgRemove"
                                                        id="modalMsgRemove-{{ $lote->id }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="modalMsgRemoveLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalMsgRemoveLabel">
                                                                        Remoção de evento</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Deseja realmente remover esse lote?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        id="btn-remove-ok-{{ $lote->id }}"
                                                                        onclick="removeSucc({{ $lote->id }})">Sim</button>
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
                                <form method="POST" action="{{ route('lote.save_lotes', $id) }}">
                                    @csrf
                                    @foreach ($lotes as $lote)
                                        <input type="hidden" name="order_lote[]" id="lote_{{ $lote->id }}"
                                            value="{{ $lote->id }}_{{ $lote->order }}">
                                    @endforeach
                                    <div class="card-footer">
                                        <a href="{{ route('event.index')}}" class="btn btn-success float-left">Voltar</a>
                                        <button type="submit" class="btn btn-primary float-right">Salvar</button>
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
    @push('footer')
        <script>
            function removeData(id) {
                $('#modalMsgRemove-' + id).modal('show');
            }

            function removeSucc(id) {
                $('#btn-remove-hidden-' + id).click();
            }
            $(document).ready(function() {
                $('.order_lote').change(function() {
                    id = $(this).attr('id');
                    value = $(this).val();
                    console.log(value);
                    $('#lote_' + id).val(id + '_' + value);
                });
            });
        </script>
    @endpush
</x-app-layout>
