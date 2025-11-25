<x-site-layout>
    <main id="main">
        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                    <li>Editar cupom</li>
                </ol>
                <h2>Editar cupom: {{ htmlspecialchars($coupon->code) }}</h2>
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
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('event_home.update_coupon', $hash) }}" id="coupon-form" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event_id }}">
                            
                            <div class="card-body">
                                <h4>Informações do cupom</h4>
                                
                                <div class="mb-3">
                                    <label for="code" class="form-label">
                                        Código do cupom
                                        <span class="text-danger" aria-label="obrigatório">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="code" 
                                        name="code" 
                                        placeholder="Código do cupom" 
                                        value="{{ htmlspecialchars($coupon->code ?? old('code')) }}"
                                        required
                                        aria-describedby="code-help"
                                        minlength="2"
                                        maxlength="50"
                                    >
                                    <div id="code-help" class="form-text">
                                        Código único para o cupom de desconto
                                    </div>
                                    <div class="invalid-feedback">Insira um código válido.</div>
                                </div>
                                
                                <div class="row mb-3 g-3">
                                    <div class="col-md-4">
                                        <label for="discount_type" class="form-label">
                                            Tipo de desconto
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="discount_type" name="discount_type" required>
                                            <option value="">Selecione</option>
                                            <option value="0" @if($coupon->discount_type == 0) selected @endif>Porcentagem (%)</option>
                                            <option value="1" @if($coupon->discount_type == 1) selected @endif>Valor fixo (R$)</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o tipo de desconto.</div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="discount_value" class="form-label">
                                            Valor do desconto
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="discount_prefix">
                                                @if($coupon->discount_type == 0)
                                                    %
                                                @else
                                                    R$
                                                @endif
                                            </span>
                                            @php
                                                // Formatar valor baseado no tipo
                                                $discountValue = '';
                                                if ($coupon->discount_type == 0) {
                                                    // Porcentagem: multiplicar por 100 para exibir (0.07 = 7%)
                                                    $discountValue = $coupon->discount_value ? number_format($coupon->discount_value * 100, 2, ',', '') : '';
                                                } else {
                                                    // Valor fixo: formatar como moeda
                                                    $discountValue = $coupon->discount_value ? number_format($coupon->discount_value, 2, ',', '') : '';
                                                }
                                            @endphp
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="discount_value" 
                                                name="discount_value" 
                                                placeholder="0,00" 
                                                value="{{ $discountValue ?: old('discount_value') }}"
                                                required
                                                aria-describedby="discount-help"
                                            >
                                        </div>
                                        <div id="discount-help" class="form-text">
                                            @if($coupon->discount_type == 0)
                                                Digite o valor em porcentagem (ex: 7 para 7%)
                                            @else
                                                Digite o valor fixo do desconto (ex: 10,00)
                                            @endif
                                        </div>
                                        <div class="invalid-feedback">Insira um valor válido.</div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3 g-3">
                                    <div class="col-md-6">
                                        <label for="limit_buy" class="form-label">
                                            Limite de compras
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            id="limit_buy" 
                                            name="limit_buy" 
                                            placeholder="0" 
                                            value="{{ $coupon->limit_buy ?? old('limit_buy') }}" 
                                            min="0"
                                            required
                                            aria-describedby="limit_buy-help"
                                        >
                                        <div id="limit_buy-help" class="form-text">
                                            Número máximo de vezes que este cupom pode ser usado
                                        </div>
                                        <div class="invalid-feedback">Insira um valor válido.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="limit_tickets" class="form-label">
                                            Limite de inscrições
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            id="limit_tickets" 
                                            name="limit_tickets" 
                                            placeholder="0" 
                                            value="{{ $coupon->limit_tickets ?? old('limit_tickets') }}" 
                                            min="0"
                                            required
                                            aria-describedby="limit_tickets-help"
                                        >
                                        <div id="limit_tickets-help" class="form-text">
                                            Número máximo de ingressos por compra com este cupom
                                        </div>
                                        <div class="invalid-feedback">Insira um valor válido.</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        Lotes que terão este cupom de desconto
                                    </label>
                                    <div class="card p-3" style="border: 1px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; max-height: 300px; overflow-y: auto;">
                                        @php
                                            $selectedLotes = [];
                                            foreach($coupon->lotes as $lote_coupons) {
                                                $selectedLotes[] = $lote_coupons->id;
                                            }
                                        @endphp
                                        @foreach($lotes as $lote)
                                            <div class="form-check mb-2">
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    name="lotes[]" 
                                                    value="{{ $lote->id }}" 
                                                    id="lote_{{ $lote->id }}"
                                                    @if(in_array($lote->id, $selectedLotes)) checked @endif
                                                >
                                                <label class="form-check-label" for="lote_{{ $lote->id }}" style="font-weight: normal;">
                                                    {{ htmlspecialchars($lote->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                        @if(count($lotes) == 0)
                                            <p class="text-muted mb-0">Nenhum lote disponível.</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="status" 
                                            id="status" 
                                            value="1"
                                            @if($coupon->status == 1) checked @endif
                                        >
                                        <label class="form-check-label" for="status">
                                            <strong>Ativar cupom</strong>
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        Cupons desativados não podem ser utilizados
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('event_home.create.step.three') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar
                                </a>
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <i class="fas fa-save me-2"></i>Salvar alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    
    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
    @endpush

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>

        <script>
        $(document).ready(function() {
            // Atualizar prefixo do desconto baseado no tipo
            $('#discount_type').change(function() {
                var type = $(this).val();
                var prefix = $('#discount_prefix');
                var help = $('#discount-help');
                
                if (type == '0') {
                    // Porcentagem
                    prefix.text('%');
                    help.text('Digite o valor em porcentagem (ex: 7 para 7%)');
                    // Aplicar máscara para porcentagem (sem vírgula)
                    $('#discount_value').unmask();
                    $('#discount_value').mask("#0", {
                        reverse: true
                    });
                } else if (type == '1') {
                    // Valor fixo
                    prefix.text('R$');
                    help.text('Digite o valor fixo do desconto (ex: 10,00)');
                    // Aplicar máscara para valor monetário
                    $('#discount_value').unmask();
                    $('#discount_value').mask("#.##0,00", {
                        reverse: true
                    });
                }
            });
            
            // Aplicar máscara inicial baseada no tipo atual
            var currentType = $('#discount_type').val();
            if (currentType == '0') {
                $('#discount_value').mask("#0", {
                    reverse: true
                });
            } else if (currentType == '1') {
                $('#discount_value').mask("#.##0,00", {
                    reverse: true
                });
            }
            
            // Validação do formulário - só adicionar was-validated no submit
            (function() {
                'use strict';
                var form = document.getElementById('coupon-form');
                if (form) {
                    // Remover was-validated se existir (caso tenha sido adicionado antes)
                    form.classList.remove('was-validated');
                    
                    // Validar no submit
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        // Só adicionar was-validated quando o formulário for submetido
                        form.classList.add('was-validated');
                    }, false);
                    
                    // Validar campos individualmente ao sair (blur)
                    var inputs = form.querySelectorAll('input, select, textarea');
                    inputs.forEach(function(input) {
                        input.addEventListener('blur', function() {
                            if (form.classList.contains('was-validated')) {
                                if (this.checkValidity()) {
                                    this.classList.remove('is-invalid');
                                    this.classList.add('is-valid');
                                } else {
                                    this.classList.remove('is-valid');
                                    this.classList.add('is-invalid');
                                }
                            }
                        });
                    });
                }
            })();
        });
        </script>
    @endpush

</x-site-layout>
