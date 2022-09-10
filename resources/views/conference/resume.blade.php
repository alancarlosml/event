<x-event-layout>
    <section id="checkout" class="section-bg" style="margin-top: 120px">
        <div class="container pb-5">
            <div class="py-5 text-center">
                <div class="section-header">
                    <h2>Finalizar compra</h2>
                </div>
                {{-- <p class="lead">Below is an example form built entirely with Bootstrap’s form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p> --}}
            </div>
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <div class="section-title-header text-center">
                        <h2 class="section-title wow fadeInUp animated">Total da compra</h2>
                    </div>
                    <ul class="list-group mb-3">
                        @foreach ($array_lotes as $array_lote)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>{{$array_lote[0]}} @if ($array_lote[0] == 1) ingresso @else ingressos @endif</div>
                                @if($array_lote[1] == 0)
                                <div class="text-muted">Grátis</div>
                                @else
                                <div class="text-muted">@money($array_lote[1])</div>
                                @endif
                            </div>
                        </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Subtotal</strong>
                            <span class="text-muted">@money($subtotal)</span>
                        </li>
                        @if ($coupon)
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <div class="text-success">
                                <h6 class="my-0">Cupom ({{$coupon[0]['code']}})</h6>
                            </div>
                            <span class="text-success">
                                -
                                @if($coupon[0]['type'] == 0)
                                    R$ {{number_format($subtotal * $coupon[0]['value']/100, 2, ',', '.')}} ({{$coupon[0]['value']}}%)
                                @else
                                    R$ {{$coupon[0]['value']}}
                                @endif
                            </span>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total</span>
                            {{-- <strong>{{'R$ '.number_format($valorSubTotalCoupon, 2, ',', '.') }}</strong> --}}
                        </li>
                    </ul>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12 text-right">
                                <strong>Compra 100% segura!&nbsp;&nbsp;&nbsp;</strong>
                                <img src="/img/stripe_logo.png" alt="Stripe Payment" height="50px"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 order-md-1">
                    <!-- Session Status -->
                    {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}
    
                    <!-- Validation Errors -->
                    {{-- <x-auth-validation-errors class="mb-4 alert alert-danger rounded" :errors="$errors" /> --}}
                    {{-- <form class="needs-validation" method="POST" action="https://ws.sandbox.pagseguro.uol.com.br/v2/sessions?email={{config('services.pagseguro.email')}}&token={{config('services.pagseguro.token')}}"> --}}
                    <form method="POST" id="checkout_submit" action="finish" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}">
                        @csrf
                        <div class="mb-3">
                            <div class="section-title-header text-center mb-4">
                                <h2 class="section-title wow fadeInUp animated">Dados pessoais</h2>
                            </div>
                            {{-- <div class="mb-3">
                                <label for="username">Nome completo</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nome completo" value="">
                                    <div class="invalid-feedback" style="width: 100%;"> Your username is required. </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email">E-mail <span class="text-muted"></span></label>
                                <input type="email" class="form-control" id="email"name="email" placeholder="email@provedor.com" value="">
                                <div class="invalid-feedback"> Please enter a valid email address for shipping updates. </div>
                            </div> --}}
                            @foreach ($questions as $id => $question)
                                @switch($question->option_id)
                                    @case(1)
                                        {{-- Campo texto --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="text" class="form-control new_field" name="new_field[]" value="" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(2)
                                        {{-- Campo seleção --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <select class="form-control new_field" name="new_field[]" @if($question->required) required @endif>
                                                <option value="0">Selecione</option>
                                                @foreach ($question->value() as $value)
                                                    <option>{{$value->value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @break
                                    @case(3)
                                        {{-- Campo marcação --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <select class="form-control new_field" name="new_field[]" @if($question->required) required @endif>
                                                <option value="0">Selecione</option>
                                                @foreach ($question->value() as $value)
                                                    <option>{{$value->value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @break
                                    @case(4)
                                        {{-- Campo multipla escolha --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <select class="form-control new_field" name="new_field[]" @if($question->required) required @endif>
                                                <option value="0">Selecione</option>
                                                @foreach ($question->value() as $value)
                                                    <option>{{$value->value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @break
                                    @case(5)
                                        {{-- Campo CPF --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="text" class="form-control new_field" name="new_field[]" value="" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(6)
                                        {{-- Campo CNPJ --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="text" class="form-control new_field" name="new_field[]" value="" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(7)
                                        {{-- Campo Data --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="date" class="form-control new_field" name="new_field[]" value="" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(8)
                                        {{-- Campo Telefone --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="text" class="form-control new_field" name="new_field[]" value="" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(9)
                                        {{-- Campo Número inteiro --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="number" class="form-control new_field" name="new_field[]" value="" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(10)
                                        {{-- Campo Número decimal --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="number" class="form-control new_field" name="new_field[]" value="" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(11)
                                        {{-- Campo arquivo --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="file" class="form-control new_field" name="new_field[]" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(12)
                                        {{-- Campo Textarea --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <textarea class="form-control new_field" name="new_field[]" value="" @if($question->required) required @endif></textarea>
                                        </div>
                                        @break
                                    @case(13)
                                        {{-- Campo Email --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="email" class="form-control new_field" name="new_field[]" value="" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(14)
                                        {{-- Campo estados --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <select class="form-control new_field" name="new_field[]" @if($question->required) required @endif>
                                                <option value="0">Selecione</option>
                                                @foreach ($question->value() as $value)
                                                    <option>{{$value->value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @break
                                
                                    @default
                                        
                                @endswitch
                            @endforeach
                        </div>

                        <div class="mb-4 mt-5">
                            <div class="section-title-header text-center mb-4">
                                <h2 class="section-title wow fadeInUp animated">Forma de pagamento</h2>
                            </div>
                            <div class="d-block my-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="creditCard" value="1">
                                    <label class="form-check-label" for="creditCard"><b>Cartão de crédito</b></label>
                                  </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="boleto" value="2">
                                    <label class="form-check-label" for="boleto"><b>Boleto</b></label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <p class="h4 mb-3"><strong>Dados do cartão de crédito</strong></p>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="cc-name">Nome impresso no cartão</label>
                                        <input type="text" class="form-control" id="cc-name" name="cc_name" placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cc-number-cc">Número do cartão de crédito</label>
                                        <div class="input-group">
                                            <input class="form-control cc_number_cc card-number" type="text" id="cc-number-cc" name="cc_number_cc"  placeholder="0000000000000000">
                                            <div class="input-group-append">
                                                <span class="input-group-text brand-img p-2">
                                                    <img src="img/credit-card-default.png" alt="" width="42px" height="20px">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback none"> Credit card number is required </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cc-expiration">Data de expiração</label>
                                        <input type="text" class="form-control cc_date" id="cc-expiration" name="cc_expiration" placeholder="00/0000">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cc-cvv">CVV</label>
                                        <input type="text" class="form-control cc_cvv card-cvc" id="cc-cvv" name="cc_cvv" placeholder="000">
                                        <input type="hidden" id="cc-brand" name="cc-brand">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <p class="h4 mb-3"><strong>Endereço de cobrança</strong></p>
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-9">
                                            <label for="cc_address">Endereço</label>
                                            <input type="text" class="form-control" id="cc_address" name="cc_address" placeholder="" value="{{ old('cc_address') ?? Auth::user()->cc_address ?? '' }}">
                                        </div>
                                        <div class="col-3">
                                            <label for="cc_number">Número</label>
                                            <input type="text" class="form-control" id="cc_number" name="cc_number" placeholder="" value="{{ old('cc_number') ?? Auth::user()->cc_number ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <label for="cc_address2">Complemento </label>
                                            <input type="text" class="form-control" id="cc_address2" name="cc_address2" placeholder="" value="{{ old('cc_address2') ?? Auth::user()->cc_address2 ?? '' }}">
                                        </div>
                                        <div class="col-4">
                                            <label for="cc_district">Bairro </label>
                                            <input type="text" class="form-control" id="cc_district" name="cc_district" placeholder="" value="{{ old('cc_district') ?? Auth::user()->cc_district ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="cc_state">Estado </label>
                                            <select class="form-control" id="cc_state" name="cc_state">
                                                <option value="AC">Acre</option>
                                                <option value="AL">Alagoas</option>
                                                <option value="AP">Amapá</option>
                                                <option value="AM">Amazonas</option>
                                                <option value="BA">Bahia</option>
                                                <option value="CE">Ceará</option>
                                                <option value="DF">Distrito Federal</option>
                                                <option value="ES">Espírito Santo</option>
                                                <option value="GO">Goiás</option>
                                                <option value="MA" selected>Maranhão</option>
                                                <option value="MT">Mato Grosso</option>
                                                <option value="MS">Mato Grosso do Sul</option>
                                                <option value="MG">Minas Gerais</option>
                                                <option value="PA">Pará</option>
                                                <option value="PB">Paraíba</option>
                                                <option value="PR">Paraná</option>
                                                <option value="PE">Pernambuco</option>
                                                <option value="PI">Piauí</option>
                                                <option value="RJ">Rio de Janeiro</option>
                                                <option value="RN">Rio Grande do Norte</option>
                                                <option value="RS">Rio Grande do Sul</option>
                                                <option value="RO">Rondônia</option>
                                                <option value="RR">Roraima</option>
                                                <option value="SC">Santa Catarina</option>
                                                <option value="SP">São Paulo</option>
                                                <option value="SE">Sergipe</option>
                                                <option value="TO">Tocantins</option>
                                            </select>
                                            {{-- <input type="text" class="form-control" id="cc_state" name="cc_state" placeholder="" value="{{ old('cc_state') ?? Auth::user()->cc_state ?? '' }}"> --}}
                                        </div>
                                        <div class="col-4">
                                            <label for="cc_city">Cidade </label>
                                            <input type="text" class="form-control" id="cc_city" name="cc_city" placeholder="" value="{{ old('cc_city') ?? Auth::user()->cc_city ?? '' }}">
                                        </div>
                                        <div class="col-4">
                                            <label for="cc_zip">CEP </label>
                                            <input type="text" class="form-control cep" id="cc_zip" name="cc_zip" placeholder="00000000" value="{{ old('cc_zip') ?? Auth::user()->cc_zip ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- <hr class="mb-4"> --}}
                        {{-- <input type="hidden" name="senderHash" id="senderHash" value="">
                        <input type="hidden" name="card_token" id="card_token" value="">
                        <input type="hidden" name="installmentValue" id="installmentValue" value=""> --}}
                        {{-- <a href="/finish" class="btn_next float-right">Finalizar compra</a> --}}
                        <button type="button" onclick="submitCheckout()" class="btn btn_primary btn_lg float-right" id="finalizar_comprar">Finalizar compra</button>
                        <button class="btn btn_primary btn_lg float-right d-none" type="button" id="carregando_comprar" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Finalizando...
                          </button>
                    </form>
                </div>
            </div>
        </div>
    
    </section><!-- End Contact Section -->

    @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('theme')
        @if($event->theme == 'red')
        <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/red.css') }}" type="text/css">
        @elseif($event->theme == 'blue')
        <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/blue.css') }}" type="text/css">
        @elseif($event->theme == 'green')
        <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/green.css') }}" type="text/css">
        @elseif($event->theme == 'purple')
        <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/purple.css') }}" type="text/css">
        @elseif($event->theme == 'orange')
        <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/orange.css') }}" type="text/css">
        @endif
    @endpush

    @push('head')

    @endpush

    @push('footer')
        <script src="assets_conference/js/bootstrap-input-spinner.js"></script>
        <script src="assets_conference/js/custom-editors.js"></script>
        <script>
            $(document).ready(function() {  
                $(".inp-number").inputSpinner({buttonsOnly: true, autoInterval: undefined})
            });

            function validSubmition(){

                // let customSelect = $('.custom-select').val();

                var count = 0;
                $(".inp-number").each(function() {
                    if($(this).val() !== '0'){
                        count++
                    }
                });

                if (count === 0) {

                    $('#cupomModal').modal('show');
                    $('#modal_txt').text('Por favor, selecione ao menos um ingresso.');
                    $('#modal_icon').attr('src', '/img/alert.png');
                    return false;
                }

                return true;
            }
        </script>
    @endpush

</x-event-layout>