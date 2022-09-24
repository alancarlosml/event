<x-event-layout>
    <section id="checkout" class="section-bg" style="margin-top: 120px">
        <div class="container pb-5">
            <div class="py-5 text-center">
                <div class="section-header">
                    <h2>Finalizar compra</h2>
                </div>
                {{-- <p class="lead">Below is an example form built entirely with Bootstrap’s form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p> --}}
            </div>
            <div class="card mb-5 pt-3 pb-3">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <img src="{{ URL::asset('storage/'.$event->banner) }}" alt="{{ $event->name}}" class="img-fluid">
                        </div>
                        <div class="col-8">
                            <h3 style="font-size: 24px">{{$event->name}}</h3>
                            <span><b>Data:</b> 12/08/2022</span><br>
                            <span><b>Local:</b> {{$event->place->name}}</span><br>
                            <span><b>Lote(s) selecionado(s):</b> </span>
                            {{-- {{dd($array_lotes)}} --}}
                            <ul class="list-style">
                                @foreach ($array_lotes as $array_lote)
                                    <li class="ml-4" style="list-style-type: circle">{{$array_lote[0]}}x {{$array_lote[2]}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
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
                            <span>Subtotal</span>
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
                                    @money($subtotal * $coupon[0]['value']/100)
                                @else
                                    @money($coupon[0]['value'])
                                @endif
                            </span>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong>@money($total)</strong>
                        </li>
                    </ul>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12 text-right">
                                <strong>Compra 100% segura!&nbsp;&nbsp;&nbsp;</strong>
                                <img src="/assets_conference/imgs/moip-logo.png" alt="Moip By PagSeguro" height="80px"/>
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
                    <form method="POST" id="checkout_submit" action="{{route('conference.thanks', $event->slug)}}">
                        @csrf
                        <div class="mb-3">
                            <div class="section-title-header text-center mb-4">
                                <h2 class="section-title wow fadeInUp animated">Dados da inscrição</h2>
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
                                            <input type="text" class="form-control new_field" name="newfield_{{$question->id}}" @if($question->required) required="required" @endif>
                                        </div>
                                        @break
                                    @case(2)
                                        {{-- Campo seleção --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <select class="form-control new_field" name="newfield_{{$question->id}}" @if($question->required) required @endif>
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
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label><br/>
                                            @foreach ($question->value() as $value)
                                                <input type="radio" name="new_field_radio" @if($question->required) required @endif/> {{$value->value}}
                                            @endforeach
                                        </div>
                                        @break
                                    @case(4)
                                        {{-- Campo multipla escolha --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            @foreach ($question->value() as $value)
                                                <input type="checkbox" name="new_field_checbox[]" @if($question->required) required @endif/> {{$value->value}}
                                            @endforeach
                                        </div>
                                        @break
                                    @case(5)
                                        {{-- Campo CPF --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="text" class="form-control new_field cpf_mask" name="newfield_{{$question->id}}" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(6)
                                        {{-- Campo CNPJ --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="text" class="form-control new_field cnpj_mask" name="newfield_{{$question->id}}" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(7)
                                        {{-- Campo Data --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="date" class="form-control new_field date_mask" name="newfield_{{$question->id}}" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(8)
                                        {{-- Campo Telefone --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="text" class="form-control new_field phone_with_ddd_mask" name="newfield_{{$question->id}}" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(9)
                                        {{-- Campo Número inteiro --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="number" class="form-control new_field" name="newfield_{{$question->id}}" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(10)
                                        {{-- Campo Número decimal --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="number" class="form-control new_field" name="newfield_{{$question->id}}" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(11)
                                        {{-- Campo arquivo --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="file" class="form-control new_field" name="newfield_{{$question->id}}" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(12)
                                        {{-- Campo Textarea --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <textarea class="form-control new_field" name="newfield_{{$question->id}}" @if($question->required) required @endif></textarea>
                                        </div>
                                        @break
                                    @case(13)
                                        {{-- Campo Email --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <input type="email" class="form-control new_field" name="newfield_{{$question->id}}" @if($question->required) required @endif>
                                        </div>
                                        @break
                                    @case(14)
                                        {{-- Campo estados --}}
                                        <div class="form-group">
                                            <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                            <select class="form-control new_field" name="newfield_{{$question->id}}" @if($question->required) required @endif>
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
                                    <input class="form-check-input" type="radio" name="payment_form_check" id="creditCard" value="1">
                                    <label class="form-check-label" for="creditCard"><b>Cartão de crédito</b></label>
                                  </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_form_check" id="boleto" value="2">
                                    <label class="form-check-label" for="boleto"><b>Boleto</b></label>
                                </div>
                            </div>
                            
                            <div id="payment_form" style="display: none;">
                                <div class="mb-3">
                                    <p class="h4 mb-3"><strong>Dados do cartão de crédito</strong></p>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="cc-name">Nome impresso no cartão*</label>
                                            <input type="text" class="form-control" id="cc_name" name="cc_name" placeholder="" value="Comprador da Silva">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cc-number-cc">Número do cartão de crédito*</label>
                                            <div class="input-group">
                                                <input class="form-control cc_number_mask" type="text" id="cc_number_cc" name="cc_number_cc"  placeholder="0000 0000 0000 0000" value="4012001037141112">
                                                <div class="input-group-append">
                                                    <span class="input-group-text brand-img p-2">
                                                        <img src="{{ asset('assets_conference/imgs/credit-card.png') }}" alt="" width="24px" height="24px">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cc-expiration">Data de expiração*</label>
                                            <input type="text" class="form-control expiration_mask" id="cc_expiration" name="cc_expiration" placeholder="00/0000" value="06/2024">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cc-cvv">CVC*</label>
                                            <input type="text" class="form-control cc_cvc_mask" id="cc_cvc" name="cc_cvc" placeholder="000" value="123">
                                            <input type="hidden" id="cc-brand" name="cc-brand">
                                        </div>
                                        <textarea id="public_key" name="public_key" style="display:none;">MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl/Ui7+/93mmx9PKXcfHrAgTmaHdL3qfp39UQw590zWQGOWH9O5ERWomkv2jtX1Ydxo+hNDg9UIqRqwCmqjgsxRBFXj8dm/tg2clwlPishn/kSbs5+I70hPFUoH6gNj+6w8pCnSqfQM33MBkAe5Y0r6YetdXi8TLJVWvtW97fIdJxK+TG3etMIIdjubnJJk48Gsr21ZoXv0hg4ML12yZBcHJgiRalDqg54R96utuFXGE7snp4PKKPvAF6lgKiO1YlxPQkfg156Q4jXdcNyUITGF1VxlcaTQH36mP3vea1/nm5qD4YcY/i5SUoGXUyNg5fHIilmvQlg+2ZLDBLDITJJQIDAQAB</textarea>
                                        <input type="hidden" name="encrypted_value" id="encrypted_value">
                                    </div>
                                    <div id="cc_feedback" style="color:red; display:none;"> Dados do cartão de crédito inválidos! </div>
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
                                                <input type="text" class="form-control cep_mask" id="cc_zip" name="cc_zip" placeholder="00000-000" value="{{ old('cc_zip') ?? Auth::user()->cc_zip ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <input type="button" value="Validar" id="encrypt"/> --}}
                            </div>
                        </div>
                        
                        {{-- <hr class="mb-4"> --}}
                        {{-- <input type="hidden" name="senderHash" id="senderHash" value="">
                        <input type="hidden" name="card_token" id="card_token" value="">
                        <input type="hidden" name="installmentValue" id="installmentValue" value=""> --}}
                        {{-- <a href="/finish" class="btn_next float-right">Finalizar compra</a> --}}
                        <button type="button" onclick="submitCheckout()" class="btn btn-common sub-btn float-right" id="finalizar_comprar">Finalizar compra</button>
                        <button class="btn btn-common sub-btn float-right d-none" type="button" id="carregando_comprar" disabled>
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
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <script type="text/javascript" src="//assets.moip.com.br/v2/moip.min.js"></script>
        {{-- <script src="assets_conference/js/bootstrap-input-spinner.js"></script>
        <script src="assets_conference/js/custom-editors.js"></script> --}}
        <script>

            $(document).ready(function() { 

                $('.form-check-input').change(function(){
                    payment_form_type = $(this).val();
                    console.log(payment_form_type);
                    if(payment_form_type == 1){
                        $('#payment_form').fadeIn();
                    }else{
                        $('#payment_form').fadeOut();
                    }
                });
                
                // $('.date_mask').mask('00/00/0000', {placeholder: "__/__/____"});
                // $('.cep').mask('00000-000');
                $('.phone_with_ddd_mask').mask('(00) 0000-0000');
                $('.cpf_mask').mask('000.000.000-00', {reverse: false});
                $('.cnpj_mask').mask('00.000.000/0000-00', {reverse: false});
                $('.cep_mask').mask('00000-000');
                $('.expiration_mask').mask('00/0000');
                $('.cc_cvc_mask').mask('000');
                $('.cc_number_mask').mask('YYYY YYYY YYYY YYYY', {'translation': {
                        Y: {pattern: /[0-9]/}
                    }
                });
                $('.cc_cvc_mask').mask('YYY', {'translation': {
                        Y: {pattern: /[0-9]/}
                    }
                });

                $('#cc_name, #cc_number_cc, #cc_expiration, #cc_cvc').change(function(){

                    let cc_name = $('#cc_name').val();
                    let cc_number_cc = $('#cc_number_cc').val();
                    let cc_expiration = $('#cc_expiration').val();
                    let cc_cvc = $('#cc_cvc').val();

                    if(cc_name && cc_number_cc && cc_expiration && cc_cvc){
                        var cc = new Moip.CreditCard({
                            number  : cc_number_cc,
                            cvc     : cc_cvc,
                            expMonth: cc_expiration.split("/")[0],
                            expYear : cc_expiration.split("/")[1],
                            pubKey  : $("#public_key").val()
                        });
                        if( cc.isValid()){
                            $("#encrypted_value").val(cc.hash());
                            $('#cc_feedback').hide();
                        }
                        else{
                            $("#encrypted_value").val('');
                            $('#cc_feedback').show();
                        }
                    }
                });

                // $("#encrypt").click(function() {
                //     var cc = new Moip.CreditCard({
                //         number  : $("#cc_number_cc").val(),
                //         cvc     : $("#cc_cvc").val(),
                //         expMonth: $("#cc_expiration").val().split("/")[0],
                //         expYear : $("#cc_expiration").val().split("/")[1],
                //         pubKey  : $("#public_key").val()
                //     });
                //     console.log(cc);
                //     if( cc.isValid()){
                //         $("#encrypted_value").val(cc.hash());
                //     }
                //     else{
                //         $("#encrypted_value").val('');
                //         alert('Invalid credit card. Verify parameters: number, cvc, expiration Month, expiration Year');
                //     }
                // });

                const settings = {
                    async: true,
                    crossDomain: true,
                    url: 'https://sandbox.moip.com.br/v2/channels',
                    method: 'POST',
                    headers: {
                        accept: 'application/json',
                        Authorization: 'Basic T1EyWUM1OEhVNURTSk1KVVNES05RQVlSMDI4UU5DV1Q6OVVVRkpPRkpQUVJBM09aVTM2S0w5NkNVNVg5VVhNQllSWllWNDQ2Tw==',
                        'Content-Type': 'application/json'
                    },
                    processData: false,
                    data: '{"name":"site","description":"descricao","site":"www.site.com","redirectUri":"www.site.com"}'
                };

                $.ajax(settings).done(function (response) {
                    console.log(response);
                });

                // const settings_order = {
                //     async: true,
                //     crossDomain: true,
                //     url: 'https://sandbox.moip.com.br/v2/orders/123456/payments',
                //     method: 'POST',
                //     headers: {
                //         accept: 'application/json',
                //         Authorization: 'Basic T1EyWUM1OEhVNURTSk1KVVNES05RQVlSMDI4UU5DV1Q6OVVVRkpPRkpQUVJBM09aVTM2S0w5NkNVNVg5VVhNQllSWllWNDQ2Tw==',
                //         'Content-Type': 'application/json'
                //     },
                //     processData: false,
                //     data: '{"fundingInstrument":{"creditCard":{"holder":{"fullname":"Alan Lima","birthdate":"1987-06-24"},"number":4012001037141112,"expirationMonth":6,"expirationYear":2024,"cvc":123,"hash":"AfPwF3XnmLoWsPLIiLqN7Oc2YG2Ea6BjE3/SiY1+3FcXeDtgNRbHWXcFyCV3sO8rDKDCX5YI7LmLmXri8jkk2fnSMyR1rHbJmfG8P7VlUFmGU71juOIxiuURDt4bh5qciM/CfB6gXJIj83GgSTz2q5HPuxd/F0XG9n64AlnTyfb6nE3qJDvN2Dczs11umR+pZhKy3rSSnAyDXAYDXzvEItY7uQYZET1Qq7QVm729DOL54e5t+w1SLL7frUvWAi4pN08FgZm3JOxzU2LNGXePJWyWtUMGeYaqIOvSw39E3l63Jb9UOWUEUx4DYG+V4N/I+EBWld5grdnqhUsKKbFwLw=="},"method":"CREDIT_CARD"},"installmentCount":1}'
                // };

                // $.ajax(settings_order).done(function (response) {
                // console.log(response);
                // });

                    // const settings_account = {
                    //     async: true,
                    //     crossDomain: true,
                    //     url: 'https://sandbox.moip.com.br/v2/orders',
                    //     method: 'POST',
                    //     headers: {
                    //         accept: 'application/json',
                    //         Authorization: 'Basic T1EyWUM1OEhVNURTSk1KVVNES05RQVlSMDI4UU5DV1Q6OVVVRkpPRkpQUVJBM09aVTM2S0w5NkNVNVg5VVhNQllSWllWNDQ2Tw==',
                    //         'Content-Type': 'application/json'
                    //     },
                    //     processData: false,
                    //     data: '{"amount":{"currency":"BRL"},"items":[{"product":"ingresso1","quantity":"1","price":"100","detail":"teste do ingresso"}],"customer":{"taxDocument":{"type":"CPF","number":"02686685310"},"phone":{"countryCode":"55","areaCode":"98","number":"983446042"},"shippingAddress":{"street":"Rua Tangará. Bonavita Prime.","streetNumber":"3","city":"São José de Ribamar","state":"MA","zipCode":"65110000","district":"Araçagy","country":"BRA"},"ownId":"345","fullname":"Alan Lima","email":"alancarlosml@gmail.com","birthDate":"1987-06-24"},"ownId":"123456"}'
                    // };

                    // $.ajax(settings_account).done(function (response) {
                    // console.log(response);
                    // });

                // const settings_account = {
                //     async: true,
                //     crossDomain: true,
                //     url: 'https://sandbox.moip.com.br/v2/accounts',
                //     method: 'POST',
                //     headers: {
                //         accept: 'application/json',
                //         Authorization: 'Basic T1EyWUM1OEhVNURTSk1KVVNES05RQVlSMDI4UU5DV1Q6OVVVRkpPRkpQUVJBM09aVTM2S0w5NkNVNVg5VVhNQllSWllWNDQ2Tw==',
                //         'Content-Type': 'application/json'
                //     },
                //     processData: false,
                //     data: '{"email":{"address":"alancarlosml@gmail.com"},"person":{"taxDocument":{"type":"CPF","number":"02686685310"},"phone":{"countryCode":"55","areaCode":"98","number":"983446042"},"address":{"street":"Rua Tangará. Bonavita Prime.","streetNumber":"3","zipCode":"65110000","city":"São José de Ribamar","state":"MA","district":"Araçagy","country":"BRA"},"name":"Alan","lastName":"Lima","birthDate":"1987-06-24"},"transparentAccount":true,"type":"MERCHANT"}'
                // };

                // $.ajax(settings_account).done(function (response) {
                //     console.log(response);
                // });


                // const settings = {
                //     async: true,
                //     crossDomain: true,
                //     url: 'https://sandbox.moip.com.br/v2/accounts',
                //     method: 'POST',
                //     headers: {
                //         accept: 'application/json',
                //         Authorization: 'Basic OVVVRkpPRkpQUVJBM09aVTM2S0w5NkNVNVg5VVhNQllSWllWNDQ2Tw==',
                //         'Content-Type': 'application/json'
                //     },
                //     processData: false,
                //     data: '{"transparentAccount":true,"type":"MERCHANT"}'
                // };

                // $.ajax(settings).done(function (response) {
                //     console.log(response);
                // });

                
                // const settings = {
                //     async: true,
                //     crossDomain: true,
                //     url: 'https://sandbox.moip.com.br/v2/accounts',
                //     method: 'POST',
                //     headers: {
                //         accept: 'application/json',
                //         Authorization: 'Basic OVVVRkpPRkpQUVJBM09aVTM2S0w5NkNVNVg5VVhNQllSWllWNDQ2Tw==',
                //         'Content-Type': 'application/json'
                //     },
                //     processData: false,
                //     data: undefined
                // };

                // $.ajax(settings).done(function (response) {
                //     console.log(response);
                // });
            });

            // function validSubmition(){

            //     // let customSelect = $('.custom-select').val();

            //     var count = 0;
            //     $(".inp-number").each(function() {
            //         if($(this).val() !== '0'){
            //             count++
            //         }
            //     });

            //     if (count === 0) {

            //         $('#cupomModal').modal('show');
            //         $('#modal_txt').text('Por favor, selecione ao menos um ingresso.');
            //         $('#modal_icon').attr('src', '/img/alert.png');
            //         return false;
            //     }

            //     return true;
            // }

            

            function submitCheckout(){ 

                $('#finalizar_comprar').hide();
                $('#carregando_comprar').removeClass('d-none');
                $('#checkout_submit').submit(); 

            }
        </script>
    @endpush

</x-event-layout>