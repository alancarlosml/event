<x-app-layout>
    @push('head')
        <!-- Dashboard Improvements CSS -->
        <link rel="stylesheet" href="{{ asset('assets_admin/css/dashboard-improvements.css') }}" type="text/css">
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush
    
    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>
    
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
                                            <div class="icon">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                    </div>
									<div class="col-lg-2 col-6">
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>{{ count($ingressos_cancelados) }}</h3>
                                                <p>Ingressos cancelados</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-times-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3>{{ count($ingressos_pendentes) }}</h3>
                                                <p>Ingressos pendentes</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="small-box bg-success" data-bs-toggle="tooltip" data-bs-placement="top" 
                                             title="Total de ingressos com pagamento confirmado">
                                            <div class="inner">
                                                <h3>{{ count($ingressos_confirmados) }}</h3>
                                                <p>Ingressos confirmados</p>
                                                @if(isset($confirmedCountChange))
                                                    <small class="stat-change {{ $confirmedCountChange >= 0 ? 'positive' : 'negative' }}">
                                                        @if($confirmedCountChange >= 0)
                                                            <i class="fas fa-arrow-up"></i>
                                                        @else
                                                            <i class="fas fa-arrow-down"></i>
                                                        @endif
                                                        {{ number_format(abs($confirmedCountChange), 1) }}% vs período anterior
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3>@money($total_pendente->total_pendente ?? 0)</h3>
                                                <p>Valor total pendente</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-6">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>@money($total_confirmado->total_confirmado ?? 0)</h3>
                                                <p>Valor total confirmado</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Gráfico de Vendas -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="chart-container">
                                            <div class="chart-header">
                                                <h3 class="chart-title">Vendas nos Últimos 30 Dias</h3>
                                                <div class="chart-filters">
                                                    <button class="chart-filter-btn active" data-period="30">30 dias</button>
                                                    <button class="chart-filter-btn" data-period="7">7 dias</button>
                                                    <button class="chart-filter-btn" data-period="90">90 dias</button>
                                                </div>
                                            </div>
                                            <canvas id="salesChart"></canvas>
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
                                                                    {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }}
                                                                @else
                                                                    De
                                                                    {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }}
                                                                    <br /> a
                                                                    {{ \Carbon\Carbon::parse($event->date_event_max)->format('d/m/Y') }}
                                                                @endif
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') }}
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
																@if ($order->gatway_payment_method == 'credit_card')
																	Crédito
																@elseif($order->gatway_payment_method == 'ticket')
																	Boleto
																@elseif($order->gatway_payment_method == 'bank_transfer')
																	Pix
                                                                @elseif($order->gatway_payment_method == 'pix')
																	Pix
                                                                @elseif($order->gatway_payment_method == 'free')
                                                                    Grátis
																@else
																	Não informado
																@endif
															</td>
															<td> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
															</td>
															<td> <a class="btn btn-info btn-sm mr-1"
																	href="{{ route('event.orders.details', $order->order_id) }}">
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
																@if ($order->gatway_payment_method == 'credit_card')
																	Crédito
																@elseif($order->gatway_payment_method == 'ticket')
																	Boleto
																@elseif($order->gatway_payment_method == 'bank_transfer')
																	Pix
                                                                @elseif($order->gatway_payment_method == 'pix')
																	Pix
                                                                @elseif($order->gatway_payment_method == 'free')
                                                                    Grátis
																@else
																	Não informado
																@endif
															</td>
															<td> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
															</td>
															<td> <a class="btn btn-info btn-sm mr-1"
																	href="{{ route('event.orders.details', $order->order_id) }}">
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
																@if ($order->gatway_payment_method == 'credit_card')
																	Crédito
																@elseif($order->gatway_payment_method == 'ticket')
																	Boleto
																@elseif($order->gatway_payment_method == 'bank_transfer')
																	Pix
                                                                @elseif($order->gatway_payment_method == 'pix')
																	Pix
                                                                @elseif($order->gatway_payment_method == 'free')
                                                                    Grátis
																@else
																	Não informado
																@endif
															</td>
															<td> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
															</td>
															<td> <a class="btn btn-info btn-sm mr-1"
																	href="{{ route('event.orders.details', $order->order_id) }}">
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
        <!-- Toast Notifications JS -->
        <script>
            function showToast(message, type = 'info', duration = 3000) {
                const container = document.getElementById('toast-container');
                if (!container) return;
                
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                
                const icons = {
                    success: 'fa-check-circle',
                    error: 'fa-exclamation-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle'
                };
                
                toast.innerHTML = `
                    <div class="toast-icon">
                        <i class="fas ${icons[type] || icons.info}"></i>
                    </div>
                    <div class="toast-message">${message}</div>
                    <button class="toast-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                container.appendChild(toast);
                
                setTimeout(() => toast.classList.add('show'), 10);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            }
        </script>
        
        <!-- Chart.js Configuration -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('salesChart');
                if (!ctx) return;
                
                const chartLabels = @json($chartLabels ?? []);
                const chartConfirmed = @json($chartConfirmed ?? []);
                const chartPending = @json($chartPending ?? []);
                
                const salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Vendas Confirmadas',
                            data: chartConfirmed,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }, {
                            label: 'Vendas Pendentes',
                            data: chartPending,
                            borderColor: '#ffc107',
                            backgroundColor: 'rgba(255, 193, 7, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14,
                                    weight: '600'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'R$ ' + value.toLocaleString('pt-BR', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        });
                                    },
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
                
                // Filtros de período (para implementação futura)
                document.querySelectorAll('.chart-filter-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.chart-filter-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        const period = this.dataset.period;
                        updateChart(period);
                    });
                });
                
                function updateChart(period) {
                    // Mostrar loading
                    ctx.style.opacity = '0.5';
                    
                    fetch(`{{ route('dashboard.chart-data') }}?period=${period}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Atualizar dados do gráfico
                        salesChart.data.labels = data.labels;
                        salesChart.data.datasets[0].data = data.confirmed;
                        salesChart.data.datasets[1].data = data.pending;
                        salesChart.update();
                        
                        ctx.style.opacity = '1';
                        showToast(`Gráfico atualizado: ${period} dias`, 'success');
                    })
                    .catch(error => {
                        console.error('Erro ao atualizar gráfico:', error);
                        ctx.style.opacity = '1';
                        showToast('Erro ao atualizar gráfico', 'error');
                    });
                }
            });
        </script>
        
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
                    order: [[0, 'desc']],
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
                    order: [[0, 'desc']],
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
                    order: [[0, 'desc']],
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
                    order: [[0, 'desc']],
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
