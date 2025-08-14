<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">Meus eventos</a></li>
            </ol>
            <h2>Venda realizada</h2>
    
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
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Default box -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 mt-1"><b>Detalhes do comprador</b></h5>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <div class="container">
                                        <div class="row">
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="id">CPF</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{$order->participante->cpf}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Nome</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{$order->participante->name}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Email</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{$order->participante->email}} @else - @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="subtitle">Telefone</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{$order->participante->phone}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Data criação</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->participante)){{ \Carbon\Carbon::parse($order->participante->created_at)->format('d/m/Y H:i') }} @else - @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <!-- /.card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 mt-1"><b>Detalhes da venda</b></h5>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <div class="container">
                                        <div class="row">
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="id">Hash da venda</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->order_hash)){{$order->order_hash}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Hash Mercado Pago</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->gatway_hash)){{$order->gatway_hash}} @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Referência Mercado Pago</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->gatway_reference)){{$order->gatway_reference}} @else - @endif
                                                    </p>
                                                </div>
                                                
                                            </div>
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="subtitle">Status da compra</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->situacao)) @if($order->situacao == 1) Confirmado @elseif($order->situacao == 2) Pendente @elseif($order->situacao == 3) Cancelado @endif @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="slug">Forma pagamento</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->gatway_payment_method)) @if($order->gatway_payment_method == 'credit') Crédito @elseif($order->gatway_payment_method == 'boleto') Boleto @elseif($order->gatway_payment_method == 'pix') Pix @else  Não informado @endif @else - @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="slug">Data da compra</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order->created_at)){{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }} @else - @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            @if(isset($order->order_items))
                            @foreach($order->order_items as $k => $order_item)
                            <!-- /.card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 mt-1"><b>Participante #{{$k+1}}</b></h5>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <div class="container">
                                        <div class="row">
                                            <div class="card-body col-6">
                                                <div class="form-group">
                                                    <label for="id">Hash participante</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        {{$order_item->hash}}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="id">Nº inscrição</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        {{$order_item->number}}
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Lote</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if(isset($order_item->lote)){{$order_item->lote->name}} @else - @endif
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
                                                        @if($order_item->date_use){{ \Carbon\Carbon::parse($order_item->date_use)->format('d/m/Y H:i') }} @else Não utilizado @endif
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subtitle">Status da compra do participante</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        @if($order_item->status == 1) Confirmado @elseif($order_item->status == 2) Pendente @elseif($order_item->status == 3) Cancelado  @elseif($order_item->status == 4) Estorno @else - @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @if(isset($order_item->answers))
                                            <div class="card-body col-6">
                                                @foreach($order_item->answers as $answer)
                                                <div class="form-group">
                                                    <label for="subtitle">{{$answer->question->question}}</label>
                                                    <p class="text-muted" style="font-size: 18px">
                                                        {{$answer->answer}}
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
            </div>
        </section>

      @push('head')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

            function removeData(id){
                $('#modalMsgRemove-' + id).modal('show');
            }

            function removeSucc(id){
                $('#btn-remove-hidden-' + id).click();
            }

            $(document).ready(function() {
               
            });
            
        </script>
      
    @endpush

</x-site-layout>