<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Painel de Controle</h1>
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
                                <h3 class="card-title">Estatísticas</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-6">
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{ count($event_count) }}</h3>

                                                <p>Eventos ativos</p>
                                            </div>
                                        </div>
                                    </div>
									<div class="col-lg-2 col-6">
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>{{ count($ingressos_cancelados) }}</h3>
                                                <p>Ingressos cancelados</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3>{{ count($ingressos_pendentes) }}</h3>
                                                <p>Ingressos pendentes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{ count($ingressos_confirmados) }}</h3>
                                                <p>Ingressos confirmados</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3>@money($total_pendente->total_pendente)</h3>
                                                <p>Valor total pendente</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>@money($total_confirmado->total_confirmado)</h3>

                                                <p>Valor total confirmado</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h4>Total de eventos ativos</h4>
                                        <div class="card-body table-responsive p-0">
                                            <table class="table table-head-fixed text-nowrap display" id="total_eventos">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Nome</th>
                                                        <th>Responsável</th>
                                                        <th>Local</th>
                                                        <th>Data do Evento</th>
                                                        <th>Data Criação</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($event_count as $event)
                                                        <tr>
                                                            <td>{{ $event->id }}</td>
                                                            <td>{{ $event->name }}</td>
                                                            <td>
                                                                {{ $event->admin_name }} <br>
                                                                <small>{{ $event->admin_email }}</small>
                                                            </td>
                                                            <td>{{ $event->place_name }}</td>
                                                            <td>
                                                                @if ($event->date_event_min == $event->date_event_max)
                                                                    {{ \Carbon\Carbon::parse($event->date_event_min)->format('j/m/Y') }}
                                                                @else
                                                                    De
                                                                    {{ \Carbon\Carbon::parse($event->date_event_min)->format('j/m/Y') }}
                                                                    <br /> a
                                                                    {{ \Carbon\Carbon::parse($event->date_event_max)->format('j/m/Y') }}
                                                                @endif
                                                            </td>
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
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
								<hr class="mt-5" />
								<div class="row">
									<div class="col-12">
										<h4 class="mb-3">Vendas confirmadas</h4>
										<div class="card-body table-responsive p-0">
											<table class="table table-head-fixed text-nowrap display" id="vendas_confirmadas">
												<thead>
													<tr>
														<th>#</th>
														<th>Hash</th>
														<th>Evento</th>
														<th>Comprador</th>
														<th>Forma pagamento</th>
														<th>Data da compra</th>
														<th>Mais detalhes</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($ingressos_confirmados as $order)
														<tr>
															<td> {{ $order->order_id }} </td>
															<td> {{ $order->order_hash }} </td>
															<td> {{ $order->event_name }} </td>
															<td> {{ $order->participante_name }}
																({{ $order->participante_cpf }}) <br />
																{{ $order->participante_email }}
															</td>
															<td>
																@if ($order->gatway_payment_method == 'credit')
																	Crédito
																@elseif($order->gatway_payment_method == 'boleto')
																	Boleto
																@elseif($order->gatway_payment_method == 'pix')
																	Pix
																@else
																	Não informado
																@endif
															</td>
															<td> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
															</td>
															<td> <a class="btn btn-info btn-sm mr-1"
																	href="{{ route('event.orders.details', $order->order_hash) }}">
																	<i class="fa-solid fa-plus"></i> Info
																</a>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<hr class="mt-5" />
								<div class="row">
									<div class="col-12">
										<h4 class="mb-3">Vendas pendentes</h4>
										<div class="card-body table-responsive p-0">
											<table class="table table-head-fixed text-nowrap display" id="vendas_pendentes">
												<thead>
													<tr>
														<th>#</th>
														<th>Hash</th>
														<th>Evento</th>
														<th>Comprador</th>
														<th>Forma pagamento</th>
														<th>Data da compra</th>
														<th>Mais detalhes</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($ingressos_pendentes as $order)
														<tr>
															<td> {{ $order->order_id }} </td>
															<td> {{ $order->order_hash }} </td>
															<td> {{ $order->event_name }} </td>
															<td> {{ $order->participante_name }}
																({{ $order->participante_cpf }}) <br />
																{{ $order->participante_email }}
															</td>
															<td>
																@if ($order->gatway_payment_method == 'credit')
																	Crédito
																@elseif($order->gatway_payment_method == 'boleto')
																	Boleto
																@elseif($order->gatway_payment_method == 'pix')
																	Pix
																@else
																	Não informado
																@endif
															</td>
															<td> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
															</td>
															<td> <a class="btn btn-info btn-sm mr-1"
																	href="{{ route('event.orders.details', $order->order_hash) }}">
																	<i class="fa-solid fa-plus"></i> Info
																</a>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<hr class="mt-5" />
								<div class="row">
									<div class="col-12">
										<h4 class="mb-3">Vendas canceladas</h4>
										<div class="card-body table-responsive p-0">
											<table class="table table-head-fixed text-nowrap display" id="vendas_canceladas">
												<thead>
													<tr>
														<th>#</th>
														<th>Hash</th>
														<th>Evento</th>
														<th>Comprador</th>
														<th>Forma pagamento</th>
														<th>Data da compra</th>
														<th>Mais detalhes</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($ingressos_cancelados as $order)
														<tr>
															<td> {{ $order->order_id }} </td>
															<td> {{ $order->order_hash }} </td>
															<td> {{ $order->event_name }} </td>
															<td> {{ $order->participante_name }}
																({{ $order->participante_cpf }}) <br />
																{{ $order->participante_email }}
															</td>
															<td>
																@if ($order->gatway_payment_method == 'credit')
																	Crédito
																@elseif($order->gatway_payment_method == 'boleto')
																	Boleto
																@elseif($order->gatway_payment_method == 'pix')
																	Pix
																@else
																	Não informado
																@endif
															</td>
															<td> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
															</td>
															<td> <a class="btn btn-info btn-sm mr-1"
																	href="{{ route('event.orders.details', $order->order_hash) }}">
																	<i class="fa-solid fa-plus"></i> Info
																</a>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
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
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

	@push('footer')
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

        <script>

            $(document).ready(function() {

                $('#total_eventos').DataTable({
                    language: {
                        "decimal": "",
                        "emptyTable": "Sem dados disponíveis na tabela",
                        "info": "Exibindo _START_ de _END_ de um total de _TOTAL_ registros",
                        "infoEmpty": "Exibindo 0 de 0 de um total de 0 registros",
                        "infoFiltered": "(filtrados do total de _MAX_ registros)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Exibir _MENU_ registros",
                        "loadingRecords": "Carregando...",
                        "processing": "",
                        "search": "Busca: ",
                        "zeroRecords": "Nenhum registro correspondente encontrado",
                        "paginate": {
                            "first": "Primeiro",
                            "last": "Último",
                            "next": "Próximo",
                            "previous": "Anterior"
                        },
                        "aria": {
                            "sortAscending": ": ative para classificar a coluna em ordem crescente",
                            "sortDescending": ": ativar para ordenar a coluna decrescente"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'csv',
                            text: 'CSV',
                            title: 'Total de eventos ativos - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripNewlines: false,
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            title: 'Total de eventos ativos - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'Total de eventos ativos - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripNewlines: false
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Total de eventos ativos - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripHtml: false
                            }
                        }
                    ]
                });

                $('#vendas_confirmadas').DataTable({
                    language: {
                        "decimal": "",
                        "emptyTable": "Sem dados disponíveis na tabela",
                        "info": "Exibindo _START_ de _END_ de um total de _TOTAL_ registros",
                        "infoEmpty": "Exibindo 0 de 0 de um total de 0 registros",
                        "infoFiltered": "(filtrados do total de _MAX_ registros)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Exibir _MENU_ registros",
                        "loadingRecords": "Carregando...",
                        "processing": "",
                        "search": "Busca: ",
                        "zeroRecords": "Nenhum registro correspondente encontrado",
                        "paginate": {
                            "first": "Primeiro",
                            "last": "Último",
                            "next": "Próximo",
                            "previous": "Anterior"
                        },
                        "aria": {
                            "sortAscending": ": ative para classificar a coluna em ordem crescente",
                            "sortDescending": ": ativar para ordenar a coluna decrescente"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'csv',
                            text: 'CSV',
                            title: 'Listagem de vendas confirmadas - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripNewlines: false,
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            title: 'Listagem de vendas confirmadas - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'Listagem de vendas confirmadas - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripNewlines: false
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Listagem de vendas confirmadas - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripHtml: false
                            }
                        }
                    ]
                });

                $('#vendas_pendentes').DataTable({
                    language: {
                        "decimal": "",
                        "emptyTable": "Sem dados disponíveis na tabela",
                        "info": "Exibindo _START_ de _END_ de um total de _TOTAL_ registros",
                        "infoEmpty": "Exibindo 0 de 0 de um total de 0 registros",
                        "infoFiltered": "(filtrados do total de _MAX_ registros)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Exibir _MENU_ registros",
                        "loadingRecords": "Carregando...",
                        "processing": "",
                        "search": "Busca: ",
                        "zeroRecords": "Nenhum registro correspondente encontrado",
                        "paginate": {
                            "first": "Primeiro",
                            "last": "Último",
                            "next": "Próximo",
                            "previous": "Anterior"
                        },
                        "aria": {
                            "sortAscending": ": ative para classificar a coluna em ordem crescente",
                            "sortDescending": ": ativar para ordenar a coluna decrescente"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'csv',
                            text: 'CSV',
                            title: 'Listagem de vendas pendentes - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripNewlines: false,
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            title: 'Listagem de vendas pendentes - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'Listagem de vendas pendentes - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripNewlines: false
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Listagem de vendas pendentes - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripHtml: false
                            }
                        }
                    ]
                });

                $('#vendas_canceladas').DataTable({
                    language: {
                        "decimal": "",
                        "emptyTable": "Sem dados disponíveis na tabela",
                        "info": "Exibindo _START_ de _END_ de um total de _TOTAL_ registros",
                        "infoEmpty": "Exibindo 0 de 0 de um total de 0 registros",
                        "infoFiltered": "(filtrados do total de _MAX_ registros)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Exibir _MENU_ registros",
                        "loadingRecords": "Carregando...",
                        "processing": "",
                        "search": "Busca: ",
                        "zeroRecords": "Nenhum registro correspondente encontrado",
                        "paginate": {
                            "first": "Primeiro",
                            "last": "Último",
                            "next": "Próximo",
                            "previous": "Anterior"
                        },
                        "aria": {
                            "sortAscending": ": ative para classificar a coluna em ordem crescente",
                            "sortDescending": ": ativar para ordenar a coluna decrescente"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'csv',
                            text: 'CSV',
                            title: 'Listagem de vendas canceladas - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripNewlines: false,
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            title: 'Listagem de vendas canceladas - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'Listagem de vendas canceladas - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripNewlines: false
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Listagem de vendas canceladas - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3],
                                stripHtml: false
                            }
                        }
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
