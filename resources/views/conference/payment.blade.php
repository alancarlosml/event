<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finalizar Compra - {{ $event->name }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Theme CSS -->
    @if ($event->theme == 'red')
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

    <style>
        .payment-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .payment-method-tab {
            cursor: pointer;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px 10px 0 0;
            background: #f8f9fa;
            transition: all 0.3s;
            text-align: center;
        }
        
        .payment-method-tab.active {
            background: #fff;
            border-bottom: none;
            border-color: #007bff;
            color: #007bff;
            font-weight: 600;
        }
        
        .payment-method-tab:hover:not(.active) {
            background: #e9ecef;
        }
        
        .payment-form-container {
            background: #fff;
            border: 2px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 10px 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .mp-form-control {
            height: 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .mp-form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
            outline: none;
        }
        
        .mp-form-control.error {
            border-color: #dc3545;
        }
        
        .mp-form-control.valid {
            border-color: #28a745;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .error-message.show {
            display: block;
        }
        
        .btn-payment {
            height: 50px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-payment:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .pix-qr-container {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .pix-qr-code {
            max-width: 300px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .pix-qr-code img {
            width: 100%;
            height: auto;
        }
        
        .boleto-link-container {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-overlay.show {
            display: flex;
        }
        
        .loading-spinner {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .card-icon {
            width: 40px;
            height: 25px;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        .installments-select {
            height: 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 16px;
        }
        
        @media (max-width: 768px) {
            .payment-method-tab {
                padding: 12px 15px;
                font-size: 14px;
            }
            
            .payment-form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="payment-container">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Finalizar Pagamento
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Informações do Evento -->
                    <div class="mb-4">
                        <h5>{{ $event->name }}</h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar me-2"></i>
                            {{ \Carbon\Carbon::parse($event->min_event_dates())->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($event->min_event_time())->format('H:i') }}h
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $event->place->name }}, {{ optional($event->place->get_city)->name }}-{{ optional($event->place->get_city)->uf }}
                        </p>
                    </div>

                    <hr>

                    <!-- Resumo do Pedido -->
                    <div class="mb-4">
                        <h6 class="mb-3">Resumo do Pedido</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total:</span>
                            <strong class="fs-5">R$ {{ number_format($total, 2, ',', '.') }}</strong>
                        </div>
                    </div>

                    <hr>

                    <!-- Container de Erro -->
                    <div id="error_container" class="alert alert-danger d-none" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span id="error_message"></span>
                    </div>

                    <!-- Container de Sucesso -->
                    <div id="success_container" class="success-message d-none">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="success_message"></span>
                    </div>

                    <!-- Tabs de Métodos de Pagamento -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="payment-method-tab active" data-method="credit_card">
                                <i class="fas fa-credit-card me-2"></i>
                                Cartão de Crédito
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="payment-method-tab" data-method="bank_transfer">
                                <i class="fas fa-qrcode me-2"></i>
                                PIX
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="payment-method-tab" data-method="ticket" id="ticket_tab" style="display: none;">
                                <i class="fas fa-barcode me-2"></i>
                                Boleto
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Cartão de Crédito -->
                    <div id="credit_card_form" class="payment-form-container">
                        <form id="card_payment_form">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label">Número do Cartão</label>
                                    <div id="cardNumber" class="mp-form-control"></div>
                                    <div class="error-message" id="cardNumberError"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nome no Cartão</label>
                                    <input type="text" id="cardholderName" class="form-control mp-form-control" placeholder="Nome como está no cartão" required>
                                    <div class="error-message" id="cardholderNameError"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Validade</label>
                                    <div id="expirationDate" class="mp-form-control"></div>
                                    <div class="error-message" id="expirationDateError"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">CVV</label>
                                    <div id="securityCode" class="mp-form-control"></div>
                                    <div class="error-message" id="securityCodeError"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">CPF do Titular</label>
                                    <input type="text" id="cardholderCPF" class="form-control mp-form-control" placeholder="000.000.000-00" maxlength="14" required>
                                    <div class="error-message" id="cardholderCPFError"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Parcelas</label>
                                    <select id="installments" class="form-control installments-select" required>
                                        <option value="">Carregando...</option>
                                    </select>
                                    <div class="error-message" id="installmentsError"></div>
                                </div>
                            </div>

                            <div class="info-box">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>Seus dados estão protegidos e criptografados pelo Mercado Pago</small>
                            </div>

                            <button type="submit" id="submit_card_payment" class="btn btn-primary btn-payment w-100">
                                <i class="fas fa-lock me-2"></i>
                                Finalizar Pagamento
                            </button>
                        </form>
                    </div>

                    <!-- Formulário de PIX -->
                    <div id="bank_transfer_form" class="payment-form-container" style="display: none;">
                        <div class="info-box">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Pagamento via PIX</strong>
                            <p class="mb-0 mt-2">O pagamento é processado instantaneamente. Após a confirmação, você receberá o QR Code para pagamento.</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CPF para Pagamento</label>
                            <input type="text" id="pix_cpf" class="form-control mp-form-control" placeholder="000.000.000-00" maxlength="14" value="{{ Auth::check() && Auth::user()->cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', Auth::user()->cpf) : '' }}">
                            <div class="error-message" id="pix_cpfError"></div>
                            <small class="text-muted">CPF obrigatório para pagamento via PIX</small>
                        </div>

                        <button type="button" id="submit_pix_payment" class="btn btn-success btn-payment w-100">
                            <i class="fas fa-qrcode me-2"></i>
                            Gerar QR Code PIX
                        </button>

                        <div id="pix_result" style="display: none;"></div>
                    </div>

                    <!-- Formulário de Boleto -->
                    <div id="ticket_form" class="payment-form-container" style="display: none;">
                        <div class="info-box">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Pagamento via Boleto</strong>
                            <p class="mb-0 mt-2">O boleto será gerado após o preenchimento dos dados. O prazo de vencimento é de 3 dias úteis.</p>
                        </div>

                        <form id="boleto_payment_form">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">CPF</label>
                                    <input type="text" id="boleto_cpf" class="form-control mp-form-control" placeholder="000.000.000-00" maxlength="14" required>
                                    <div class="error-message" id="boleto_cpfError"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">E-mail</label>
                                    <input type="email" id="boleto_email" class="form-control mp-form-control" value="{{ Auth::check() ? Auth::user()->email : '' }}" required>
                                    <div class="error-message" id="boleto_emailError"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nome Completo</label>
                                    <input type="text" id="boleto_first_name" class="form-control mp-form-control" value="{{ Auth::check() ? explode(' ', Auth::user()->name)[0] : '' }}" required>
                                    <div class="error-message" id="boleto_first_nameError"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sobrenome</label>
                                    @php
                                        $lastName = '';
                                        if (Auth::check()) {
                                            $nameParts = explode(' ', Auth::user()->name);
                                            $lastName = count($nameParts) > 1 ? end($nameParts) : '';
                                        }
                                    @endphp
                                    <input type="text" id="boleto_last_name" class="form-control mp-form-control" value="{{ $lastName }}" required>
                                    <div class="error-message" id="boleto_last_nameError"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">CEP</label>
                                    <input type="text" id="boleto_zip_code" class="form-control mp-form-control" placeholder="00000-000" maxlength="9" required>
                                    <div class="error-message" id="boleto_zip_codeError"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Rua</label>
                                    <input type="text" id="boleto_street_name" class="form-control mp-form-control" required>
                                    <div class="error-message" id="boleto_street_nameError"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Número</label>
                                    <input type="text" id="boleto_street_number" class="form-control mp-form-control" required>
                                    <div class="error-message" id="boleto_street_numberError"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" id="boleto_neighborhood" class="form-control mp-form-control" required>
                                    <div class="error-message" id="boleto_neighborhoodError"></div>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Cidade</label>
                                    <input type="text" id="boleto_city" class="form-control mp-form-control" required>
                                    <div class="error-message" id="boleto_cityError"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">UF</label>
                                    <input type="text" id="boleto_federal_unit" class="form-control mp-form-control" placeholder="SP" maxlength="2" required>
                                    <div class="error-message" id="boleto_federal_unitError"></div>
                                </div>
                            </div>

                            <button type="submit" id="submit_boleto_payment" class="btn btn-warning btn-payment w-100">
                                <i class="fas fa-barcode me-2"></i>
                                Gerar Boleto
                            </button>
                        </form>

                        <div id="boleto_result" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <!-- Segurança -->
            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="fas fa-shield-alt me-2"></i>
                    Pagamento 100% seguro processado pelo Mercado Pago
                </p>
                <img src="{{ asset('assets_conference/imgs/mercado-pago-logo.png') }}" alt="Mercado Pago" height="30px" class="mt-2">
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading_overlay">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Processando...</span>
            </div>
            <p class="mt-3 mb-0">Processando pagamento...</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        $(document).ready(function() {
            const publicKey = "{{ env('MERCADO_PAGO_PUBLIC_KEY') }}";
            if (!publicKey) {
                showError('Configuração de pagamento inválida. Entre em contato com o suporte.');
                return;
            }

            const mp = new MercadoPago(publicKey, { locale: 'pt-BR' });
            let cardToken = null;
            let paymentMethodId = null;
            let issuerId = null;
            let installments = [];

            // Verificar se boleto está disponível
            let maxDate = "{{ $event->max_event_dates() }} 00:00:00";
            let maxDateObj = new Date(maxDate);
            let now = new Date();
            now.setDate(now.getDate() + 3);

            if (now < maxDateObj) {
                $('#ticket_tab').show();
            }

            // Máscaras de CPF
            function maskCPF(input) {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length <= 11) {
                        value = value.replace(/(\d{3})(\d)/, '$1.$2');
                        value = value.replace(/(\d{3})(\d)/, '$1.$2');
                        value = value.replace(/(\d{3})(\d{2})$/, '$1-$2');
                        e.target.value = value;
                    }
                });
            }

            function maskCEP(input) {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length <= 8) {
                        value = value.replace(/(\d{5})(\d)/, '$1-$2');
                        e.target.value = value;
                    }
                });
            }

            // Aplicar máscaras
            maskCPF(document.getElementById('cardholderCPF'));
            maskCPF(document.getElementById('pix_cpf'));
            maskCPF(document.getElementById('boleto_cpf'));
            maskCEP(document.getElementById('boleto_zip_code'));

            // Tabs de métodos de pagamento
            $('.payment-method-tab').on('click', function() {
                const method = $(this).data('method');
                $('.payment-method-tab').removeClass('active');
                $(this).addClass('active');
                
                $('.payment-form-container').hide();
                $(`#${method}_form`).show();
                $('#error_container').addClass('d-none');
            });

            // Inicializar campos do Mercado Pago
            const cardNumber = mp.fields.create('cardNumber', {
                placeholder: 'Número do cartão'
            }).mount('cardNumber');

            const expirationDate = mp.fields.create('expirationDate', {
                placeholder: 'MM/AA'
            }).mount('expirationDate');

            const securityCode = mp.fields.create('securityCode', {
                placeholder: 'CVV'
            }).mount('securityCode');

            // Validação e detecção de bandeira
            cardNumber.on('binChange', function(data) {
                paymentMethodId = data.payment_method_id;
                issuerId = data.issuer ? data.issuer.id : null;
                
                // Carregar parcelas
                loadInstallments(data.bin);
            });

            cardNumber.on('change', function(event) {
                if (event.status === 'invalid') {
                    $('#cardNumber').addClass('error');
                    $('#cardNumberError').text('Número do cartão inválido').addClass('show');
                } else {
                    $('#cardNumber').removeClass('error').addClass('valid');
                    $('#cardNumberError').removeClass('show');
                }
            });

            expirationDate.on('change', function(event) {
                if (event.status === 'invalid') {
                    $('#expirationDate').addClass('error');
                    $('#expirationDateError').text('Data inválida').addClass('show');
                } else {
                    $('#expirationDate').removeClass('error').addClass('valid');
                    $('#expirationDateError').removeClass('show');
                }
            });

            securityCode.on('change', function(event) {
                if (event.status === 'invalid') {
                    $('#securityCode').addClass('error');
                    $('#securityCodeError').text('CVV inválido').addClass('show');
                } else {
                    $('#securityCode').removeClass('error').addClass('valid');
                    $('#securityCodeError').removeClass('show');
                }
            });

            // Carregar parcelas
            function loadInstallments(bin) {
                if (!bin || bin.length < 6) return;

                mp.getInstallments({
                    amount: {{ $total }},
                    bin: bin,
                    locale: 'pt-BR'
                }, function(status, response) {
                    if (status === 200) {
                        installments = response[0].payer_costs;
                        const select = $('#installments');
                        select.empty();
                        
                        installments.forEach(function(installment) {
                            const option = $('<option></option>')
                                .attr('value', installment.installments)
                                .text(`${installment.installments}x de R$ ${installment.installment_amount.toFixed(2).replace('.', ',')} ${installment.installments === 1 ? '(sem juros)' : '(total: R$ ' + installment.total_amount.toFixed(2).replace('.', ',') + ')'}`);
                            select.append(option);
                        });
                    }
                });
            }

            // Submissão do formulário de cartão
            $('#card_payment_form').on('submit', function(e) {
                e.preventDefault();
                
                // Validar campos
                let isValid = true;
                const cardholderName = $('#cardholderName').val().trim();
                const cardholderCPF = $('#cardholderCPF').val().replace(/\D/g, '');
                const installmentsValue = $('#installments').val();

                if (!cardholderName) {
                    $('#cardholderNameError').text('Nome é obrigatório').addClass('show');
                    isValid = false;
                } else {
                    $('#cardholderNameError').removeClass('show');
                }

                if (cardholderCPF.length !== 11) {
                    $('#cardholderCPFError').text('CPF inválido').addClass('show');
                    isValid = false;
                } else {
                    $('#cardholderCPFError').removeClass('show');
                }

                if (!installmentsValue) {
                    $('#installmentsError').text('Selecione o número de parcelas').addClass('show');
                    isValid = false;
                } else {
                    $('#installmentsError').removeClass('show');
                }

                if (!isValid) return;

                // Mostrar loading
                showLoading();
                $('#submit_card_payment').prop('disabled', true);

                // Criar token do cartão
                mp.fields.createCardToken({
                    cardNumber: cardNumber,
                    cardholderName: cardholderName,
                    cardExpirationMonth: expirationDate.getExpirationMonth(),
                    cardExpirationYear: expirationDate.getExpirationYear(),
                    securityCode: securityCode,
                    identificationType: 'CPF',
                    identificationNumber: cardholderCPF
                }, function(status, response) {
                    if (status === 200) {
                        cardToken = response.id;
                        submitCardPayment(response.id);
                    } else {
                        hideLoading();
                        $('#submit_card_payment').prop('disabled', false);
                        showError('Erro ao processar cartão: ' + (response.message || 'Tente novamente'));
                    }
                });
            });

            // Enviar pagamento de cartão
            function submitCardPayment(token) {
                const formData = {
                    paymentType: 'credit_card',
                    formData: {
                        token: token,
                        installments: parseInt($('#installments').val()),
                        payment_method_id: paymentMethodId,
                        payer: {
                            email: "{{ Auth::check() ? Auth::user()->email : '' }}",
                            identification: {
                                type: 'CPF',
                                number: $('#cardholderCPF').val().replace(/\D/g, '')
                            }
                        }
                    }
                };

                // Adicionar issuer_id apenas se disponível
                if (issuerId) {
                    formData.formData.issuer_id = issuerId;
                }

                sendPayment(formData);
            }

            // Submissão do PIX
            $('#submit_pix_payment').on('click', function() {
                const cpf = $('#pix_cpf').val().replace(/\D/g, '');
                
                if (cpf.length !== 11) {
                    $('#pix_cpfError').text('CPF inválido').addClass('show');
                    return;
                }

                $('#pix_cpfError').removeClass('show');
                showLoading();
                $(this).prop('disabled', true);

                const formData = {
                    paymentType: 'bank_transfer',
                    formData: {
                        payment_method_id: 'pix',
                        payer: {
                            email: "{{ Auth::check() ? Auth::user()->email : '' }}",
                            identification: {
                                type: 'CPF',
                                number: cpf
                            }
                        }
                    }
                };

                sendPayment(formData);
            });

            // Submissão do Boleto
            $('#boleto_payment_form').on('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    paymentType: 'ticket',
                    formData: {
                        payment_method_id: 'bolbradesco',
                        payer: {
                            email: $('#boleto_email').val(),
                            first_name: $('#boleto_first_name').val(),
                            last_name: $('#boleto_last_name').val(),
                            identification: {
                                type: 'CPF',
                                number: $('#boleto_cpf').val().replace(/\D/g, '')
                            },
                            address: {
                                zip_code: $('#boleto_zip_code').val().replace(/\D/g, ''),
                                street_name: $('#boleto_street_name').val(),
                                street_number: $('#boleto_street_number').val(),
                                neighborhood: $('#boleto_neighborhood').val(),
                                city: $('#boleto_city').val(),
                                federal_unit: $('#boleto_federal_unit').val().toUpperCase()
                            }
                        }
                    }
                };

                showLoading();
                $('#submit_boleto_payment').prop('disabled', true);
                sendPayment(formData);
            });

            // Função para enviar pagamento
            function sendPayment(formData) {
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                
                fetch("{{ route('conference.thanks', $event->slug) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(result => {
                    hideLoading();
                    handlePaymentResponse(result, formData.paymentType);
                })
                .catch(error => {
                    hideLoading();
                    const errorMessage = error.error || error.message || 'Erro ao processar pagamento. Tente novamente.';
                    showError(errorMessage);
                    
                    // Reabilitar botões
                    $('#submit_card_payment, #submit_pix_payment, #submit_boleto_payment').prop('disabled', false);
                });
            }

            // Tratar resposta do pagamento
            function handlePaymentResponse(result, paymentType) {
                if (result.status === 'approved') {
                    showSuccess('Pagamento aprovado! Redirecionando...');
                    setTimeout(() => {
                        window.location.href = "{{ route('event_home.my_registrations') }}";
                    }, 2000);
                } else if (result.status === 'pending' || result.status === 'in_process') {
                    if (paymentType === 'bank_transfer' && result.pix) {
                        // Exibir QR Code PIX
                        displayPixDetails(result.pix);
                    } else if (paymentType === 'ticket' && result.boleto) {
                        // Exibir dados do boleto
                        displayBoletoDetails(result.boleto);
                    } else {
                        showSuccess('Pagamento pendente. Aguardando confirmação...');
                    }
                } else {
                    showError('Pagamento não aprovado: ' + (result.detail || 'Tente novamente'));
                }
            }

            // Exibir detalhes do PIX
            function displayPixDetails(pixData) {
                let qrCodeHtml = '';
                if (pixData.qr_code_base64) {
                    qrCodeHtml = `<img src="data:image/png;base64,${pixData.qr_code_base64}" alt="QR Code PIX" class="img-fluid">`;
                } else if (pixData.qr_code) {
                    qrCodeHtml = `<div class="text-center"><p class="mb-2"><strong>Copie o código PIX:</strong></p><p class="bg-white p-3 rounded border" style="word-break: break-all; font-family: monospace; font-size: 12px;">${pixData.qr_code}</p><button class="btn btn-sm btn-outline-primary mt-2" onclick="navigator.clipboard.writeText('${pixData.qr_code}').then(() => alert('Código copiado!'))"><i class="fas fa-copy me-1"></i> Copiar</button></div>`;
                }

                const expirationDate = pixData.expiration_date ? new Date(pixData.expiration_date).toLocaleString('pt-BR') : '';

                $('#pix_result').html(`
                    <div class="pix-qr-container">
                        <h5 class="mb-3"><i class="fas fa-qrcode me-2"></i>QR Code PIX Gerado!</h5>
                        <div class="pix-qr-code">
                            ${qrCodeHtml}
                        </div>
                        ${pixData.ticket_url ? `<p class="mt-3"><a href="${pixData.ticket_url}" target="_blank" class="btn btn-primary"><i class="fas fa-external-link-alt me-2"></i>Abrir PIX Copia e Cola</a></p>` : ''}
                        ${expirationDate ? `<p class="text-muted mt-2"><small>Válido até: ${expirationDate}</small></p>` : ''}
                        <div class="alert alert-info mt-3" id="pix_status_alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Aguardando pagamento...</strong>
                            <p class="mb-0 mt-2">Estamos verificando seu pagamento automaticamente. Você será redirecionado assim que confirmarmos o pagamento.</p>
                            <div class="spinner-border spinner-border-sm mt-2" role="status">
                                <span class="visually-hidden">Verificando...</span>
                            </div>
                        </div>
                    </div>
                `).show();
                showSuccess('QR Code PIX gerado com sucesso!');
                
                // CORREÇÃO CRÍTICA: Iniciar polling para verificar status do pagamento
                const orderId = {{ $request->session()->get('order_id', 0) }};
                if (orderId) {
                    startPaymentStatusPolling(orderId);
                }
            }
            
            // Função para fazer polling do status do pagamento
            function startPaymentStatusPolling(orderId) {
                let attempts = 0;
                const maxAttempts = 120; // 10 minutos (120 x 5 segundos)
                
                const checkInterval = setInterval(() => {
                    attempts++;
                    
                    fetch(`{{ url('/check-payment-status') }}/${orderId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro ao verificar status');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Payment status:', data);
                            
                            if (data.approved) {
                                clearInterval(checkInterval);
                                
                                // Atualizar mensagem
                                $('#pix_status_alert').removeClass('alert-info').addClass('alert-success').html(`
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Pagamento Confirmado!</strong>
                                    <p class="mb-0 mt-2">Seu pagamento PIX foi confirmado. Redirecionando...</p>
                                `);
                                
                                showSuccess('✅ Pagamento PIX confirmado!');
                                
                                // Redirecionar após 2 segundos
                                setTimeout(() => {
                                    window.location.href = '{{ route("event_home.my_registrations") }}';
                                }, 2000);
                            } else if (data.status === 'rejected' || data.status === 'cancelled') {
                                clearInterval(checkInterval);
                                
                                $('#pix_status_alert').removeClass('alert-info').addClass('alert-danger').html(`
                                    <i class="fas fa-times-circle me-2"></i>
                                    <strong>Pagamento não aprovado</strong>
                                    <p class="mb-0 mt-2">O pagamento foi ${data.status === 'rejected' ? 'rejeitado' : 'cancelado'}. Entre em contato com o suporte.</p>
                                `);
                            }
                            
                            // Parar após máximo de tentativas
                            if (attempts >= maxAttempts) {
                                clearInterval(checkInterval);
                                $('#pix_status_alert').removeClass('alert-info').addClass('alert-warning').html(`
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Verificação automática expirou</strong>
                                    <p class="mb-0 mt-2">Ainda não detectamos seu pagamento. Verifique seu email ou atualize esta página.</p>
                                `);
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao verificar status:', error);
                            // Não parar o polling por erro temporário
                        });
                }, 5000); // Verificar a cada 5 segundos
            }

            // Exibir detalhes do Boleto
            function displayBoletoDetails(boletoData) {
                const expirationDate = boletoData.expiration_date ? new Date(boletoData.expiration_date).toLocaleString('pt-BR') : '';

                let boletoHtml = `
                    <div class="boleto-link-container">
                        <h5 class="mb-3"><i class="fas fa-barcode me-2"></i>Boleto Gerado!</h5>
                        ${boletoData.href ? `
                            <a href="${boletoData.href}" target="_blank" class="btn btn-warning btn-lg mb-3">
                                <i class="fas fa-file-pdf me-2"></i>Visualizar/Imprimir Boleto
                            </a>
                        ` : ''}
                        ${boletoData.line_code ? `
                            <div class="mb-3">
                                <p class="mb-2"><strong>Código de Barras:</strong></p>
                                <p class="bg-white p-3 rounded border" style="word-break: break-all; font-family: monospace; font-size: 14px;">${boletoData.line_code}</p>
                                <button class="btn btn-sm btn-outline-primary mt-2" onclick="navigator.clipboard.writeText('${boletoData.line_code}').then(() => alert('Código copiado!'))">
                                    <i class="fas fa-copy me-1"></i> Copiar Código
                                </button>
                            </div>
                        ` : ''}
                        ${expirationDate ? `<p class="text-muted"><small>Vencimento: ${expirationDate}</small></p>` : ''}
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Atenção:</strong> O boleto também foi enviado para seu e-mail. Pague até a data de vencimento para garantir sua inscrição.
                        </div>
                    </div>
                `;

                $('#boleto_result').html(boletoHtml).show();
                showSuccess('Boleto gerado com sucesso!');
            }

            // Funções auxiliares
            function showError(message) {
                $('#error_message').text(message);
                $('#error_container').removeClass('d-none');
                $('#success_container').addClass('d-none');
                $('html, body').animate({ scrollTop: $('#error_container').offset().top - 100 }, 500);
            }

            function showSuccess(message) {
                $('#success_message').text(message);
                $('#success_container').removeClass('d-none');
                $('#error_container').addClass('d-none');
                $('html, body').animate({ scrollTop: $('#success_container').offset().top - 100 }, 500);
            }

            function showLoading() {
                $('#loading_overlay').addClass('show');
            }

            function hideLoading() {
                $('#loading_overlay').removeClass('show');
            }

            // Verificar erros do Laravel
            @if ($errors->any())
                showError('{{ $errors->first() }}');
            @endif
        });
    </script>
</body>
</html>
