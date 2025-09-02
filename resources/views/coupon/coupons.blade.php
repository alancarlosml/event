<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Cupons</h1>
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
                                <h3 class="card-title">Listar todos - {{ $event->name }}</h3>

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
                                        <a href="{{ route('event.create_coupon', $event->id) }}"
                                            class="btn btn-success">Cadastrar novo cupom</a>
                                    </div>
                                    <table class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Código</th>
                                                <th>Tipo desconto</th>
                                                <th>Valor desconto</th>
                                                <th>Limite de compras</th>
                                                <th>Limite de inscrições</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($coupons as $coupon)
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
                                                            {{ $coupon->discount_value }}%
                                                        @elseif($coupon->discount_type == 1)
                                                            @money($coupon->discount_value)
                                                        @endif
                                                    </td>
                                                    <td>{{ $coupon->limit_buy }}</td>
                                                    <td>{{ $coupon->limit_tickets }}</td>
                                                    <td>
                                                        @if ($coupon->status == 1)
                                                            <span class="badge badge-success">Ativo</span>
                                                        @else
                                                            <span class="badge badge-danger">Não ativo</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a class="btn btn-info btn-sm mr-1"
                                                                href="{{ route('event.coupon_edit', $coupon->id) }}">
                                                                <i class="fas fa-pencil-alt">
                                                                </i>
                                                                Editar
                                                            </a>
                                                            <form
                                                                action="{{ route('event.destroy_coupon', $coupon->id) }}"
                                                                method="POST">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="_token"
                                                                    value="{{ csrf_token() }}">
                                                                <a class="btn btn-danger btn-sm mr-1"
                                                                    href="javascript:;"
                                                                    onclick="removeData({{ $coupon->id }})">
                                                                    <i class="fas fa-trash">
                                                                    </i>
                                                                    Remover
                                                                </a>
                                                                <button class="d-none"
                                                                    id="btn-remove-hidden-{{ $coupon->id }}">Remover</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <div class="modal fade modalMsgRemove"
                                                        id="modalMsgRemove-{{ $coupon->id }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="modalMsgRemoveLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalMsgRemoveLabel">
                                                                        Remoção de cupom</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Deseja realmente remover esse cupom?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        id="btn-remove-ok-{{ $coupon->id }}"
                                                                        onclick="removeSucc({{ $coupon->id }})">Sim</button>
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
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <a href="{{ route('event.index')}}" class="btn btn-secondary float-left">Voltar</a>
                            </div>
                            <!-- /.card-footer-->
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

            });
        </script>
    @endpush
</x-app-layout>
