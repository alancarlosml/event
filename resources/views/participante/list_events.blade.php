<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Participante - Informações sobre os eventos</h1>
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
                                <h3 class="card-title">Inscrições realizadas</h3>

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
                                            <th>Hash</th>
                                            <th>Evento</th>
                                            <th>Data uso</th>
                                            <th>Referencia</th>
                                            <th>Forma pagamento</th>
                                            <th>Data compra</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($participante->inscricoes as $order)
                                            <tr @if ((isset($order->event->place->name) && $order->event->place->name == '') || $order->event->get_participante_admin()->name == '' || (count($order->event->event_dates) == 0) || (count($order->event->lotes) == 0)) style="background:#faceca" @endif>
                                                <td>{{ $order->hash }}</td>
                                                <td>
                                                    <b>Nome: </b> {{ $order->event->name }} <br>
                                                    <b>Data evento: </b> 
                                                    @if ($order->event->date_event_min == $order->event->date_event_max)
                                                        {{ \Carbon\Carbon::parse($order->event->date_event_min)->format('d/m/Y') }}
                                                    @else
                                                        De
                                                        {{ \Carbon\Carbon::parse($order->event->date_event_min)->format('d/m/Y') }}
                                                        a
                                                        {{ \Carbon\Carbon::parse($order->event->date_event_max)->format('d/m/Y') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($order->date_used)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ $order->gatway_reference }}</td>
                                                <td>
                                                    @if ($order->gatway_payment_method == 'credit_card')
                                                        Crédito
                                                    @elseif($order->gatway_payment_method == 'ticket')
                                                        Boleto
                                                    @elseif($order->gatway_payment_method == 'bank_transfer')
                                                        Pix
                                                    @elseif($order->gatway_payment_method == 'free')
                                                        Grátis
                                                    @else
                                                        Não informado
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                                                <td>
                                                    <a href="{{ route('event.show', $order->event->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                        Detalhes
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Eventos criados</h3>

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
                                            <th>Data Criação</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($participante->eventos as $event)
                                            <tr @if ((isset($event->place->name) && $event->place->name == '') || $event->get_participante_admin()->name == '' || (count($event->event_dates) == 0) || (count($event->lotes) == 0)) style="background:#faceca" @endif>
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
                                                    {{ $event->get_participante_admin()->name }} <br>
                                                    <small>{{ $event->get_participante_admin()->email }}</small>
                                                </td>
                                                <td>{{ isset($event->place->name) && $event->place->name != '' ? $event->place->name : '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>
                                                    @if ((isset($event->place->name) && $event->place->name == '') || $event->get_participante_admin()->name == '' || (count($event->event_dates) == 0) || (count($event->lotes) == 0))
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
                                                    <a href="{{ route('event.show', $event->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                        Detalhes
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
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
