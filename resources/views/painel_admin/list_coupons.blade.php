<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="/">Home</a></li>
              <li><a href="/painel/meus-eventos">Meus eventos</a></li>
              <li>Cupons</li>
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
                        <div class="wizard-progress-bar" style="width: 75%"></div>
                    </div>
                    
                    <div class="wizard-steps">
                        <div class="step completed">
                            <div class="step-number">1</div>
                            <div class="step-label">Informações</div>
                        </div>
                        <div class="step completed">
                            <div class="step-number">2</div>
                            <div class="step-label">Inscrições</div>
                        </div>
                        <div class="step active">
                            <div class="step-number">3</div>
                            <div class="step-label">Cupons</div>
                        </div>
                        <div class="step">
                            <div class="step-number">4</div>
                            <div class="step-label">Publicar</div>
                        </div>
                    </div>
                    
                    <div class="wizard-content">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4>Listagem dos cupons</h4>
                            <a href="{{route('event_home.create_coupon', $hash_event)}}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Cadastrar novo cupom
                            </a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
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
                                    @foreach($coupons as $coupon)
                                        <tr data-coupon-hash="{{$coupon->hash}}">
                                            <td>{{$coupon->id}}</td>
                                            <td><span class="badge bg-light text-dark border">{{$coupon->code}}</span></td>
                                            <td>@if($coupon->discount_type == 0) Porcentagem @elseif($coupon->discount_type == 1) Fixo @endif</td>
                                            <td>@if($coupon->discount_type == 0) {{$coupon->discount_value*100}}% @elseif($coupon->discount_type == 1) @money($coupon->discount_value) @endif</td>
                                            <td>{{$coupon->limit_buy}}</td>
                                            <td>{{$coupon->limit_tickets}}</td>
                                            <td>
                                                @if($coupon->status == 1) 
                                                    <span class="badge bg-success">Ativo</span> 
                                                @else 
                                                    <span class="badge bg-danger">Inativo</span> 
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a class="btn btn-sm btn-outline-info" href="{{route('event_home.coupon_edit', $coupon->hash)}}" data-bs-toggle="tooltip" title="Editar">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="removeData('{{$coupon->hash}}')" data-bs-toggle="tooltip" title="Remover">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    
                                                    <form action="{{ route('event_home.destroy_coupon', $coupon->hash) }}" method="POST" id="form-remove-{{$coupon->hash}}" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if(count($coupons) == 0)
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-ticket-alt fa-2x mb-2"></i><br>
                                                Nenhum cupom cadastrado.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="wizard-actions">
                            <a href="{{ route('event_home.create.step.two') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Anterior
                            </a>
                            <a href="{{ route('event_home.create.step.four') }}" class="btn btn-primary">
                                Próximo<i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Modal de Remoção -->
        <div class="modal fade" id="modalMsgRemove" tabindex="-1" aria-labelledby="modalMsgRemoveLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMsgRemoveLabel">Remoção de cupom</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja realmente remover esse cupom?
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
            let couponHashToRemove = null;

            function removeData(hash){
                couponHashToRemove = hash;
                var modal = new bootstrap.Modal(document.getElementById('modalMsgRemove'));
                modal.show();
            }

            document.getElementById('btn-confirm-remove').addEventListener('click', function() {
                if (couponHashToRemove) {
                    const button = this;
                    // Mostra loading no botão
                    const originalText = button.innerHTML;
                    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...';
                    button.disabled = true;
                    
                    document.getElementById('form-remove-' + couponHashToRemove).submit();
                }
            });
            
            $(document).ready(function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            });
        </script>
      @endpush

</x-site-layout>