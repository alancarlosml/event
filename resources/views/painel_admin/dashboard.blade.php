<x-site-layout>
    @push('head')
        <!-- Modern Admin CSS -->
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush
    
    <main id="main">
        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="{{ route('event_home.dashboard') }}">Dashboard</a></li>
                </ol>
                <h2>Dashboard - Super Administrador</h2>
            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page">
            <div class="container">
                <!-- Toast Container -->
                <div id="toast-container" class="toast-container"></div>

                <!-- Estatísticas -->
                <div class="row mb-4 g-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-card-content">
                                <div class="stat-card-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="stat-card-info">
                                    <h3 class="stat-card-value">{{ count($event_count) }}</h3>
                                    <p class="stat-card-label">Eventos ativos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="stat-card stat-card-success">
                            <div class="stat-card-content">
                                <div class="stat-card-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-card-info">
                                    <h3 class="stat-card-value">{{ count($ingressos_confirmados) }}</h3>
                                    <p class="stat-card-label">Ingressos confirmados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="stat-card stat-card-warning">
                            <div class="stat-card-content">
                                <div class="stat-card-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-card-info">
                                    <h3 class="stat-card-value">{{ count($ingressos_pendentes) }}</h3>
                                    <p class="stat-card-label">Ingressos pendentes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="stat-card stat-card-danger">
                            <div class="stat-card-content">
                                <div class="stat-card-icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="stat-card-info">
                                    <h3 class="stat-card-value">{{ count($ingressos_cancelados) }}</h3>
                                    <p class="stat-card-label">Ingressos cancelados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="stat-card stat-card-info">
                            <div class="stat-card-content">
                                <div class="stat-card-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="stat-card-info">
                                    <h3 class="stat-card-value">@money($total_confirmado->total_confirmado ?? 0)</h3>
                                    <p class="stat-card-label">Valor total confirmado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="stat-card stat-card-secondary">
                            <div class="stat-card-content">
                                <div class="stat-card-icon">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div class="stat-card-info">
                                    <h3 class="stat-card-value">@money($total_pendente->total_pendente ?? 0)</h3>
                                    <p class="stat-card-label">Valor total pendente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Vendas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h3 class="card-title"><i class="fas fa-chart-line me-2 text-primary"></i>Vendas nos Últimos 30 Dias</h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div class="chart-header mb-3">
                                        <div class="chart-filters">
                                            <button class="chart-filter-btn active" data-period="30">30 dias</button>
                                            <button class="chart-filter-btn" data-period="7">7 dias</button>
                                            <button class="chart-filter-btn" data-period="90">90 dias</button>
                                        </div>
                                    </div>
                                    <canvas id="salesChart" style="max-height: 400px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Eventos -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h3 class="card-title"><i class="fas fa-calendar-check me-2 text-primary"></i>Total de Eventos Ativos</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover display" id="total_eventos">
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
                                                        {{ $event->admin_name ?? 'N/A' }} <br>
                                                        <small>{{ $event->admin_email ?? 'N/A' }}</small>
                                                    </td>
                                                    <td>{{ $event->place_name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($event->date_event_min == $event->date_event_max)
                                                            {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }}
                                                        @else
                                                            De {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }}
                                                            <br /> a {{ \Carbon\Carbon::parse($event->date_event_max)->format('d/m/Y') }}
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        @if (empty($event->place_name) || empty($event->admin_name) || empty($event->event_date) || empty($event->lote_name))
                                                            <span class="badge bg-danger">Incompleto</span>
                                                        @else
                                                            @if ($event->status == 1)
                                                                <span class="badge bg-success">Ativo</span>
                                                            @else
                                                                <span class="badge bg-warning">Não ativo</span>
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
                    </div>
                </div>

                <!-- Vendas Confirmadas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h3 class="card-title"><i class="fas fa-check-circle me-2 text-success"></i>Vendas Confirmadas</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover display" id="vendas_confirmadas">
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
                                                    <td>{{ $order->order_id }}</td>
                                                    <td>{{ $order->order_hash }}</td>
                                                    <td>{{ $order->event_name }}</td>
                                                    <td>
                                                        {{ $order->participante_name }}
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
                                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('event_home.order.details', $order->order_hash) }}" data-bs-toggle="tooltip" title="Ver detalhes">
                                                            <i class="fas fa-eye"></i> Detalhes
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

                <!-- Vendas Pendentes -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h3 class="card-title"><i class="fas fa-clock me-2 text-warning"></i>Vendas Pendentes</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover display" id="vendas_pendentes">
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
                                                    <td>{{ $order->order_id }}</td>
                                                    <td>{{ $order->order_hash }}</td>
                                                    <td>{{ $order->event_name }}</td>
                                                    <td>
                                                        {{ $order->participante_name }}
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
                                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('event_home.order.details', $order->order_hash) }}" data-bs-toggle="tooltip" title="Ver detalhes">
                                                            <i class="fas fa-eye"></i> Detalhes
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

                <!-- Vendas Canceladas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h3 class="card-title"><i class="fas fa-times-circle me-2 text-danger"></i>Vendas Canceladas</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover display" id="vendas_canceladas">
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
                                                    <td>{{ $order->order_id }}</td>
                                                    <td>{{ $order->order_hash }}</td>
                                                    <td>{{ $order->event_name }}</td>
                                                    <td>
                                                        {{ $order->participante_name }}
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
                                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('event_home.order.details', $order->order_hash) }}" data-bs-toggle="tooltip" title="Ver detalhes">
                                                            <i class="fas fa-eye"></i> Detalhes
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
            </div>
        </section>
    </main>

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
                
                // Dados iniciais vazios (pode ser expandido no futuro)
                const chartLabels = [];
                const chartConfirmed = [];
                const chartPending = [];
                
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
            });
        </script>
        
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

        <script>
            $(document).ready(function() {
                // Inicializar tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                // Configuração padrão do DataTables
                const dataTableConfig = {
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
                    buttons: ['csv', 'excel', 'pdf', 'print']
                };

                $('#total_eventos').DataTable(dataTableConfig);
                $('#vendas_confirmadas').DataTable(dataTableConfig);
                $('#vendas_pendentes').DataTable(dataTableConfig);
                $('#vendas_canceladas').DataTable(dataTableConfig);
            });
        </script>
    @endpush
</x-site-layout>

