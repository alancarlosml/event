<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Relatórios financeiros: {{$event->name}}</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="form-group pl-3 pr-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12 pr-3">
                            <h4>Resumo financeiro</h4>
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light justify-content-center">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Total confirmado</span>
                                            <span class="info-box-number text-center text-success mb-0 h5">@money($resumo->total_confirmado)</span>
                                            <small class="text-center mb-0">{{$resumo->confirmado}} participantes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light justify-content-center">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Total pendente</span>
                                            <span class="info-box-number text-center text-danger mb-0 h5">@money($resumo->total_pendente)</span>
                                            <small class="text-center mb-0">{{$resumo->pendente}} participantes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light justify-content-center">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Total geral</span>
                                            <span class="info-box-number text-center text-primary mb-0 h5">@money($resumo->total_confirmado + $resumo->total_pendente)</span>
                                            <small class="text-center mb-0">{{$resumo->geral}} participantes</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6 border-right">
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
                            <hr/>
                            <div class="row pt-3">
                                <div class="col-12">
                                    <h4>Resumo de inscritos por lote</h4>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-head-fixed text-nowrap display">
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
                                                @foreach($lotes as $lote)
                                                    <tr>
                                                        <td>{{$lote->id}}</td>
                                                        <td>{{$lote->name}}</td>
                                                        <td>{{$lote->quantity}}</td>
                                                        <td>{{$lote->confirmado}}</td>
                                                        <td>{{$lote->pendente}}</td>
                                                        <td>{{$lote->restante}}</td>
                                                        <td>@money($lote->total_confirmado)</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-5"/>
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="mb-3">Listagem de inscritos por lote</h4>
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
                                                    <th>N°</th>
                                                    <th>Nome</th>
                                                    <th>Lote</th>
                                                    <th>Situação</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($participantes as $participante)
                                                    <tr>
                                                        <td>{{$participante->inscricao}}</td>
                                                        <td>
                                                            {{$participante->participante_name}} <br/><small>{{$participante->participante_email}}</small><br/>@if($participante->code)<small><b>Cupom:</b> {{$participante->code}}</small>@endif
                                                        </td>
                                                        <td>{{$participante->lote_name}}</td>
                                                        <td>@if($participante->situacao == 1) <span class="badge badge-success">Confirmado</span> @elseif($participante->situacao == 2) <span class="badge badge-warning">Pendente</span> @else <span class="badge badge-danger">Cancelado</span> @endif</td>
                                                        <td>
                                                            <a class="btn btn-info btn-sm mr-1" href="{{route('event.participantes.edit', $participante->id)}}">
                                                                <i class="fas fa-pencil-alt">
                                                                </i>
                                                                Editar
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-5"/>
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="mb-3">Situação do pagamento dos inscritos</h4>
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
                                                @foreach($situacao_participantes as $participante)
                                                    <tr>
                                                        <td>{{$participante->inscricao}}</td>
                                                        <td>{{$participante->participante_name}}</td>
                                                        <td>{{$participante->participante_email}}</td>
                                                        <td>@if($participante->valor_porcentagem != "") @money($participante->valor_porcentagem) @elseif($participante->valor_desconto != "") @money($participante->valor_desconto) @else @money($participante->lote_value) @endif</td>
                                                        <td>@if($participante->gatway_status == 1) <span class="badge badge-success">Concluído</span> @elseif($participante->gatway_status == 3) <span class="badge badge-danger">Boleto vencido</span> @endif</td>
                                                        <td>
                                                            @if($participante->gatway_status == 3)
                                                                <div class="d-flex">
                                                                    <a class="btn btn-warning btn-sm mr-1" href="{{route('event.participantes.edit', $participante->id)}}">
                                                                        <i class="fas fa-plus">
                                                                        </i>
                                                                        Nova cobrança
                                                                    </a>
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
                            <hr class="mt-5"/>
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="mb-3">Resumo de inscritos por cupom de desconto</h4>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-head-fixed text-nowrap display" id="participantes_situacao">
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
                                                @foreach($situacao_coupons as $coupon)
                                                    <tr>
                                                        <td>{{$coupon->id}}</td>
                                                        <td>{{$coupon->code}}</td>
                                                        <td>@if($coupon->discount_type == 0) {{$coupon->discount_value*100}}% @elseif($coupon->discount_type == 1) @money($coupon->discount_value) @endif</td>
                                                        <td>{{$coupon->limit_buy}}</td>
                                                        <td>{{$coupon->limit_buy - $coupon->confirmado}}</td>
                                                        <td>{{$coupon->confirmado}}</td>
                                                        <td>{{$coupon->pendente}}</td>
                                                        <td>{{$coupon->confirmado + $coupon->pendente}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2 border-left">
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
                        </div> --}}
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->
      @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
      @endpush

      @push('head')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="../../../assets_admin/jquery.datetimepicker.min.css " rel="stylesheet"> --}}
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"> --}}
          
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
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="../../../assets_admin/jquery.datetimepicker.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

        <script>

        function removeData(id){
            $('#modalMsgRemove-' + id).modal('show');
        }

        function removeSucc(id){
            $('#btn-remove-hidden-' + id).click();
        }

        $(document).ready(function() {
                $('.order_lote').change(function(){
                    id = $(this).attr('id');
                    value = $(this).val();
                    console.log(value);
                    $('#lote_' + id).val(id + '_' + value);
                });

                $('#participantes_table').DataTable({
                    language: {
                        "decimal":        "",
                        "emptyTable":     "Sem dados disponíveis na tabela",
                        "info":           "Exibindo _START_ de _END_ de um total de _TOTAL_ registros",
                        "infoEmpty":      "Exibindo 0 de 0 de um total de 0 registros",
                        "infoFiltered":   "(filtrados do total de _MAX_ registros)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "Exibir _MENU_ registros",
                        "loadingRecords": "Carregando...",
                        "processing":     "",
                        "search":         "Busca: ",
                        "zeroRecords":    "Nenhum registro correspondente encontrado",
                        "paginate": {
                            "first":      "Primeiro",
                            "last":       "Último",
                            "next":       "Próximo",
                            "previous":   "Anterior"
                        },
                        "aria": {
                            "sortAscending":  ": ative para classificar a coluna em ordem crescente",
                            "sortDescending": ": ativar para ordenar a coluna decrescente"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'csv',
                            text: 'CSV',
                            title: 'Listagem de inscritos por lote - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [ 0, 1, 2, 3 ],
                                stripNewlines: false,
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi, " - ").replace(/<\/small>/gi, "").replace(/<b>/gi, "").replace(/<\/b>/gi, "");
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
                                columns: [ 0, 1, 2, 3 ],
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi, " - ").replace(/<\/small>/gi, "").replace(/<b>/gi, "").replace(/<\/b>/gi, "");
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
                                columns: [ 0, 1, 2, 3 ],
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
                                columns: [ 0, 1, 2, 3 ],
                                stripHtml: false
                            }
                        }
                    ]
                });

                $('#participantes_situacao').DataTable({
                    language: {
                        "decimal":        "",
                        "emptyTable":     "Sem dados disponíveis na tabela",
                        "info":           "Exibindo _START_ de _END_ de um total de _TOTAL_ registros",
                        "infoEmpty":      "Exibindo 0 de 0 de um total de 0 registros",
                        "infoFiltered":   "(filtrados do total de _MAX_ registros)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "Exibir _MENU_ registros",
                        "loadingRecords": "Carregando...",
                        "processing":     "",
                        "search":         "Busca: ",
                        "zeroRecords":    "Nenhum registro correspondente encontrado",
                        "paginate": {
                            "first":      "Primeiro",
                            "last":       "Último",
                            "next":       "Próximo",
                            "previous":   "Anterior"
                        },
                        "aria": {
                            "sortAscending":  ": ative para classificar a coluna em ordem crescente",
                            "sortDescending": ": ativar para ordenar a coluna decrescente"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'csv',
                            text: 'CSV',
                            title: 'Situação do pagamento dos inscritos - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [ 0, 1, 2, 3 ],
                                stripNewlines: false,
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi, " - ").replace(/<\/small>/gi, "").replace(/<b>/gi, "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            title: 'Situação do pagamento dos inscritos - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [ 0, 1, 2, 3 ],
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi, " - ").replace(/<\/small>/gi, "").replace(/<b>/gi, "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'Situação do pagamento dos inscritos - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [ 0, 1, 2, 3 ],
                                stripNewlines: false
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Situação do pagamento dos inscritos - {{ $event->name }}',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [ 0, 1, 2, 3 ],
                                stripHtml: false
                            }
                        }
                    ]
                });

                var methods = new Array();
                var total_methods = new Array();
                var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
                var pieData        = {!! json_encode($payment_methods_json) !!}
                var pieOptions     = {
                                        maintainAspectRatio : false,
                                        responsive : true,
                                    }

                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                size_obj_methods = Object.keys(pieData['original']).length;
                for (var i = 0; i < size_obj_methods; i++) {
                    total_methods.push(pieData['original'][i].payment_methods_total);
                    if(pieData['original'][i].gatway_payment_method == 'credit'){
                        methods.push('Cartão de crédito');
                    }else if(pieData['original'][i].gatway_payment_method == 'boleto'){
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

        // $(document).ready(function() {

        //     $('.order_lote').change(function(){
        //         id = $(this).attr('id');
        //         value = $(this).val();
        //         console.log(value);
        //         $('#lote_' + id).val(id + '_' + value);
        //     });

        //     $(".up,.down").click(function () {
               
        //        var $element = this;
        //        var row = $($element).parents("tr:first");

               
        //        if($(this).is('.up')){
        //             hash_this = $(this).parents('tr').find('.lote_hash').text();
        //             hash_prev = row.prev().find('.lote_hash').text();
        //             if(hash_prev != ''){
        //                 console.log(hash_prev);
        //                     val_this = $('#lote_' + hash_this).val();
        //                     val_prev = $('#lote_' + hash_prev).val();
        //                     id_this = parseInt(val_this.split('_')[1]) - 1;
        //                     id_prev = parseInt(val_prev.split('_')[1]) + 1;
        //                     $('#lote_' + hash_this).val(hash_this + '_' + id_this);
        //                     $('#lote_' + hash_prev).val(hash_prev + '_' + id_prev);
        //                     console.log(id_this);
        //                     console.log(id_prev);
        //                     row.insertBefore(row.prev());
        //             }
        //        }
        //        else{
        //             hash_this = $(this).parents('tr').find('.lote_hash').text();
        //             hash_next = row.next().find('.lote_hash').text();
        //             if(hash_next != ''){
        //                 console.log(hash_next);
        //                     val_this = $('#lote_' + hash_this).val();
        //                     val_next = $('#lote_' + hash_next).val();
        //                     id_this = parseInt(val_this.split('_')[1]) + 1;
        //                     id_next = parseInt(val_next.split('_')[1]) - 1;
        //                     $('#lote_' + hash_this).val(hash_this + '_' + id_this);
        //                     $('#lote_' + hash_next).val(hash_next + '_' + id_next);
        //                     console.log(id_this);
        //                     console.log(id_next);
        //                     row.insertAfter(row.next());
        //             }
        //        }
        //   });

            // $('#description').summernote({
            //     placeholder: 'Descreva em detalhes o evento',
            //     tabsize: 2,
            //     height: 200
            // });

            // $('#name').keyup(function(e) {
            //     $.get('{{ route('event_home.check_slug') }}', 
            //         { 'title': $(this).val() }, 
            //         function( data ) {
            //             $('#slug').val(data.slug);
            //             if(data.slug_exists == '1'){
            //                 $('#slug').removeClass('is-valid');
            //                 $('#slug').addClass('is-invalid');
            //                 $('#slugHelp').removeClass('d-none');
            //             }else{
            //                 $('#slug').removeClass('is-invalid');
            //                 $('#slug').addClass('is-valid');
            //                 $('#slugHelp').addClass('d-none');
            //             }
            //         }
            //     );
            // });

            // $('#slug').keyup(function(e) {
            //     $.get('{{ route('event_home.create_slug') }}', 
            //         { 'title': $(this).val() }, 
            //         function( data ) {
            //             if(data.slug_exists == '1'){
            //                 $('#slug').removeClass('is-valid');
            //                 $('#slug').addClass('is-invalid');
            //             }else{
            //                 $('#slug').removeClass('is-invalid');
            //                 $('#slug').addClass('is-valid');
            //             }
            //         }
            //     );
            // });

            // $('#category').on('change', function() {
            //     var category_id = this.value;
            //     $("#area_id").html('');
            //     $.ajax({
            //         url:"{{route('event_home.get_areas_by_category')}}",
            //         type: "POST",
            //         data: {
            //             category_id: category_id,
            //             _token: '{{csrf_token()}}' 
            //         },
            //         dataType : 'json',
            //         success: function(result){
            //             $('#area_id').html('<option value="">Selecione</option>'); 
            //             $.each(result.areas,function(key,value){
            //                 $("#area_id").append('<option value="'+value.id+'">'+value.name+'</option>');
            //             });
            //         }
            //     });
            // });

            // $('#cmd').click(function(){
            //     $('#card-date').append('<div class="form-row">' + 
            //             '<div class="form-group col-md-3">' +
            //                 '<label for="number">Data</label>'+
            //                 '<div class="input-group date" data-target-input="nearest">'+
            //                     '<input class="form-control datetimepicker-input datetimepicker_day" name="date[]" value=""/>'+
            //                     '<div class="input-group-append" data-toggle="datetimepicker">'+
            //                         '<div class="input-group-text"><i class="fa fa-calendar"></i></div>'+
            //                     '</div>'+
            //                 '</div>'+
            //             '</div>'+
            //             '<div class="form-group col-md-2">'+
            //                 '<label for="number">Hora início</label>'+
            //                 '<div class="input-group date" data-target-input="nearest">'+
            //                     '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" name="time_begin[]" value=""/>'+
            //                     '<div class="input-group-append" data-toggle="datetimepicker">'+
            //                         '<div class="input-group-text"><i class="fa-regular fa-clock"></i></div>'+
            //                     '</div>'+
            //                 '</div>'+
            //             '</div>'+
            //             '<div class="form-group col-md-2">'+
            //                 '<label for="number">Hora fim</label>'+
            //                 '<div class="input-group date" data-target-input="nearest">'+
            //                     '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" name="time_end[]" value=""/>'+
            //                     '<div class="input-group-append" data-toggle="datetimepicker">'+
            //                         '<div class="input-group-text"><i class="fa-regular fa-clock"></i></div>'+
            //                     '</div>'+
            //                 '</div>'+
            //             '</div>'+
            //             '<div class="form-group col-md-2">'+
            //                 '<a class="btn btn-danger btn-sm mr-1 btn-remove" style="margin-top: 35px" href="javascript:;">'+
            //                     '<i class="fa-solid fa-remove"></i>'+
            //                     ' Remover'+
            //                 '</a>'+
            //             '</div>'+ 
            //         '</div>'
            //     );
            // });

            // var i_field = 2;
            // $('#add_new_field').click(function(){
            //     var field = $(this).parent().parent().find('#question').val();
            //     var option = $(this).parent().parent().find('#option').val();
            //     var option_text = $(this).parent().parent().find('#option:selected').text();
            //     var required = $(this).parent().parent().find('#required').is(":checked");
            //     var unique = $(this).parent().parent().find('#unique').is(":checked");

            //     if(field === ''){

            //         alert('preencha');
            //         return false;
            //     }

            //     var required_star = required ? '*':'';
            //     var field_text = '';
            //     var field_name = '';
            //     i_field = i_field+1;

            //     $('#question').val('');
            //     $('#option').prop('selectedIndex',0);
            //     $('#required').prop('checked',false);
            //     $('#unique').prop('checked',false);

            //     switch(option){
            //         case '1':
            //             field_text = '(Tipo: Texto (Até 200 caracteres))';
            //             field_name = 'text';
            //             break;
            //         case '2':
            //             field_text = '(Tipo: Seleção)';
            //             field_name = 'select';
            //             break;
            //         case '3':
            //             field_text = '(Tipo: Marcação)';
            //             field_name = 'checkbox';
            //             break;
            //         case '4':
            //             field_text = '(Tipo: Múltipla escolha)';
            //             field_name = 'multiselect';
            //             break;
            //         case '5':
            //             field_text = '(Tipo: CPF)';
            //             field_name = 'cpf';
            //             break;
            //         case '6':
            //             field_text = '(Tipo: CNPJ)';
            //             field_name = 'cnpj';
            //             break;
            //         case '7':
            //             field_text = '(Tipo: Data)';
            //             field_name = 'date';
            //             break;
            //         case '8':
            //             field_text = '(Tipo: Telefone)';
            //             field_name = 'phone';
            //             break;
            //         case '9':
            //             field_text = '(Tipo: Número inteiro)';
            //             field_name = 'integer';
            //             break;
            //         case '10':
            //             field_text = '(Tipo: Número decimal)';
            //             field_name = 'decimal';
            //             break;
            //         case '11':
            //             field_text = '(Tipo: Arquivo)';
            //             field_name = 'file';
            //             break;
            //         case '12':
            //             field_text = '(Tipo: Textarea (+ de 200 caracteres))';
            //             field_name = 'textearea';
            //             break;
            //         case '13':
            //             field_text = '(Tipo: Email)';
            //             field_name = 'new_email';
            //             break;
            //         case '14':
            //             field_text = '(Tipo: Estados (BRA))';
            //             field_name = 'states';
            //             break;
            //     }

            //     $('#card-new-field').append('<div class="form-row">' +
            //         '<div class="form-group col-10">'+
            //             '<label for="field_'+i_field+'">Campo ' + i_field + required_star + '</label>' +
            //             '<input type="text" class="form-control" name="'+field_name+'_new_field" value="'+field+' '+field_text +'" readonly>' +
            //         '</div>'+
            //         '<div class="form-group col-2">'+
            //             '<a class="btn btn-danger btn-sm mr-1 btn-remove-field" style="margin-top: 35px" href="javascript:;">'+
            //                 '<i class="fa-solid fa-remove"></i>'+
            //                 ' Remover'+
            //             '</a>'+
            //         '</div>'+
            //     '</div>');
            // });

            // $('#add_place').click(function(){
            //         $('#event_address').toggle();                    
            //     });

            // var path = "{{route('event_home.autocomplete_place')}}";
            // $("#place_name").autocomplete({
            //     source: function( request, response ) {
            //         $.ajax({
            //             url: path,
            //             type: 'GET',
            //             dataType: "json",
            //             data: {
            //                 search: request.term
            //             },
            //             success: function( data ) {
            //                 response(data);
            //             }
            //         });
            //     },
            //     select: function (event, ui) {
            //         $('#place_name').val(ui.item.label);
            //         $('#address').val(ui.item.address);
            //         $('#number').val(ui.item.number);
            //         $('#district').val(ui.item.district);
            //         $('#complement').val(ui.item.complement);
            //         $('#zip').val(ui.item.zip);

            //         $('#state option[value="'+ui.item.uf+'"]').prop("selected", true);
                    
            //         var uf = $("#state").val();
            //         $("#city").html('');
            //         $.ajax({
            //             url:"{{url('admin/places/get-cities-by-state')}}",
            //             type: "POST",
            //             data: {
            //                 uf: uf,
            //                 _token: '{{csrf_token()}}' 
            //             },
            //             dataType : 'json',
            //             success: function(result){
            //                 $('#city').html('<option value="">Selecione</option>'); 
            //                 city_id = $('#city_id_hidden').val();

            //                 $.each(result.cities,function(key,value){
            //                     $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
            //                 });

            //                 $('#city option[value="'+ui.item.city_id+'"]').prop("selected", true);
            //             }
            //         });

            //         return false;
            //     }
            // });

            // $('body').on('click',".btn-remove-field", function(){
            //     $(this).parent().parent().remove();
            //     i_field = i_field-1;
            // });

            // $('body').on('click',".btn-remove", function(){
            //     $(this).parent().parent().remove();
            // });

            // $('body').on('mousedown',".datetimepicker_day", function(){
            //     $(this).datetimepicker({
            //         timepicker:false,
            //         format:'d/m/Y',
            //         mask:true
            //     });
            // });

            // $('body').on('mousedown',".datetimepicker_hour_begin", function(){
            //     $(this).datetimepicker({
            //         datepicker:false,
            //         format:'H:i',
            //         mask:true,
            //         onShow:function( ct ){
            //             this.setOptions({
            //                 maxTime:$(this).val()?$(this).val():false
            //             })
            //         }
            //     });
            // });

            // $('body').on('mousedown',".datetimepicker_hour_end", function(){
            //     $(this).datetimepicker({
            //         datepicker:false,
            //         format:'H:i',
            //         mask:true,
            //         onShow:function( ct ){
            //             this.setOptions({
            //                 minTime:$(this).val()?$(this).val():false
            //             })
            //         }
            //     });
            // });
        // });
    
    </script>
      
    @endpush

</x-site-layout>