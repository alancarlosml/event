<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="/">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Gerenciar evento</h2>
    
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                </div>
                <div class="card-body table-responsive p-0">
                    <ul id="progressbar">
                        <li class="active" id="account"><strong>Informações</strong></li>
                        <li class="active" id="personal"><strong>Inscrições</strong></li>
                        <li id="payment"><strong>Cupons</strong></li>
                        <li id="confirm"><strong>Publicar</strong></li>
                    </ul>
                    <div class="card-body" id="lote_field">
                        <h4 class="py-3">Listagem dos lotes
                        {{-- <div class="form-group text-right"> --}}
                            <a href="{{route('event_home.lote_create')}}" class="btn btn-success float-end">Cadastrar novo lote</a>
                        {{-- </div> --}}
                        </h4>
                        <table class="table table-head-fixed text-nowrap" id="table_lotes">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Valor</th>
                                    <th>Taxa</th>
                                    <th>Preço final</th>
                                    <th>Quantidade</th>
                                    <th>Visibilidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{dd($lotes)}} --}}
                                @if(isset($lotes))
                                    @foreach($lotes as $lote)
                                        <tr>
                                            <td>{{$lote->id}}</td>
                                            <td class="lote_hash" style="display: none;">{{$lote->hash}}</td>
                                            <td style="display: none;">
                                                <input type="number" class="order_lote" value="{{$lote->order}}" style="width: 40%" min="1">
                                            </td>
                                            <td>
                                                <b>{{$lote->name}}</b><br/>
                                                {{$lote->description}}
                                            </td>
                                            <td>@money($lote->value)</td>
                                            <td>@money($lote->tax)</td>
                                            <td>@money($lote->final_value)</td>
                                            <td>{{$lote->quantity}}</td>
                                            <td>@if($lote->visibility == 0) Público @else Privado @endif</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a class="btn btn-info btn-sm" href="{{route('event_home.lote_edit', $lote->hash)}}">
                                                        <i class="fas fa-pencil-alt">
                                                        </i>
                                                        Editar
                                                    </a>
                                                    <form action="{{ route('event_home.lote_delete', $lote->hash) }}" method="POST">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <a class="btn btn-danger btn-sm ms-1" href="javascript:;" onclick="removeData('{{$lote->hash}}')">
                                                            <i class="fas fa-trash">
                                                            </i>
                                                            Remover
                                                        </a>
                                                        <button class="d-none" id="btn-remove-hidden-{{$lote->hash}}">Remover</button>
                                                    </form>
                                                    <a class="btn btn-secondary btn-sm ms-1 up" href="javascript:;">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </a>
                                                    <a class="btn btn-secondary btn-sm ms-1 down" href="javascript:;">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <div class="modal fade modalMsgRemove" id="modalMsgRemove-{{$lote->hash}}" tabindex="-1" role="dialog" aria-labelledby="modalMsgRemoveLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalMsgRemoveLabel">Remoção de evento</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Deseja realmente remover esse lote?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" id="btn-remove-ok-{{$lote->hash}}" onclick="removeSucc('{{$lote->hash}}')">Sim</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    @endforeach
                                @else
                                        <tr><td>Nenhum lote cadastrado.</td></tr>
                                @endif
                            </tbody>
                        </table>
                        <form method="POST" action="{{route('event_home.save_lotes', $event_id)}}">
                            @csrf
                            @if(isset($lotes))
                                @foreach($lotes as $lote)
                                    <input type="hidden" name="order_lote[]" id="lote_{{$lote->hash}}" value="{{$lote->hash}}_{{$lote->order}}">
                                @endforeach
                            @endif
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('event_home.create.step.one') }}" class="btn btn-secondary">Anterior</a>
                                <button type="submit" class="btn btn-primary">Próximo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('footer')

        <script>

            function removeData(hash){
                $('#modalMsgRemove-' + hash).modal('show');
            }

            function removeSucc(hash){
                const button = $('#btn-remove-ok-' + hash);
                
                // Mostra loading no botão
                setButtonLoading(button[0], 'Excluindo...');
                
                // Executa a remoção
                $('#btn-remove-hidden-' + hash).click();
                
                // Fecha o modal
                $('#modalMsgRemove-' + hash).modal('hide');
                
                // Mostra notificação de sucesso
                showToast('Lote removido com sucesso!', 'success');
                
                // Remove a linha da tabela após um pequeno delay
                setTimeout(() => {
                    const row = $(`tr[data-lote-hash="${hash}"]`);
                    if (row.length) {
                        row.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                }, 500);
            }

            $(document).ready(function() {

                $("#lote_field .up:first").hide();
                $("#lote_field .down:last").hide();

                $('.order_lote').change(function(){
                    id = $(this).attr('id');
                    value = $(this).val();
                    console.log(value);
                    $('#lote_' + id).val(id + '_' + value);
                });

                $(".up,.down").click(function () {
                    
                    var $element = this;
                    var row = $($element).parents("tr:first");
                
                    if($(this).is('.up')){
                        hash_this = $(this).parents('tr').find('.lote_hash').text();
                        hash_prev = row.prev().find('.lote_hash').text();
                        if(hash_prev != ''){
                            console.log(hash_prev);
                                val_this = $('#lote_' + hash_this).val();
                                val_prev = $('#lote_' + hash_prev).val();
                                id_this = parseInt(val_this.split('_')[1]) - 1;
                                id_prev = parseInt(val_prev.split('_')[1]) + 1;
                                $('#lote_' + hash_this).val(hash_this + '_' + id_this);
                                $('#lote_' + hash_prev).val(hash_prev + '_' + id_prev);
                                console.log(id_this);
                                console.log(id_prev);
                                row.insertBefore(row.prev());
                        }
                    }
                    else{
                        hash_this = $(this).parents('tr').find('.lote_hash').text();
                        hash_next = row.next().find('.lote_hash').text();
                        if(hash_next != ''){
                            console.log(hash_next);
                                val_this = $('#lote_' + hash_this).val();
                                val_next = $('#lote_' + hash_next).val();
                                id_this = parseInt(val_this.split('_')[1]) + 1;
                                id_next = parseInt(val_next.split('_')[1]) - 1;
                                $('#lote_' + hash_this).val(hash_this + '_' + id_this);
                                $('#lote_' + hash_next).val(hash_next + '_' + id_next);
                                console.log(id_this);
                                console.log(id_next);
                                row.insertAfter(row.next());
                        }
                    }

                    $("#lote_field .up:first").hide();
                    $("#lote_field .down:last").hide();
                    $("#lote_field .up:not(:first)").show();
                    $("#lote_field .down:not(:last)").show();
            });
        });
        
        </script>
      
    @endpush

</x-site-layout>