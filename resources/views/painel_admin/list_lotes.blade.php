<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="/">Home</a></li>
              <li><a href="/painel/meus-eventos">Meus eventos</a></li>
              <li>Lotes</li>
            </ol>
            <h2>Gerenciar evento</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="mb-3 px-3">
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
                
                <div class="wizard-container">
                    <div class="wizard-progress">
                        <div class="wizard-progress-bar" style="width: 50%"></div>
                    </div>
                    
                    <div class="wizard-steps">
                        <div class="step completed">
                            <div class="step-number">1</div>
                            <div class="step-label">Informações</div>
                        </div>
                        <div class="step active">
                            <div class="step-number">2</div>
                            <div class="step-label">Inscrições</div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-label">Cupons</div>
                        </div>
                        <div class="step">
                            <div class="step-number">4</div>
                            <div class="step-label">Publicar</div>
                        </div>
                    </div>
                    
                    <div class="wizard-content" id="lote_field">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4>Listagem dos lotes</h4>
                            <a href="{{route('event_home.lote_create')}}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Cadastrar novo lote
                            </a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="table_lotes">
                                <thead class="table-light">
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
                                    @if(isset($lotes) && count($lotes) > 0)
                                        @foreach($lotes as $lote)
                                            <tr data-lote-hash="{{$lote->hash}}">
                                                <td>{{$lote->id}}</td>
                                                <td class="lote_hash d-none">{{$lote->hash}}</td>
                                                <td class="d-none">
                                                    <input type="number" class="order_lote" value="{{$lote->order}}" style="width: 40%" min="1">
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{$lote->name}}</div>
                                                    <small class="text-muted">{{ Str::limit($lote->description, 50) }}</small>
                                                </td>
                                                <td>@money($lote->value)</td>
                                                <td>@money($lote->tax)</td>
                                                <td><span class="fw-bold text-success">@money($lote->final_value)</span></td>
                                                <td>{{$lote->quantity}}</td>
                                                <td>
                                                    @if($lote->visibility == 0) 
                                                        <span class="badge bg-primary">Público</span> 
                                                    @else 
                                                        <span class="badge bg-secondary">Privado</span> 
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a class="btn btn-sm btn-outline-info" href="{{route('event_home.lote_edit', $lote->hash)}}" data-bs-toggle="tooltip" title="Editar">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        
                                                        <button class="btn btn-sm btn-outline-danger" onclick="removeData('{{$lote->hash}}')" data-bs-toggle="tooltip" title="Remover">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        
                                                        <form action="{{ route('event_home.lote_delete', $lote->hash) }}" method="POST" id="form-remove-{{$lote->hash}}" class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        
                                                        <button class="btn btn-sm btn-outline-secondary up" data-bs-toggle="tooltip" title="Mover para cima">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary down" data-bs-toggle="tooltip" title="Mover para baixo">
                                                            <i class="fas fa-arrow-down"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-ticket-alt fa-2x mb-2"></i><br>
                                                Nenhum lote cadastrado.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <form method="POST" action="{{route('event_home.save_lotes', $event_id)}}">
                            @csrf
                            @if(isset($lotes))
                                @foreach($lotes as $lote)
                                    <input type="hidden" name="order_lote[]" id="lote_{{$lote->hash}}" value="{{$lote->hash}}_{{$lote->order}}">
                                @endforeach
                            @endif
                            
                            <div class="wizard-actions">
                                <a href="{{ route('event_home.create.step.one') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Anterior
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Próximo<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Modal de Remoção -->
        <div class="modal fade" id="modalMsgRemove" tabindex="-1" aria-labelledby="modalMsgRemoveLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMsgRemoveLabel">Remoção de lote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja realmente remover esse lote?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btn-confirm-remove">Sim, remover</button>
                    </div>
                </div>
            </div>
        </div>
    
      </main><!-- End #main -->

      @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
      @endpush

      @push('footer')
        <script>
            let loteHashToRemove = null;

            function removeData(hash){
                loteHashToRemove = hash;
                var modal = new bootstrap.Modal(document.getElementById('modalMsgRemove'));
                modal.show();
            }

            document.getElementById('btn-confirm-remove').addEventListener('click', function() {
                if (loteHashToRemove) {
                    const button = this;
                    // Mostra loading no botão
                    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...';
                    button.disabled = true;
                    
                    document.getElementById('form-remove-' + loteHashToRemove).submit();
                }
            });

            $(document).ready(function() {
                // Tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                // Reordering logic
                function updateArrows() {
                    $("#lote_field .up").show();
                    $("#lote_field .down").show();
                    $("#lote_field tbody tr:first .up").hide();
                    $("#lote_field tbody tr:last .down").hide();
                }
                
                updateArrows();

                $('.order_lote').change(function(){
                    var id = $(this).attr('id');
                    var value = $(this).val();
                    $('#lote_' + id).val(id + '_' + value);
                });

                $(".up, .down").click(function (e) {
                    e.preventDefault(); // Prevent default button behavior
                    
                    var $element = this;
                    var row = $($element).closest("tr");
                
                    if($(this).hasClass('up')){
                        var hash_this = row.find('.lote_hash').text();
                        var prev_row = row.prev();
                        var hash_prev = prev_row.find('.lote_hash').text();
                        
                        if(hash_prev != ''){
                            var val_this = $('#lote_' + hash_this).val();
                            var val_prev = $('#lote_' + hash_prev).val();
                            
                            var id_this = parseInt(val_this.split('_')[1]) - 1;
                            var id_prev = parseInt(val_prev.split('_')[1]) + 1;
                            
                            $('#lote_' + hash_this).val(hash_this + '_' + id_this);
                            $('#lote_' + hash_prev).val(hash_prev + '_' + id_prev);
                            
                            row.insertBefore(prev_row);
                        }
                    }
                    else {
                        var hash_this = row.find('.lote_hash').text();
                        var next_row = row.next();
                        var hash_next = next_row.find('.lote_hash').text();
                        
                        if(hash_next != ''){
                            var val_this = $('#lote_' + hash_this).val();
                            var val_next = $('#lote_' + hash_next).val();
                            
                            var id_this = parseInt(val_this.split('_')[1]) + 1;
                            var id_next = parseInt(val_next.split('_')[1]) - 1;
                            
                            $('#lote_' + hash_this).val(hash_this + '_' + id_this);
                            $('#lote_' + hash_next).val(hash_next + '_' + id_next);
                            
                            row.insertAfter(next_row);
                        }
                    }

                    updateArrows();
                });
            });
        </script>
      @endpush

</x-site-layout>