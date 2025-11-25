<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                </ol>
                <h2>Relatórios do Evento: {{ $event->name }}</h2>

            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="mb-3 ps-3 pe-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                            <strong>Erros encontrados:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                </div>

                <!-- Informações do Evento -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informações do Evento</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Data(s) do Evento:</strong><br>
                                @if($event_dates && $event_dates->count() > 0)
                                    @foreach($event_dates as $date)
                                        <span class="badge bg-info me-1">
                                            {{ \Carbon\Carbon::parse($date->date)->format('d/m/Y') }} 
                                            @if($date->time_begin)
                                                às {{ \Carbon\Carbon::parse($date->time_begin)->format('H:i') }}
                                            @endif
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-map-marker-alt me-2 text-danger"></i>Local:</strong><br>
                                @if($place)
                                    {{ $place->name }}
                                    @if($place->get_city)
                                        - {{ $place->get_city->name }}/{{ $place->get_city->uf }}
                                    @endif
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-ticket-alt me-2 text-success"></i>Status:</strong><br>
                                @if($event->status == 1)
                                    <span class="badge bg-success">Publicado</span>
                                @else
                                    <span class="badge bg-secondary">Rascunho</span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-users me-2 text-warning"></i>Capacidade:</strong><br>
                                <span class="badge bg-primary">{{ number_format($capacidade_total, 0, ',', '.') }} ingressos</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPIs Principais -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="d-flex justify-content-between mb-3">
                            <span><i class="fas fa-chart-line me-2"></i>Indicadores Principais</span>
                            <a href="#" class="button-print" onclick="window.print();" title="Imprimir relatório">
                                <img src="{{ asset('assets/img/print-pdf.jpg') }}" alt="Imprimir" width="36px">
                            </a>
                        </h4>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ingressos Vendidos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($ingressos_vendidos, 0, ',', '.') }}</div>
                                        <small class="text-muted">de {{ number_format($capacidade_total, 0, ',', '.') }} disponíveis</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Taxa de Ocupação</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($taxa_ocupacao, 1, ',', '.') }}%</div>
                                        <small class="text-muted">do evento preenchido</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-percentage fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ticket Médio</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">@money($ticket_medio ?? 0)</div>
                                        <small class="text-muted">por participante confirmado</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Check-in Realizado</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $checkin_stats->total_checkin ?? 0 }}
                                        </div>
                                        <small class="text-muted">de {{ $checkin_stats->total_confirmados ?? 0 }} confirmados</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumo Financeiro -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Resumo Financeiro</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-4 mb-3">
                                <div class="info-box bg-light justify-content-center">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Total Confirmado</span>
                                        <span class="info-box-number text-center text-success mb-0 h5">@money($resumo->total_confirmado ?? 0)</span>
                                        <small class="text-center mb-0">{{ $resumo->confirmado ?? 0 }} participantes</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mb-3">
                                <div class="info-box bg-light justify-content-center">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Total Pendente</span>
                                        <span class="info-box-number text-center text-danger mb-0 h5">@money($resumo->total_pendente ?? 0)</span>
                                        <small class="text-center mb-0">{{ $resumo->pendente ?? 0 }} participantes</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mb-3">
                                <div class="info-box bg-light justify-content-center">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Total Geral</span>
                                        <span class="info-box-number text-center text-primary mb-0 h5">@money(($resumo->total_confirmado ?? 0) + ($resumo->total_pendente ?? 0))</span>
                                        <small class="text-center mb-0">{{ $resumo->geral ?? 0 }} participantes</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h5><i class="fas fa-calculator me-2"></i>Detalhamento Financeiro</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width:60%">Total Confirmado:</th>
                                            <td class="text-end fw-bold text-success">@money($resumo->total_confirmado ?? 0)</td>
                                        </tr>
                                        <tr>
                                            <th>Taxa de Serviço ({{ number_format(($config->tax ?? 0) * 100, 2, ',', '.') }}%):</th>
                                            <td class="text-end text-danger">- @money($resumo->total_taxa ?? 0)</td>
                                        </tr>
                                        <tr class="table-success" style="border-top: solid 2px #28a745">
                                            <th class="fw-bold">Valor Líquido a Receber:</th>
                                            <td class="text-end fw-bold h5 mb-0">@money($resumo->total_liquido ?? 0)</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5 class="text-center"><i class="fas fa-credit-card me-2"></i>Vendas por Meio de Pagamento</h5>
                                <div class="table-responsive">
                                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Vendas ao Longo do Tempo -->
                @if($vendas_por_periodo && $vendas_por_periodo->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>Evolução de Vendas</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
                @endif

                <!-- Gráfico de Vendas por Lote -->
                @if($vendas_por_lote && $vendas_por_lote->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Vendas por Lote</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="lotesChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
                @endif

                <hr />
                            <div class="row pt-3">
                                <div class="col-12">
                                    <h4 class="mb-3">Listagem de vendas realizadas ({{ count($all_orders) }})</h4>
                                    {{-- <div class="info-box bg-light" style="margin-top: 20px">
                                        <div class="container-fluid info-box-content">
                                            <h6 class="text-left display-5">Opções de busca</h6>
                                            <form action="enhanced-results.html">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label>Ativo</label>
                                                                    <select class="form-control select2" style="width: 100%;">
                                                                        <option>Selecione</option>
                                                                        <option>Images</option>
                                                                        <option>Video</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-3">
                                                                <div class="form-group">
                                                                    <label>Pago</label>
                                                                    <select class="form-control select2" style="width: 100%;">
                                                                        <option>Selecione</option>
                                                                        <option>ASC</option>
                                                                        <option>DESC</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-3">
                                                                <div class="form-group">
                                                                    <label>Lote</label>
                                                                    <select class="form-control select2" style="width: 100%;">
                                                                        <option>Selecione</option>
                                                                        <option>Date</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-10">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input type="search" class="form-control" placeholder="N° inscrição, nome, e-mail"/>
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                            <div class="col-2 text-right">
                                                                <input class="btn btn-primary" type="submit" value="Buscar" style="width: 100%;">
                                                            </div> 
                                                        </div>  
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div> --}}
                                    <div class="card-body table-responsive p-0">
                                        <!-- Mobile Card View -->
                                        <div class="d-md-none">
                                            @foreach ($all_orders as $order)
                                                <div class="card mb-2 border">
                                                    <div class="card-body p-3">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <strong>ID:</strong> {{ $order->order_id }}
                                                            </div>
                                                            <div class="col-6 text-end">
                                                                @if ($order->situacao == 1)
                                                                    <span class="badge bg-success">Confirmado</span>
                                                                @elseif($order->situacao == 2)
                                                                    <span class="badge bg-warning">Pendente</span>
                                                                @elseif($order->situacao == 3)
                                                                    <span class="badge bg-danger">Cancelado</span>
                                                                @else
                                                                    <span class="badge bg-secondary">-</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <strong>Comprador:</strong><br>
                                                            <small>{{ htmlspecialchars($order->participante_name) }}</small><br>
                                                            <small class="text-muted">{{ htmlspecialchars($order->participante_email) }}</small>
                                                        </div>
                                                        <div class="mt-2">
                                                            <strong>Pagamento:</strong>
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
                                                        </div>
                                                        <div class="mt-2">
                                                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</small>
                                                        </div>
                                                        <div class="mt-3">
                                                            <a class="btn btn-info btn-sm w-100" href="{{ route('event_home.order.details', $order->order_hash) }}" aria-label="Ver detalhes do pedido {{ $order->order_id }}">
                                                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Ver Detalhes
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Desktop Table View -->
                                        <div class="d-none d-md-block">
                                            <table class="table table-head-fixed text-nowrap display" id="participantes_table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Hash</th>
                                                        <th>Comprador</th>
                                                        <th>Forma pagamento</th>
                                                        <th>Situação</th>
                                                        <th>Data da compra</th>
                                                        <th>Mais detalhes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($all_orders as $order)
                                                        <tr>
                                                            <td>{{ $order->order_id }}</td>
                                                            <td>{{ htmlspecialchars($order->order_hash) }}</td>
                                                            <td>{{ htmlspecialchars($order->participante_name) }}
                                                                ({{ htmlspecialchars($order->participante_cpf) }}) <br />
                                                                {{ htmlspecialchars($order->participante_email) }}
                                                            </td>
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
                                                            <td>
                                                                @if ($order->situacao == 1)
                                                                    <span class="badge bg-success">Confirmado</span>
                                                                @elseif($order->situacao == 2)
                                                                    <span class="badge bg-warning">Pendente</span>
                                                                @elseif($order->situacao == 3)
                                                                    <span class="badge bg-danger">Cancelado</span>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                                            <td>
                                                                <a class="btn btn-info btn-sm mr-1" href="{{ route('event_home.order.details', $order->order_hash) }}" aria-label="Ver detalhes do pedido {{ $order->order_id }}">
                                                                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Info
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
                            <hr class="mt-5" />
                            <div class="row pt-3">
                                <div class="col-12">
                                    <h4>Resumo de inscritos por lote ({{ count($lotes) }})</h4>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-head-fixed text-nowrap display" id="participantes_inscritos_lote">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Lote</th>
                                                    <th>Limite</th>
                                                    <th>Confirmados</th>
                                                    <th>Pendentes</th>
                                                    <th>Restante</th>
                                                    <th>Total confirmado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($lotes as $lote)
                                                    <tr>
                                                        <td>{{ $lote->id }}</td>
                                                        <td>{{ $lote->name }}</td>
                                                        <td>{{ $lote->quantity }}</td>
                                                        <td>{{ $lote->confirmado }}</td>
                                                        <td>{{ $lote->pendente }}</td>
                                                        <td>{{ $lote->restante }}</td>
                                                        <td>@money($lote->total_confirmado)</td>
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
                                    <h4 class="mb-3">Listagem de inscritos por lote ({{ count($situacao_participantes_lotes) }})</h4>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-head-fixed text-nowrap display" id="participantes_listagem_lote">
                                            <thead>
                                                <tr>
                                                    <th>Nº inscrição</th>
                                                    <!-- <th>Nome</th>
                                                    <th>Email</th> -->
                                                    <th>Lote</th>
                                                    <th>Situação</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($situacao_participantes_lotes as $participante)
                                                    <tr>
                                                        <td>{{ $participante->number }}</td>
                                                        {{-- <td>@if (isset($participante->answers[0])){{$participante->answers[0]->answer}} @else - @endif</td>
                                                        <td>@if (isset($participante->answers[1])) {{$participante->answers[1]->answer}} @else - @endif</td> --}}
                                                        <td>{{ $participante->lote_name }}</td>
                                                        <td>
                                                            @if ($participante->status_item == 1)
                                                                <span class="badge bg-success">Confirmado</span>
                                                            @elseif($participante->status_item == 2)
                                                                <span class="badge bg-warning">Pendente</span>
                                                            @elseif($participante->status_item == 3)
                                                                <span class="badge bg-info">Cancelado</span>
                                                            @elseif($participante->status_item == 4)
                                                                <span class="badge bg-warning">Estornado</span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($participante->status_item == 2)
                                                                <div class="d-flex">
                                                                    <form action="{{ route('participante.nova_cobranca', $participante->id) }}" method="POST" class="me-2">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Tem certeza que deseja gerar uma nova cobrança para este participante?');">
                                                                            <i class="fas fa-plus"></i>
                                                                            Nova cobrança
                                                                        </button>
                                                                    </form>
                                                                    <!-- <a class="btn btn-info btn-sm me-2"
                                                                       href="{{ route('event.participantes.edit', $participante->id) }}">
                                                                        <i class="fas fa-edit"></i>
                                                                        Editar
                                                                    </a> -->
                                                                </div>
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
                            {{-- <div class="row">
                                <div class="col-12">
                                    <h4 class="mb-3">Situação do pagamento dos inscritos</h4>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-head-fixed text-nowrap display" id="participantes_situacao">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Nome</th>
                                                    <th>Email</th>
                                                    <th>Valor</th>
                                                    <th>Situação</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($situacao_participantes as $participante)
                                                    <tr>
                                                        <td>{{$participante->inscricao}}</td>
                                                        <td>{{$participante->participante_name}}</td>
                                                        <td>{{$participante->participante_email}}</td>
                                                        <td>@if ($participante->valor_porcentagem != '') @money($participante->valor_porcentagem) @elseif($participante->valor_desconto != "") @money($participante->valor_desconto) @else @money($participante->lote_value) @endif</td>
                                                        <td>@if ($participante->gatway_status == 1) <span class="badge badge-success">Concluído</span> @elseif($participante->gatway_status == 3) <span class="badge badge-danger">Boleto vencido</span> @endif</td>
                                                        <td>
                                                            @if ($participante->gatway_status == 3)
                                                                <div class="d-flex">
                                                                    <form action="{{ route('participante.nova_cobranca', $participante->id) }}" method="POST" class="me-2">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Tem certeza que deseja gerar uma nova cobrança para este participante?');">
                                                                            <i class="fas fa-plus"></i>
                                                                            Nova cobrança
                                                                        </button>
                                                                    </form>
                                                                    <form action="{{ route('event.destroy', $event->id) }}" method="POST">
                                                                        <input type="hidden" name="_method" value="DELETE">
                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                        <a class="btn btn-danger btn-sm mr-1"  href="javascript:;" onclick="removeData({{$event->id}})">
                                                                            <i class="fas fa-trash">
                                                                            </i>
                                                                            Excluir
                                                                        </a>
                                                                        <button class="d-none" id="btn-remove-hidden-{{$event->id}}">Remover</button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-5"/> --}}
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="mb-3">Resumo de inscritos por cupom de desconto ({{ count($situacao_coupons) }})</h4>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-head-fixed text-nowrap display" id="participantes_inscritos_cupom">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Cupum</th>
                                                    <th>Desconto</th>
                                                    <th>Limite</th>
                                                    <th>Restante</th>
                                                    <th>Confirmados</th>
                                                    <th>Pendentes</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($situacao_coupons as $coupon)
                                                    <tr>
                                                        <td>{{ $coupon->id }}</td>
                                                        <td>{{ $coupon->code }}</td>
                                                        <td>
                                                            @if ($coupon->discount_type == 0)
                                                                {{ $coupon->discount_value * 100 }}%
                                                            @elseif($coupon->discount_type == 1)
                                                                @money($coupon->discount_value)
                                                            @endif
                                                        </td>
                                                        <td>{{ $coupon->limit_buy }}</td>
                                                        <td>{{ $coupon->limit_buy - $coupon->confirmado }}</td>
                                                        <td>{{ $coupon->confirmado }}</td>
                                                        <td>{{ $coupon->pendente }}</td>
                                                        <td>{{ $coupon->confirmado + $coupon->pendente }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2 border-start">
                            <div class="row pl-3 pr-2">
                                <h4>Valor total</h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                        <th style="width:50%">Confirmado:</th>
                                        <td class="text-right">@money($resumo->total_confirmado)</td>
                                        </tr>
                                        <tr>
                                        <th>Total da taxa ({{number_format($config->tax*100, 2, ',')}}%)</th>
                                        <td class="text-right">- @money($resumo->total_taxa)</td>
                                        </tr>
                                        <tr style="border-top: solid 2px #666">
                                        <th>Líquido:</th>
                                        <td class="text-right"> @money($resumo->total_liquido)</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr class="mb-5"/>
                            <div class="row pl-3 pr-2">
                                <h4>Vendas por meio de pagamento</h4>
                                <div class="table-responsive">
                                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('event_home.my_events') }}" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('assets/css/print.css') }}" media="print" type="text/css"/>
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
    @endpush

    @push('footer')
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.2/chart.min.js" integrity="sha512-zjlf0U0eJmSo1Le4/zcZI51ks5SjuQXkU0yOdsOBubjSmio9iCUp8XPLkEAADZNBdR9crRy3cniZ65LF2w8sRA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

                $('#participantes_table').DataTable({
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
                            title: 'Listagem de vendas realizadas - {{ $event->name }}',
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
                            title: 'Listagem de vendas realizadas - {{ $event->name }}',
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
                            title: 'Listagem de vendas realizadas - {{ $event->name }}',
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
                            title: 'Listagem de vendas realizadas - {{ $event->name }}',
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

                $('#participantes_inscritos_lote').DataTable({
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
                            title: 'Resumo de inscritos por lote - {{ $event->name }}',
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
                            title: 'Resumo de inscritos por lote - {{ $event->name }}',
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
                            title: 'Resumo de inscritos por lote - {{ $event->name }}',
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
                            title: 'Resumo de inscritos por lote - {{ $event->name }}',
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

                $('#participantes_listagem_lote').DataTable({
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
                            title: 'Listagem de inscritos por lote - {{ $event->name }}',
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
                            title: 'Listagem de inscritos por lote - {{ $event->name }}',
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
                            title: 'Listagem de inscritos por lote - {{ $event->name }}',
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
                            title: 'Listagem de inscritos por lote - {{ $event->name }}',
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

                $('#participantes_inscritos_cupom').DataTable({
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
                            title: 'Resumo de inscritos por cupom de desconto - {{ $event->name }}',
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
                            title: 'Resumo de inscritos por cupom de desconto - {{ $event->name }}',
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
                            title: 'Resumo de inscritos por cupom de desconto - {{ $event->name }}',
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
                            title: 'Resumo de inscritos por cupom de desconto - {{ $event->name }}',
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

                $(document).ready(function() {
                    // Verificar se o canvas existe
                    if ($('#pieChart').length === 0) {
                        console.error('Canvas #pieChart não encontrado');
                        return;
                    }

                    var methods = [];
                    var total_methods = [];
                    var backgroundColors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'];

                    // Dados passados do PHP
                    var paymentData = @json($payment_methods);

                    console.log('Dados de pagamento recebidos:', paymentData);

                    // Verificar se há dados - sempre tentar criar gráfico mesmo com dados vazios
                    console.log('Verificando dados de pagamento:', paymentData);

                    // Se não há dados, criar entrada padrão para vendas gratuitas
                    if (!paymentData || paymentData.length === 0) {
                        console.warn('Nenhum dado de pagamento encontrado, criando entrada padrão');
                        paymentData = [{
                            gatway_payment_method: 'free',
                            payment_methods_total: 0
                        }];
                    }

                    // Processar dados
                    for (var i = 0; i < paymentData.length; i++) {
                        var method = paymentData[i].gatway_payment_method;
                        var total = parseInt(paymentData[i].payment_methods_total) || 0;

                        total_methods.push(total);

                        // Mapear métodos de pagamento para labels legíveis
                        switch(method) {
                            case 'credit':
                            case 'credit_card':
                                methods.push('Cartão de Crédito');
                                break;
                            case 'boleto':
                            case 'ticket':
                                methods.push('Boleto');
                                break;
                            case 'pix':
                            case 'bank_transfer':
                                methods.push('PIX');
                                break;
                            case 'free':
                                methods.push('Grátis');
                                break;
                            default:
                                methods.push(method || 'Outro');
                        }
                    }

                    console.log('Labels processados:', methods);
                    console.log('Dados processados:', total_methods);

                    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');

                    try {
                        new Chart(pieChartCanvas, {
                            type: 'pie',
                            data: {
                                labels: methods,
                                datasets: [{
                                    label: 'Vendas por meio de pagamento',
                                    data: total_methods,
                                    backgroundColor: backgroundColors.slice(0, methods.length),
                                    borderColor: backgroundColors.slice(0, methods.length).map(color => color + '80'),
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                maintainAspectRatio: false,
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                var label = context.label || '';
                                                var value = context.parsed || 0;
                                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                                return label + ': ' + value + ' (' + percentage + '%)';
                                            }
                                        }
                                    }
                                }
                            }
                        });

                        console.log('Gráfico criado com sucesso');
                    } catch (error) {
                        console.error('Erro ao criar gráfico:', error);
                        // Fallback: mostrar mensagem de erro no canvas
                        var ctx = pieChartCanvas;
                        ctx.font = '14px Arial';
                        ctx.fillStyle = '#dc3545';
                        ctx.textAlign = 'center';
                        ctx.fillText('Erro ao carregar gráfico', $('#pieChart').width() / 2, $('#pieChart').height() / 2);
                    }

                    // Gráfico de Vendas ao Longo do Tempo
                    @if($vendas_por_periodo && $vendas_por_periodo->count() > 0)
                    if ($('#salesChart').length > 0) {
                        var salesData = @json($vendas_por_periodo);
                        var salesLabels = [];
                        var salesValues = [];

                        salesData.forEach(function(item) {
                            var date = new Date(item.data);
                            salesLabels.push(date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' }));
                            salesValues.push(parseInt(item.total_vendas) || 0);
                        });

                        var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
                        new Chart(salesChartCanvas, {
                            type: 'line',
                            data: {
                                labels: salesLabels,
                                datasets: [{
                                    label: 'Vendas por Dia',
                                    data: salesValues,
                                    borderColor: '#17a2b8',
                                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return 'Vendas: ' + context.parsed.y;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });
                    }
                    @endif

                    // Gráfico de Vendas por Lote
                    @if($vendas_por_lote && $vendas_por_lote->count() > 0)
                    if ($('#lotesChart').length > 0) {
                        var lotesData = @json($vendas_por_lote);
                        var lotesLabels = [];
                        var lotesValues = [];

                        lotesData.forEach(function(item) {
                            lotesLabels.push(item.name || 'Lote #' + item.id);
                            lotesValues.push(parseInt(item.vendidos) || 0);
                        });

                        var lotesChartCanvas = $('#lotesChart').get(0).getContext('2d');
                        new Chart(lotesChartCanvas, {
                            type: 'bar',
                            data: {
                                labels: lotesLabels,
                                datasets: [{
                                    label: 'Ingressos Vendidos',
                                    data: lotesValues,
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.6)',
                                        'rgba(54, 162, 235, 0.6)',
                                        'rgba(255, 206, 86, 0.6)',
                                        'rgba(75, 192, 192, 0.6)',
                                        'rgba(153, 102, 255, 0.6)',
                                        'rgba(255, 159, 64, 0.6)'
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return 'Vendidos: ' + context.parsed.y;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });
                    }
                    @endif
                });
            });
        </script>
    @endpush

</x-site-layout>
