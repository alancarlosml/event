<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                </ol>
                <h2>Relatórios financeiros: {{ $event->name }}</h2>

            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="mb-3 ps-3 pe-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erros encontrados:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12 pe-3">
                            <h4 class="d-flex justify-content-between">
                                Resumo financeiro
                                <a href="#" class="button-print" onclick="window.print();"><img src="{{ asset('assets/img/print-pdf.jpg') }}" alt="Imprimir" width="36px"></a>
                            </h4>
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light justify-content-center">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Total confirmado</span>
                                            <span class="info-box-number text-center text-success mb-0 h5">@money($resumo->total_confirmado)</span>
                                            <small class="text-center mb-0">{{ $resumo->confirmado }} participantes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light justify-content-center">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Total pendente</span>
                                            <span class="info-box-number text-center text-danger mb-0 h5">@money($resumo->total_pendente)</span>
                                            <small class="text-center mb-0">{{ $resumo->pendente }} participantes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light justify-content-center">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Total geral</span>
                                            <span class="info-box-number text-center text-primary mb-0 h5">@money($resumo->total_confirmado + $resumo->total_pendente)</span>
                                            <small class="text-center mb-0">{{ $resumo->geral }} participantes</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6 border-end">
                                    <h4>Valor total</h4>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Confirmado:</th>
                                                <td class="text-right">@money($resumo->total_confirmado)</td>
                                            </tr>
                                            <tr>
                                                <th>Total da taxa ({{ number_format($config->tax * 100, 2, ',') }}%)</th>
                                                <td class="text-right">- @money($resumo->total_taxa)</td>
                                            </tr>
                                            <tr style="border-top: solid 2px #666">
                                                <th>Líquido:</th>
                                                <td class="text-right"> @money($resumo->total_liquido)</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-center">Vendas por meio de pagamento</h4>
                                    <div class="table-responsive">
                                        {{-- <table class="table">
                                            @foreach ($payment_methods as $payment_method)
                                                <tr>
                                                    <th style="width:50%">{{$payment_method->gatway_payment_method}}</th>
                                                    <td class="text-right">{{$payment_method->payment_methods_total}}</td>
                                                </tr>
                                            @endforeach
                                        </table> --}}
                                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
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
                                                        <td> {{ $order->order_id }} </td>
                                                        <td> {{ $order->order_hash }} </td>
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
                                                        <td>
                                                            @if ($order->situacao == 1)
                                                                <span class="badge bg-success">Confirmado</span>
                                                            @elseif($order->situacao == 2)
                                                                <span class="badge bg-warning">Pendente</span>
                                                            @elseif($order->situacao == 2)
                                                                <span class="badge bg-danger">Cancelado</span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                                                        </td>
                                                        <td> 
                                                            <a class="btn btn-info btn-sm mr-1" href="{{ route('event_home.order.details', $order->order_hash) }}">
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
                    <a href="{{ route('event_home.my_events') }}" class="btn btn-primary">Voltar</a>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('assets/css/print.css') }}" media="print" type="text/css"/>
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

                var methods = new Array();
                var total_methods = new Array();
                var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
                var pieData = {!! json_encode($payment_methods_json) !!}
                var pieOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                }

                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                size_obj_methods = Object.keys(pieData['original']).length;
                for (var i = 0; i < size_obj_methods; i++) {
                    total_methods.push(pieData['original'][i].payment_methods_total);
                    if (pieData['original'][i].gatway_payment_method == 'credit') {
                        methods.push('Cartão de crédito');
                    } else if (pieData['original'][i].gatway_payment_method == 'boleto') {
                        methods.push('Boleto');
                    }
                }

                new Chart(pieChartCanvas, {
                    type: 'pie',
                    data: {
                        labels: methods,
                        datasets: [{
                            label: 'Vendas por meio de pagamento',
                            data: total_methods,
                            backgroundColor: ['#f56954', '#3c8dbc'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        pieOptions,
                        plugins: {
                            labels: {
                                render: 'percentage',
                                fontColor: ['white', 'white'],
                                precision: 2
                            }
                        },
                    }
                });
            });
        </script>
    @endpush

</x-site-layout>
