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
                            <span><b>Data:</b> {{\Carbon\Carbon::parse($eventDate->date)->format('d/m/y')}}</span><br>
                            <span><b>Local:</b> {{$event->place->name}}</span><br>
                            <span><b>Lote(s) selecionado(s):</b> </span>
                            <ul class="list-style">
                                @foreach ($array_lotes as $array_lote)
                                    <li class="ml-4" style="list-style-type: circle">{{$array_lote['quantity']}}x {{$array_lote['name']}}</li>
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
                                <div>{{$array_lote['quantity']}} @if ($array_lote['quantity'] == 1) ingresso @else ingressos @endif</div>
                                @if($array_lote['value'] == 0)
                                <div class="text-muted">Grátis</div>
                                @else
                                <div class="text-muted">@money($array_lote['value'])</div>
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
                    <form method="POST" id="checkout_submit" action="{{route('conference.thanks', $event->slug)}}">
                        @csrf
                        <div class="mb-3">
                            <div class="section-title-header text-center mb-4">
                                <h2 class="section-title wow fadeInUp animated">Dados da inscrição</h2>
                            </div>
                            @foreach($array_lotes_obj as $k => $lote_obj)
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
                                <strong class="d-block mb-3"><u>Informações do participante #{{$k+1}} ({{$lote_obj['name']}})</u></strong>
                                @foreach ($questions as $id => $question)
                                    @switch($question->option_id)
                                        @case(1)
                                            {{-- Campo texto --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <input type="text" class="form-control new_field" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required="required" @endif>
                                            </div>
                                            @break
                                        @case(2)
                                            {{-- Campo seleção --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <select class="form-control new_field" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
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
                                                <input type="text" class="form-control new_field cpf_mask" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
                                            </div>
                                            @break
                                        @case(6)
                                            {{-- Campo CNPJ --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <input type="text" class="form-control new_field cnpj_mask" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
                                            </div>
                                            @break
                                        @case(7)
                                            {{-- Campo Data --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <input type="date" class="form-control new_field date_mask" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
                                            </div>
                                            @break
                                        @case(8)
                                            {{-- Campo Telefone --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <input type="text" class="form-control new_field phone_with_ddd_mask" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
                                            </div>
                                            @break
                                        @case(9)
                                            {{-- Campo Número inteiro --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <input type="number" class="form-control new_field" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
                                            </div>
                                            @break
                                        @case(10)
                                            {{-- Campo Número decimal --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <input type="number" class="form-control new_field" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
                                            </div>
                                            @break
                                        @case(11)
                                            {{-- Campo arquivo --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <input type="file" class="form-control new_field" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
                                            </div>
                                            @break
                                        @case(12)
                                            {{-- Campo Textarea --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <textarea class="form-control new_field" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif></textarea>
                                            </div>
                                            @break
                                        @case(13)
                                            {{-- Campo Email --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <input type="email" class="form-control new_field" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
                                            </div>
                                            @break
                                        @case(14)
                                            {{-- Campo estados --}}
                                            <div class="form-group">
                                                <label for="new_field">{{$question->question}}@if($question->required == 1)* @endif</label>
                                                <select class="form-control new_field" name="newfield_{{$k+1}}_{{$question->id}}" @if($question->required) required @endif>
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
                                <hr>
                            @endforeach
                        </div>

                        <div class="mb-4 mt-5">
                            <div class="section-title-header text-center mb-4">
                                <h2 class="section-title wow fadeInUp animated">Forma de pagamento</h2>
                            </div>
                            <div class="d-flex d-block my-3">
                                <div>
                                    <input type="radio" class="form-check-input" id="creditCard" name="payment_form_check" value="1">
                                    <label class="form-check-label" for="creditCard">
                                        <i class="fa-solid fa-credit-card"></i> <br/>
                                        <strong>Cartão de Crédito</strong>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" class="form-check-input" id="boleto" name="payment_form_check" value="2">
                                    <label class="form-check-label" for="boleto">
                                        <i class="fa-solid fa-barcode"></i> <br/>
                                        <strong>Boleto</strong>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" class="form-check-input" id="pix" name="payment_form_check" value="3">
                                    <label class="form-check-label" for="pix">
                                        <i class="fa-solid fa-qrcode"></i> <br/>
                                        <strong>Pix</strong>
                                    </label>
                                </div>
                                {{-- <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_form_check" id="creditCard" value="1">
                                    <label class="form-check-label" for="creditCard"><b>Cartão de crédito</b></label>
                                  </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_form_check" id="boleto" value="2">
                                    <label class="form-check-label" for="boleto"><b>Boleto</b></label>
                                </div> --}}
                            </div>
                            
                            <div id="payment_form_cc" style="display: none;">
                                <div class="mb-3 mt-3">
                                    <p class="h4 mb-3"><strong>Dados do cartão de crédito</strong></p>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="cc_name">Nome impresso no cartão*</label>
                                            <input type="text" class="form-control" id="cc_name" name="cc_name" placeholder="" value="Comprador da Silva">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cc_number_cc">Número do cartão de crédito*</label>
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
                                            <label for="cc_expiration">Data de expiração*</label>
                                            <input type="text" class="form-control expiration_mask" id="cc_expiration" name="cc_expiration" placeholder="00/0000" value="06/2024">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cc_cvc">CVC*</label>
                                            <input type="text" class="form-control cc_cvc_mask" id="cc_cvc" name="cc_cvc" placeholder="000" value="123">
                                            <input type="hidden" id="cc-brand" name="cc-brand">
                                        </div>
                                        <textarea id="public_key" name="public_key" style="display:none;">MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl/Ui7+/93mmx9PKXcfHrAgTmaHdL3qfp39UQw590zWQGOWH9O5ERWomkv2jtX1Ydxo+hNDg9UIqRqwCmqjgsxRBFXj8dm/tg2clwlPishn/kSbs5+I70hPFUoH6gNj+6w8pCnSqfQM33MBkAe5Y0r6YetdXi8TLJVWvtW97fIdJxK+TG3etMIIdjubnJJk48Gsr21ZoXv0hg4ML12yZBcHJgiRalDqg54R96utuFXGE7snp4PKKPvAF6lgKiO1YlxPQkfg156Q4jXdcNyUITGF1VxlcaTQH36mP3vea1/nm5qD4YcY/i5SUoGXUyNg5fHIilmvQlg+2ZLDBLDITJJQIDAQAB</textarea>
                                        <input type="hidden" name="encrypted_value" id="encrypted_value">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="cc_name">Parcelas*</label>
                                            <select class="form-control" id="cc_installments" name="cc_installments"></select>
                                        </div>
                                    </div>
                                    <div id="issuerInput" class="form-group col-sm-12 d-none">
                                        <select id="cc_issuer" name="issuer" class="form-control"></select>
                                    </div>
                                    <div id="cc_feedback" style="color:red;"> </div>
                                </div>
                                <div class="mb-3">
                                    <p class="h4 mb-3"><strong>Informações do titular do cartão de crédito</strong></p>
                                    <div class="mb-3">
                                        <div class="row">
                                            {{-- <div class="col-6">
                                                <label for="cc_name_info">Nome*</label>
                                                <input type="text" class="form-control" id="cc_name_info" name="cc_name_info" placeholder="" value="{{ old('cc_name_info') ?? '' }}">
                                            </div> --}}
                                            <div class="col-8">
                                                <label for="cc_email_info">E-mail*</label>
                                                <input type="email" class="form-control" id="cc_email_info" name="cc_email_info" placeholder="" value="{{ old('cc_email_info') ?? '' }}">
                                            </div>
                                            <div class="col-4">
                                                <label for="cc_phone_info">Telefone*</label>
                                                <input type="text" class="form-control phone_with_ddd_mask" id="cc_phone_info" name="cc_phone_info" placeholder="" value="{{ old('cc_phone_info') ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="cc_identificationType">Tipo do documento*</label>
                                                <select class="form-control" id="cc_identificationType" name="cc_identificationType"></select>
                                            </div>
                                            <div class="col-4">
                                                <label for="cc_identificationNumber">Número do documento*</label>
                                                <input type="text" class="form-control cpf_mask" id="cc_identificationNumber" name="cc_identificationNumber" placeholder="" value="{{ old('cc_identificationNumber') ?? '' }}">
                                            </div>
                                            <div class="col-4">
                                                <label for="cc_date_info">Data de nascimento*</label>
                                                <input type="date" class="form-control" id="cc_date_info" name="cc_date_info" placeholder="" value="{{ old('cc_date_info') ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <p class="h4 mb-3"><strong>Endereço de cobrança</strong></p>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <label for="cc_address">Endereço*</label>
                                                <input type="text" class="form-control" id="cc_address" name="cc_address" placeholder="" value="{{ old('cc_address') ?? '' }}">
                                            </div>
                                            <div class="col-4">
                                                <label for="cc_number">Número*</label>
                                                <input type="text" class="form-control" id="cc_number" name="cc_number" placeholder="" value="{{ old('cc_number') ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <label for="cc_address2">Complemento </label>
                                                <input type="text" class="form-control" id="cc_address2" name="cc_address2" placeholder="" value="{{ old('cc_address2') ?? '' }}">
                                            </div>
                                            <div class="col-4">
                                                <label for="cc_district">Bairro* </label>
                                                <input type="text" class="form-control" id="cc_district" name="cc_district" placeholder="" value="{{ old('cc_district') ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="cc_state">Estado* </label>
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
                                                <label for="cc_city">Cidade* </label>
                                                <input type="text" class="form-control" id="cc_city" name="cc_city" placeholder="" value="{{ old('cc_city') ?? '' }}">
                                            </div>
                                            <div class="col-4">
                                                <label for="cc_zip">CEP* </label>
                                                <input type="text" class="form-control cep_mask" id="cc_zip" name="cc_zip" placeholder="00000-000" value="{{ old('cc_zip') ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <input type="button" value="Validar" id="encrypt"/> --}}
                            </div>
                            <div id="payment_form_boleto" style="display: none;">
                                <div class="mb-3">
                                    <p class="h4 mb-3"><strong>Informações do titular</strong></p>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="boleto_name_info">Nome*</label>
                                                <input type="text" class="form-control" id="boleto_name_info" name="boleto_name_info" placeholder="" value="{{ old('boleto_name_info') ?? '' }}">
                                            </div>
                                            <div class="col-6">
                                                <label for="boleto_email_info">E-mail*</label>
                                                <input type="email" class="form-control" id="boleto_email_info" name="boleto_email_info" placeholder="" value="{{ old('boleto_email_info') ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="boleto_cpf_info">CPF*</label>
                                                <input type="text" class="form-control cpf_mask" id="boleto_cpf_info" name="boleto_cpf_info" placeholder="" value="{{ old('boleto_cpf_info') ?? '' }}">
                                            </div>
                                            <div class="col-4">
                                                <label for="boleto_date_info">Data de nascimento*</label>
                                                <input type="date" class="form-control" id="boleto_date_info" name="boleto_date_info" placeholder="" value="{{ old('boleto_date_info') ?? '' }}">
                                            </div>
                                            <div class="col-4">
                                                <label for="boleto_phone_info">Telefone*</label>
                                                <input type="text" class="form-control phone_with_ddd_mask" id="boleto_phone_info" name="boleto_phone_info" placeholder="" value="{{ old('boleto_phone_info') ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <p class="h4 mb-3"><strong>Endereço de cobrança</strong></p>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-9">
                                                <label for="boleto_address">Endereço*</label>
                                                <input type="text" class="form-control" id="boleto_address" name="boleto_address" placeholder="" value="{{ old('boleto_address') ?? '' }}">
                                            </div>
                                            <div class="col-3">
                                                <label for="boleto_number">Número*</label>
                                                <input type="text" class="form-control" id="boleto_number" name="boleto_number" placeholder="" value="{{ old('boleto_number') ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <label for="boleto_address2">Complemento </label>
                                                <input type="text" class="form-control" id="boleto_address2" name="boleto_address2" placeholder="" value="{{ old('boleto_address2') ?? '' }}">
                                            </div>
                                            <div class="col-4">
                                                <label for="boleto_district">Bairro* </label>
                                                <input type="text" class="form-control" id="boleto_district" name="boleto_district" placeholder="" value="{{ old('boleto_district') ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="boleto_state">Estado* </label>
                                                <select class="form-control" id="boleto_state" name="boleto_state">
                                                    <option value="AC">Acre</option>
                                                    <option value="AL">Alagoas</option>
                                                    <option value="AP">Amapá</option>
                                                    <option value="AM">Amazonas</option>
                                                    <option value="BA">Bahia</option>
                                                    <option value="CE">Ceará</option>
                                                    <option value="DF">Distrito Federal</option>
                                                    <option value="ES">Espírito Santo</option>
                                                    <option value="GO">Goiás</option>
                                                    <option value="MA">Maranhão</option>
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
                                                {{-- <input type="text" class="form-control" id="boleto_state" name="boleto_state" placeholder="" value="{{ old('boleto_state') ?? Auth::user()->boleto_state ?? '' }}"> --}}
                                            </div>
                                            <div class="col-4">
                                                <label for="boleto_city">Cidade* </label>
                                                <input type="text" class="form-control" id="boleto_city" name="boleto_city" placeholder="" value="{{ old('boleto_city') ?? '' }}" required>
                                            </div>
                                            <div class="col-4">
                                                <label for="boleto_zip">CEP* </label>
                                                <input type="text" class="form-control cep_mask" id="boleto_zip" name="boleto_zip" placeholder="00000-000" value="{{ old('boleto_zip') ?? '' }}" required>
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
                        {{-- <div id="validation-error-messages"></div> --}}
                        <button type="button" onclick="submitCheckout()" class="btn btn-common sub-btn float-right" id="finalizar_comprar">Finalizar compra</button>
                        <button class="btn btn-common sub-btn float-right d-none" type="button" id="carregando_comprar" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Enviando...
                          </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- <form id="form-checkout" action="/process_payment" method="POST">
            <div id="form-checkout__cardNumber" class="container_form_submit"></div>
            <div id="form-checkout__expirationDate" class="container_form_submit"></div>
            <div id="form-checkout__securityCode" class="container_form_submit"></div>
            <input type="text" id="form-checkout__cardholderName" placeholder="Titular do cartão" />
            <select id="form-checkout__issuer" name="issuer">
              <option value="" disabled selected>Banco emissor</option>
            </select>
            <select id="form-checkout__installments" name="installments">
              <option value="" disabled selected>Parcelas</option>
            </select>
            <select id="form-checkout__identificationType" name="identificationType">
              <option value="" disabled selected>Tipo de documento</option>
            </select>
            <input type="text" id="form-checkout__identificationNumber" name="identificationNumber" placeholder="Número do documento" />
            <input type="email" id="form-checkout__email" name="email" placeholder="E-mail" />
        
            <input id="token" name="token" type="hidden">
            <input id="paymentMethodId" name="paymentMethodId" type="hidden">
            <input id="transactionAmount" name="transactionAmount" type="hidden" value="100">
            <input id="description" name="description" type="hidden" value="Nome do Produto">
        
            <button type="submit" id="form-checkout__submit">Pagar</button>
          </form> --}}
    
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
        {{-- <script type="text/javascript" src="//assets.moip.com.br/v2/moip.min.js"></script> --}}
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
        {{-- <script src="assets_conference/js/bootstrap-input-spinner.js"></script>
        <script src="assets_conference/js/custom-editors.js"></script> --}}
        <script>

            $(document).ready(function() { 

                $('#checkout_submit').validate({
                    errorClass: "error fail-alert d-block"
                });

                $('.form-check-input').change(function(){
                    payment_form_type = $(this).val();
                    if(payment_form_type == 1){
                        $('#payment_form_cc').fadeIn();
                        $('#payment_form_boleto').fadeOut();
                        $('#payment_form_pix').fadeOut();
                        $("#cc_name_info, #cc_email_info, #cc_identificationNumber, #cc_date_info, #cc_phone_info, #cc_address, #cc_number, #cc_district, #cc_state, #cc_city, #cc_zip").prop('required',true);
                        $("#boleto_name_info, #boleto_email_info, #boleto_cpf_info, #boleto_date_info, #boleto_phone_info, #boleto_address, #boleto_number, #boleto_district, #boleto_state, #boleto_city, #boleto_zip").prop('required',false);
                    }else if(payment_form_type == 2){
                        $('#payment_form_boleto').fadeIn();
                        $('#payment_form_cc').fadeOut();
                        $('#payment_form_pix').fadeOut();
                        $("#boleto_name_info, #boleto_email_info, #boleto_cpf_info, #boleto_date_info, #boleto_phone_info, #boleto_address, #boleto_number, #boleto_district, #boleto_state, #boleto_city, #boleto_zip").prop('required',true);
                        $("#cc_name_info, #cc_email_info, #cc_identificationNumber, #cc_date_info, #cc_phone_info, #cc_address, #cc_number, #cc_district, #cc_state, #cc_city, #cc_zip").prop('required',false);
                    }else if(payment_form_type == 3){
                        $('#payment_form_pix').fadeIn();
                        $('#payment_form_cc').fadeOut();
                        $('#payment_form_boleto').fadeOut();
                        $("#pix_name_info, #pix_email_info, #pix_cpf_info, #pix_date_info, #pix_phone_info, #pix_address, #pix_number, #pix_district, #pix_state, #pix_city, #pix_zip").prop('required',true);
                        $("#cc_name_info, #cc_email_info, #cc_identificationNumber, #cc_date_info, #cc_phone_info, #cc_address, #cc_number, #cc_district, #cc_state, #cc_city, #cc_zip").prop('required',false);
                        $("#boleto_name_info, #boleto_email_info, #boleto_cpf_info, #boleto_date_info, #boleto_phone_info, #boleto_address, #boleto_number, #boleto_district, #boleto_state, #boleto_city, #boleto_zip").prop('required',false);
                    }
                });

                $('#finalizar_comprar').show();
                $('#carregando_comprar').addClass('d-none');
                
                // $('.date_mask').mask('00/00/0000', {placeholder: "__/__/____"});
                // $('.cep').mask('00000-000');
                $('.phone_with_ddd_mask').mask('(00) 00000-0000');
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

                const mp = new MercadoPago('{{env('MERCADO_PAGO_PUBLIC_KEY', '')}}');

                const productCost = '100';
                const productDescription = 'Camisa';
                const payButton = document.getElementById("finalizar_comprar");
                const validationErrorMessages = document.getElementById('cc_feedback');

                const form = {
                    id: "checkout_submit",
                    cardholderName: {
                        id: "cc_name",
                    },
                    cardholderEmail: {
                        id: "cc_email_info",
                    },
                    cardNumber: {
                        id: "cc_number_cc",
                    },
                    expirationDate: {
                        id: "cc_expiration",
                    },
                    securityCode: {
                        id: "cc_cvc",
                    },
                    installments: {
                        id: "cc_installments",
                    },
                    identificationType: {
                        id: "cc_identificationType",
                    },
                    identificationNumber: {
                        id: "cc_identificationNumber",
                    },
                    issuer: {
                        id: "cc_issuer",
                    },
                };

                const cardForm = mp.cardForm({
                    amount: productCost,
                    iframe: true,
                    form,
                    callbacks: {
                        onFormMounted: error => {
                            if (error)
                                return console.warn("Form Mounted handling error: ", error);
                            console.log("Form mounted");
                        },
                        onSubmit: event => {
                            event.preventDefault();
                            document.getElementById("loading-message").style.display = "block";

                            const {
                                paymentMethodId,
                                issuerId,
                                cardholderEmail: email,
                                amount,
                                token,
                                installments,
                                identificationNumber,
                                identificationType,
                            } = cardForm.getCardFormData();

                            fetch("/process_payment", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify({
                                    token,
                                    issuerId,
                                    paymentMethodId,
                                    transactionAmount: Number(amount),
                                    installments: Number(installments),
                                    description: productDescription,
                                    payer: {
                                        email,
                                        identification: {
                                            type: identificationType,
                                            number: identificationNumber,
                                        },
                                    },
                                }),
                            })
                            .then(response => {
                                return response.json();
                            })
                            .then(result => {
                                if(!result.hasOwnProperty("error_message")) {
                                    document.getElementById("success-response").style.display = "block";
                                    document.getElementById("payment-id").innerText = result.id;
                                    document.getElementById("payment-status").innerText = result.status;
                                    document.getElementById("payment-detail").innerText = result.detail;
                                } else {
                                    document.getElementById("error-message").textContent = result.error_message;
                                    document.getElementById("fail-response").style.display = "block";
                                }
                                
                                $('.container__payment').fadeOut(500);
                                setTimeout(() => { $('.container__result').show(500).fadeIn(); }, 500);
                            })
                            .catch(error => {
                                alert("Unexpected error\n"+JSON.stringify(error));
                            });
                        },
                        onFetching: (resource) => {
                            console.log("Fetching resource: ", resource);
                            payButton.setAttribute('disabled', true);
                            return () => {
                                payButton.removeAttribute("disabled");
                            };
                        },
                        onCardTokenReceived: (errorData, token) => {
                            if (errorData && errorData.error.fieldErrors.length !== 0) {
                                errorData.error.fieldErrors.forEach(errorMessage => {
                                    alert(errorMessage);
                                });
                            }

                            return token;
                        },
                        onValidityChange: (error, field) => {
                            const input = document.getElementById(form[field].id);
                            removeFieldErrorMessages(input, validationErrorMessages);
                            addFieldErrorMessages(input, validationErrorMessages, error);
                            enableOrDisablePayButton(validationErrorMessages, payButton);
                        }
                    },
                });

                function removeFieldErrorMessages(input, validationErrorMessages) {
                    Array.from(validationErrorMessages.children).forEach(child => {
                        const shouldRemoveChild = child.id.includes(input.id);
                        if (shouldRemoveChild) {
                            validationErrorMessages.removeChild(child);
                        }
                    });
                }

                function addFieldErrorMessages(input, validationErrorMessages, error) {
                    if (error) {
                        input.classList.add('validation-error');
                        error.forEach((e, index) => {
                            const p = document.createElement('p');
                            p.id = `${input.id}-${index}`;
                            p.innerText = e.message;
                            validationErrorMessages.appendChild(p);
                        });
                    } else {
                        input.classList.remove('validation-error');
                    }
                }

                function enableOrDisablePayButton(validationErrorMessages, payButton) {
                    if (validationErrorMessages.children.length > 0) {
                        payButton.setAttribute('disabled', true);
                    } else {
                        payButton.removeAttribute('disabled');
                    }
                }
                





                // $('#cc_name, #cc_number_cc, #cc_expiration, #cc_cvc').change(function(){

                //     let cc_name = $('#cc_name').val();
                //     let cc_number_cc = $('#cc_number_cc').val();
                //     let cc_expiration = $('#cc_expiration').val();
                //     let cc_cvc = $('#cc_cvc').val();

                //     if(cc_name && cc_number_cc && cc_expiration && cc_cvc){
                        // var cc = new Moip.CreditCard({
                        //     number  : cc_number_cc,
                        //     cvc     : cc_cvc,
                        //     expMonth: cc_expiration.split("/")[0],
                        //     expYear : cc_expiration.split("/")[1],
                        //     pubKey  : $("#public_key").val()
                        // });
                        // if( cc.isValid()){
                        //     $("#encrypted_value").val(cc.hash());
                        //     $('#cc_feedback').hide();
                        // }
                        // else{
                        //     $("#encrypted_value").val('');
                        //     $('#cc_feedback').show();
                        // }
                    // }
                // });

                

                
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

                // const settings = {
                //     async: true,
                //     crossDomain: true,
                //     url: 'https://sandbox.moip.com.br/v2/channels',
                //     method: 'POST',
                //     headers: {
                //         accept: 'application/json',
                //         Authorization: 'Basic T1EyWUM1OEhVNURTSk1KVVNES05RQVlSMDI4UU5DV1Q6OVVVRkpPRkpQUVJBM09aVTM2S0w5NkNVNVg5VVhNQllSWllWNDQ2Tw==',
                //         'Content-Type': 'application/json'
                //     },
                //     processData: false,
                //     data: '{"name":"site","description":"descricao","site":"www.site.com","redirectUri":"www.site.com"}'
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
                $('.fail-alert').html('Esse campo é obrigatório!');
            }
        </script>
    @endpush

</x-event-layout>