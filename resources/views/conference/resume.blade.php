<x-event-layout>
    @push('head')
        <link rel="stylesheet" href="{{ asset('assets_conference/css/modern-conference.css') }}" type="text/css">
    @endpush
    
    <section id="checkout" class="section-bg" style="margin-top: 120px">
        <div class="container pb-5">
            <!-- Progresso do Checkout -->
            <div class="checkout-progress">
                <div class="checkout-progress-steps">
                    <div class="checkout-step completed">
                        <div class="checkout-step-number">1</div>
                        <div class="checkout-step-label">Selecionar Ingressos</div>
                    </div>
                    <div class="checkout-step active">
                        <div class="checkout-step-number">2</div>
                        <div class="checkout-step-label">Dados da Inscrição</div>
                    </div>
                    <div class="checkout-step">
                        <div class="checkout-step-number">3</div>
                        <div class="checkout-step-label">Pagamento</div>
                    </div>
                    <div class="checkout-step">
                        <div class="checkout-step-number">4</div>
                        <div class="checkout-step-label">Confirmação</div>
                    </div>
                </div>
            </div>
            
            <div class="row gy-4">
                <!-- Resumo da Compra (Sticky) -->
                <div class="col-md-4 order-md-2 mb-4">
                    <div class="checkout-summary">
                        <h3 class="checkout-summary-title">
                            <i class="fas fa-shopping-cart"></i> Resumo da compra
                        </h3>
                        
                        <!-- Card do Evento -->
                        <div class="checkout-event-card">
                            <img src="{{ URL::asset('storage/' . $event->banner) }}" alt="{{ $event->name }}" class="checkout-event-image" loading="lazy">
                            <div class="checkout-event-info">
                                <div class="checkout-event-name">{{ $event->name }}</div>
                                <div class="checkout-event-details">
                                    <div><i class="fas fa-map-marker-alt"></i> {{ $event->place->name }}</div>
                                    <div><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($event->min_event_dates())->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Itens -->
                        <div class="checkout-items-list">
                            @foreach ($array_lotes as $array_lote)
                                <div class="checkout-item">
                                    <div class="checkout-item-name">
                                        <strong>{{ $array_lote['name'] }}</strong>
                                    </div>
                                    <div class="checkout-item-quantity">
                                        {{ $array_lote['quantity'] }}x
                                    </div>
                                    <div class="checkout-item-price {{ $array_lote['value'] == 0 ? 'checkout-item-free' : '' }}">
                                        @if ($array_lote['value'] == 0)
                                            Grátis
                                        @else
                                            @money($array_lote['value'] * $array_lote['quantity'])
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Totais -->
                        <div class="checkout-totals">
                            <div class="checkout-total-row subtotal">
                                <span>Subtotal</span>
                                <span>@money($subtotal)</span>
                            </div>
                            @if ($coupon)
                                <div class="checkout-total-row coupon">
                                    <span>
                                        <i class="fas fa-tag"></i> Cupom ({{ $coupon[0]['code'] }})
                                    </span>
                                    <span>
                                        -
                                        @if ($coupon[0]['type'] == 0)
                                            @money(($subtotal * $coupon[0]['value']) / 100)
                                        @else
                                            @money($coupon[0]['value'])
                                        @endif
                                    </span>
                                </div>
                            @endif
                            <div class="checkout-total-row final">
                                <span>Total</span>
                                <span>@money($total)</span>
                            </div>
                        </div>
                        
                        <!-- Segurança -->
                        <div class="checkout-security">
                            <div class="checkout-security-title">
                                <i class="fas fa-shield-alt"></i>
                                <span>Compra 100% segura</span>
                            </div>
                            <div class="checkout-security-logo">
                                <img src="{{ asset('assets_conference/imgs/mercado-pago-logo.png') }}" alt="Mercado Pago" height="30px" loading="lazy" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 order-md-1">
                    <div class="checkout-form-container">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ $message }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Erros encontrados:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Container para exibir os erros de validação --}}
                        <div id="validation-errors" class="alert alert-danger" style="display: none;">
                            <strong>Por favor, corrija os seguintes erros:</strong>
                            <ul></ul>
                        </div>
                        
                        <form method="POST" id="checkout_submit" action="{{ route('conference.payment', $event->slug) }}" data-use-ajax="{{ $total > 0 ? '1' : '0' }}">
                            @csrf
                            @if($total > 0)
                                <input type="hidden" name="_response" value="json">
                            @endif
                            <div class="checkout-form-section">
                                <h3 class="checkout-form-section-title">
                                    <i class="fas fa-user-edit"></i>
                                    Dados da inscrição
                                </h3>
                                
                                @foreach ($array_lotes_obj as $k => $lote_obj)
                                    <div class="participant-card">
                                        <div class="participant-card-header">
                                            <div class="participant-card-number">{{ $k + 1 }}</div>
                                            <h4 class="participant-card-title">Participante #{{ $k + 1 }}</h4>
                                            <span class="participant-card-lote">{{ $lote_obj['name'] }}</span>
                                        </div>
                                @foreach ($questions as $id => $question)
                                    @switch($question->option_id)
                                        @case(1)
                                            {{-- Campo texto --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <input type="text" 
                                                    class="form-control-improved new_field"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    placeholder="Digite {{ strtolower($question->question) }}"
                                                    @if ($question->required) required="required" title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(2)
                                            {{-- Campo seleção --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <select class="form-control-improved new_field"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                                    <option value="0">Selecione uma opção</option>
                                                    @foreach ($question->value() as $value)
                                                        <option>{{ $value->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @break

                                        @case(3)
                                            {{-- Campo marcação --}}
                                            <div class="form-group-improved">
                                                <label class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <p class="container_radio mt-2 mb-0">
                                                    @foreach ($question->value() as $value)
                                                        <input type="radio" name="new_field_radio"
                                                            @if ($question->required) required title="Este campo é obrigatório" @endif />
                                                        {{ $value->value }}
                                                    @endforeach
                                                </p>
                                            </div>
                                        @break

                                        @case(4)
                                            {{-- Campo multipla escolha --}}
                                            <div class="form-group-improved">
                                                <label class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <p class="container_checkbox mt-2 mb-0">
                                                    @foreach ($question->value() as $value)
                                                        <input type="checkbox" name="new_field_checbox[]"
                                                            @if ($question->required) required title="Este campo é obrigatório" @endif />
                                                        {{ $value->value }}
                                                    @endforeach
                                                </p>
                                            </div>
                                        @break

                                        @case(5)
                                            {{-- Campo CPF --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <input type="text"
                                                    class="form-control-improved new_field cpf_mask"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    placeholder="Digite o CPF"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(6)
                                            {{-- Campo CNPJ --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <input type="text"
                                                    class="form-control-improved new_field cnpj_mask"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    placeholder="Digite o CNPJ"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(7)
                                            {{-- Campo Data --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <input type="date"
                                                    class="form-control-improved new_field date_mask"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(8)
                                            {{-- Campo Telefone --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <input type="text"
                                                    class="form-control-improved new_field phone_with_ddd_mask"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    placeholder="Digite o telefone"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(9)
                                            {{-- Campo Número inteiro --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <input type="number"
                                                    class="form-control-improved new_field"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    placeholder="Digite {{ strtolower($question->question) }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(10)
                                            {{-- Campo Número decimal --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <input type="number" step="any"
                                                    class="form-control-improved new_field"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    placeholder="Digite {{ strtolower($question->question) }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(11)
                                            {{-- Campo arquivo --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <input type="file"
                                                    class="form-control-improved new_field"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(12)
                                            {{-- Campo Textarea --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <textarea class="form-control-improved new_field"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    rows="4"
                                                    placeholder="Digite {{ strtolower($question->question) }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif></textarea>
                                            </div>
                                        @break

                                        @case(13)
                                            {{-- Campo Email --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <input type="email"
                                                    class="form-control-improved new_field"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    placeholder="Digite {{ strtolower($question->question) }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(14)
                                            {{-- Campo estados --}}
                                            <div class="form-group-improved">
                                                <label for="new_field_{{ $k + 1 }}_{{ $question->id }}" class="form-label-improved">
                                                    {{ $question->question }}
                                                    @if ($question->required == 1)
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <select class="form-control-improved new_field"
                                                    id="new_field_{{ $k + 1 }}_{{ $question->id }}"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                                    <option value="0">Selecione</option>
                                                    @if(isset($states))
                                                        @foreach ($states as $state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    @else
                                                        {{-- Fallback: usar valores da option se estados não estiverem disponíveis --}}
                                                        @php
                                                            $option = $question->option;
                                                            if($option && $option->value) {
                                                                $stateCodes = explode(',', $option->value);
                                                                $stateNames = [
                                                                    'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
                                                                    'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
                                                                    'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
                                                                    'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
                                                                    'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
                                                                    'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
                                                                    'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
                                                                ];
                                                                foreach($stateCodes as $code) {
                                                                    $code = trim($code);
                                                                    if(isset($stateNames[$code])) {
                                                                        echo "<option value=\"{$code}\">{$stateNames[$code]}</option>";
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                    @endif
                                                </select>
                                            </div>
                                        @break

                                        @default
                                    @endswitch
                                @endforeach
                                    </div>
                                    @if (!$loop->last)
                                        <hr style="margin: 2rem 0; border-color: #e9ecef;">
                                    @endif
                                @endforeach
                            </div>
                            
                            <button type="submit" class="checkout-submit-btn" id="finalizar_comprar">
                                <i class="fas fa-credit-card"></i>
                                Continuar para pagamento
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade mt-5" id="cupomModal" tabindex="-1" role="dialog"
            aria-labelledby="cupomModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong class="modal-title" id="cupomModalLabel"><img id="modal_icon"
                                src="/assets_conference/imgs/success.png" style="max-height: 48px"> <span
                                id="modal_txt">Cupom adicionado com sucesso!</span></strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="modal_close" class="btn btn-secondary"
                            data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- End Contact Section -->

    @push('theme')
        @if ($event->theme == 'red')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/red.css') }}" type="text/css">
        @elseif ($event->theme == 'blue')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/blue.css') }}" type="text/css">
        @elseif ($event->theme == 'green')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/green.css') }}" type="text/css">
        @elseif ($event->theme == 'purple')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/purple.css') }}" type="text/css">
        @elseif ($event->theme == 'orange')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/orange.css') }}" type="text/css">
        @endif
    @endpush

    @push('head')
    @endpush

    @push('footer')
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
        <script>
            $(document).ready(function() {

                $('#checkout_submit').validate({
                    errorClass: "error fail-alert",

                    errorPlacement: function(error, element) {
                        if (element.is(":radio")) {
                            error.insertAfter(element.parents('.container_radio'));
                        } else if (element.is(":checkbox")) {
                            error.insertAfter(element.parents('.container_checkbox'));
                        } else { // This is the default behavior 
                            error.insertAfter(element);
                        }
                    }
                });

                $('#finalizar_comprar').show();
                $('#carregando_comprar').addClass('d-none');

                // $('.date_mask').mask('00/00/0000', {placeholder: "__/__/____"});
                // $('.cep').mask('00000-000');
                $('.phone_with_ddd_mask').mask('(00) 00000-0000');
                $('.cpf_mask').mask('000.000.000-00', {
                    reverse: false
                });
                $('.cnpj_mask').mask('00.000.000/0000-00', {
                    reverse: false
                });
                $('.cep_mask').mask('00000-000');
                $('.expiration_mask').mask('00/0000');
                $('.cc_cvc_mask').mask('000');
                $('.cc_number_mask').mask('YYYY YYYY YYYY YYYY', {
                    'translation': {
                        Y: {
                            pattern: /[0-9]/
                        }
                    }
                });
                $('.cc_cvc_mask').mask('YYY', {
                    'translation': {
                        Y: {
                            pattern: /[0-9]/
                        }
                    }
                });

            });


            $(document).on('submit', '#checkout_submit', function(e) {
                var $form = $(this);
                if ($form.data('use-ajax') !== '1') return; // evento gratuito: submit normal

                if (!$form.valid()) return;

                e.preventDefault();
                var $btn = $('#finalizar_comprar');
                var url = $form.attr('action');
                var data = $form.serialize();

                $btn.prop('disabled', true);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).done(function(r) {
                    if (r.success && r.redirect) {
                        window.location.href = r.redirect;
                        return;
                    }
                    if (r.redirect) {
                        window.location.href = r.redirect;
                        return;
                    }
                    var err = (r.errors && r.errors.length) ? r.errors : ['Erro ao processar.'];
                    var $errDiv = $('#validation-errors');
                    $errDiv.find('ul').html(err.map(function(m){ return '<li>' + m + '</li>'; }).join(''));
                    $errDiv.show();
                    $btn.prop('disabled', false);
                }).fail(function(xhr) {
                    var msg = 'Erro de conexão. Tente novamente.';
                    if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.length) {
                        msg = xhr.responseJSON.errors;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    var $errDiv = $('#validation-errors');
                    $errDiv.find('ul').html(Array.isArray(msg) ? msg.map(function(m){ return '<li>' + m + '</li>'; }).join('') : '<li>' + msg + '</li>');
                    $errDiv.show();
                    $btn.prop('disabled', false);
                });
            });
        </script>
    @endpush

</x-event-layout>
