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
                                <h3 class="card-title">Detalhes do evento - {{ $event->name }}</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="id">ID</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $event->id }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Nome</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $event->name }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="subtitle">Subtitulo</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $event->subtitle }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="slug">URL do evento</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            http://www.ticketdz6.com.br/<b>{{ $event->slug }}</b>
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Descrição</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {!! $event->description !!}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="banner">Banner</label> <br />
                                        @if ($event->banner != '')
                                            <img src="{{ asset('storage/' . $event->banner) }}" alt="Banner evento"
                                                class="img-fluid img-thumbnail" style="width: 400px">
                                        @else
                                            <p class="text-muted" style="font-size: 18px">
                                                Sem banner cadastrado
                                            </p>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="max_tickets">N° máximo de ingressos</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $event->max_tickets }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="max_tickets">Local do evento</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $event->place->name }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="max_tickets">Responsável</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ $event->get_participante_admin()->name }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="created_at">Data de criação</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            {{ \Carbon\Carbon::parse($event->created_at)->format('j/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Ativo</label>
                                        <p class="text-muted" style="font-size: 18px">
                                            @if ($event->status == 1)
                                                Sim
                                            @else
                                                Não
                                            @endif
                                        </p>
                                    </div>
                                    <hr>
                                    <label for="lotes">Lotes</label>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nome</th>
                                                    <th>Valor</th>
                                                    <th>Taxa</th>
                                                    <th>Preço final</th>
                                                    <th>Quantidade</th>
                                                    <th>Visibilidade</th>
                                                    {{-- <th>Cupons</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($event->lotes as $lote)
                                                    <tr>
                                                        <td>{{ $lote->id }}</td>
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
                                                        {{-- <td>
                                                <ul class="list-group list-group-flush">
                                                    @foreach ($lote->coupons as $coupon)
                                                    <li>{{$coupon->code}}</li>
                                                    @endforeach
                                                </ul>
                                            </td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <label for="cupons">Cupons</label>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Código</th>
                                                    <th>Tipo desconto</th>
                                                    <th>Valor desconto</th>
                                                    <th>Limite de compras</th>
                                                    <th>Limite de inscrições</th>
                                                    <th>Lotes</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($event->coupons as $coupon)
                                                    <tr>
                                                        <td>{{ $coupon->id }}</td>
                                                        <td>{{ $coupon->code }}</td>
                                                        <td>
                                                            @if ($coupon->discount_type == 0)
                                                                Porcentagem
                                                            @elseif($coupon->discount_type == 1)
                                                                Fixo
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($coupon->discount_type == 0)
                                                                {{ $coupon->discount_value * 100 }}%
                                                            @elseif($coupon->discount_type == 1)
                                                                @money($coupon->discount_value)
                                                            @endif
                                                        </td>
                                                        <td>{{ $coupon->limit_buy }}</td>
                                                        <td>{{ $coupon->limit_tickets }}</td>
                                                        <td>
                                                            <ul class="list-group list-group-flush">
                                                                @foreach ($coupon->lotes as $lote)
                                                                    <li>{{ $lote->name }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            @if ($coupon->status == 1)
                                                                <span class="badge badge-success">Ativo</span>
                                                            @else
                                                                <span class="badge badge-danger">Não ativo</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card-body -->
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
</x-app-layout>
