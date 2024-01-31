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
                                <h3 class="card-title">Listar todos</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-head-fixed text-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Evento</th>
                                            <th>Responsável</th>
                                            <th>Local</th>
                                            {{-- <th>Data do Evento</th> --}}
                                            <th>Data Criação</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($events as $event)
                                            <tr @if ($event->place_name == '' || $event->participante_name == '' || $event->event_date == '' || $event->lote_name == '') style="background:#faceca" @endif>
                                                <td>{{ $event->id }}</td>
                                                <td>
                                                    <b>Nome: </b> {{ $event->name }} <br>
                                                    <b>Data evento: </b> 
                                                    @if ($event->date_event_min == $event->date_event_max)
                                                        {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }}
                                                    @else
                                                        De
                                                        {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }}
                                                        a
                                                        {{ \Carbon\Carbon::parse($event->date_event_max)->format('d/m/Y') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $event->admin_name }} <br>
                                                    <small>{{ $event->admin_email }}</small>
                                                </td>
                                                <td>{{ $event->place_name }}</td>
                                                {{-- <td>
                                                    @if ($event->date_event_min == $event->date_event_max)
                                                        {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }}
                                                    @else
                                                        De
                                                        {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }}
                                                        <br /> a
                                                        {{ \Carbon\Carbon::parse($event->date_event_max)->format('d/m/Y') }}
                                                    @endif
                                                </td> --}}
                                                <td>{{ \Carbon\Carbon::parse($event->created_at)->format('j/m/Y H:i') }}
                                                </td>
                                                <td>
                                                    @if ($event->place_name == '' || $event->participante_name == '' || $event->event_date == '' || $event->lote_name == '')
                                                        <span class="badge badge-danger">Incompleto</span>
                                                    @else
                                                        @if ($event->status == 1)
                                                            <span class="badge badge-success">Ativo</span>
                                                        @else
                                                            <span class="badge badge-warning">Não ativo</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a class="btn btn-success btn-sm mr-1"
                                                            href="{{ route('event.reports', $event->id) }}">
                                                            <i class="fa-solid fa-chart-pie"></i>
                                                            Relatórios
                                                        </a>
                                                        <a class="btn btn-secondary btn-sm mr-1"
                                                            href="{{ route('event.lotes', $event->id) }}">
                                                            <i class="fa-solid fa-tags"></i>
                                                            Lotes
                                                        </a>
                                                        <a class="btn btn-info btn-sm mr-1"
                                                            href="{{ route('event.questions', $event->id) }}">
                                                            <i class="fa-solid fa-question"></i>
                                                            Questionário
                                                        </a>
                                                        <a class="btn btn-warning btn-sm mr-1"
                                                            href="{{ route('event.coupons', $event->id) }}">
                                                            <i class="fa-solid fa-percent"></i>
                                                            Cupons
                                                        </a>
                                                        <div class="btn-group" role="group">
                                                            <button id="btnGroupDrop" type="button"
                                                                class="btn btn-primary btn-sm mr-1 dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="fa-solid fa-gear"></i> Mais
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('event.show', $event->id) }}">
                                                                    <i class="fas fa-eye"></i>
                                                                    Detalhes
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('event.edit', $event->id) }}">
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                    Editar
                                                                </a>
                                                                <form action="{{ route('event.destroy', $event->id) }}"
                                                                    method="POST">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <input type="hidden" name="_token"
                                                                        value="{{ csrf_token() }}">
                                                                    <a href="javascript:;" class="dropdown-item"
                                                                        onclick="removeData({{ $event->id }})">
                                                                        <i class="fas fa-trash"></i>
                                                                        Remover
                                                                    </a>
                                                                    <button class="d-none"
                                                                        id="btn-remove-hidden-{{ $event->id }}">Remover</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <div class="modal fade modalMsgRemove"
                                                    id="modalMsgRemove-{{ $event->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="modalMsgRemoveLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalMsgRemoveLabel">Remoção
                                                                    de evento</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Deseja realmente remover esse evento?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger"
                                                                    id="btn-remove-ok-{{ $event->id }}"
                                                                    onclick="removeSucc({{ $event->id }})">Sim</button>
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
            $(document).ready(function() {});
        </script>
    @endpush
</x-app-layout>
