<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Vendas</h1>
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
                                <h3 class="card-title">Detalhes do comprador</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <div class="container">
                                    <div class="row">
                                        <div class="card-body col-6">
                                            <div class="form-group">
                                                <label for="id">CPF</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->participante))
                                                        {{ $order->participante->cpf }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Nome</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->participante))
                                                        {{ $order->participante->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <label for="subtitle">Email</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->participante))
                                                        {{ $order->participante->email }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="card-body col-6">
                                            <div class="form-group">
                                                <label for="subtitle">Telefone</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->participante))
                                                        {{ $order->participante->phone }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <label for="subtitle">Data criação</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->participante))
                                                        {{ \Carbon\Carbon::parse($order->participante->created_at)->format('d/m/Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <!-- /.card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Detalhes da venda</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <div class="container">
                                    <div class="row">
                                        <div class="card-body col-6">
                                            <div class="form-group">
                                                <label for="id">Hash da venda</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->order_hash))
                                                        {{ $order->order_hash }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Hash Mercado Pago</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->gatway_hash))
                                                        {{ $order->gatway_hash }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <label for="subtitle">Referência Mercado Pago</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->gatway_reference))
                                                        {{ $order->gatway_reference }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>

                                        </div>
                                        <div class="card-body col-6">
                                            <div class="form-group">
                                                <label for="subtitle">Status da compra</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->situacao))
                                                        @if ($order->situacao == 1)
                                                            Confirmado
                                                        @elseif($order->situacao == 2)
                                                            Pendente
                                                        @elseif($order->situacao == 3)
                                                            Cancelado
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <label for="slug">Forma pagamento</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->gatway_payment_method))
                                                        @if ($order->gatway_payment_method == 'credit')
                                                            Crédito
                                                        @elseif($order->gatway_payment_method == 'boleto')
                                                            Boleto
                                                        @elseif($order->gatway_payment_method == 'pix')
                                                            Pix
                                                        @else
                                                            Não informado
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <label for="slug">Data da compra</label>
                                                <p class="text-muted" style="font-size: 18px">
                                                    @if (isset($order->created_at))
                                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        @if (isset($order->order_items))
                            @foreach ($order->order_items as $k => $order_item)
                                <!-- /.card -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Participante #{{ $k + 1 }}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <div class="container">
                                            <div class="row">
                                                <div class="card-body col-6">
                                                    <div class="form-group">
                                                        <label for="id">Hash participante</label>
                                                        <p class="text-muted" style="font-size: 18px">
                                                            {{ $order_item->hash }}
                                                        </p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="id">Nº inscrição</label>
                                                        <p class="text-muted" style="font-size: 18px">
                                                            {{ $order_item->number }}
                                                        </p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="subtitle">Lote</label>
                                                        <p class="text-muted" style="font-size: 18px">
                                                            @if (isset($order_item->lote))
                                                                {{ $order_item->lote->name }}
                                                            @else
                                                                -
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="name">Valor do ingresso</label>
                                                        <p class="text-muted" style="font-size: 18px">
                                                            @money($order_item->value)
                                                        </p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="name">Taxa de serviço</label>
                                                        <p class="text-muted" style="font-size: 18px">
                                                            @money($order_item->value)
                                                        </p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="name">Valor recebido</label>
                                                        <p class="text-muted" style="font-size: 18px">
                                                            @money($order_item->value)
                                                        </p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="subtitle">Data uso</label>
                                                        <p class="text-muted" style="font-size: 18px">
                                                            @if ($order_item->date_use)
                                                                {{ \Carbon\Carbon::parse($order_item->date_use)->format('d/m/Y H:i') }}
                                                            @else
                                                                Não utilizado
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="subtitle">Status da compra do participante</label>
                                                        <p class="text-muted" style="font-size: 18px">
                                                            @if ($order_item->status == 1)
                                                                Confirmado
                                                            @elseif($order_item->status == 2)
                                                                Pendente
                                                            @elseif($order_item->status == 3)
                                                                Cancelado
                                                            @elseif($order_item->status == 4)
                                                                Estorno
                                                            @else
                                                                -
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                @if (isset($order_item->answers))
                                                    <div class="card-body col-6">
                                                        @foreach ($order_item->answers as $answer)
                                                            <div class="form-group">
                                                                <label
                                                                    for="subtitle">{{ $answer->question->question }}</label>
                                                                <p class="text-muted" style="font-size: 18px">
                                                                    {{ $answer->answer }}
                                                                </p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                                <!-- /.card -->
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</x-app-layout>
