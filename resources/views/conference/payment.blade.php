<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finalizar Compra - {{ $event->name }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
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
                                <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
                            </div>
                        </div>

                        <hr>

                        <!-- Container de Erro -->
                        <div id="error_container" class="alert alert-danger d-none" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <span id="error_message"></span>
                        </div>

                        <!-- Indicador de Loading -->
                        <div id="loading_indicator" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            <p class="mt-2 text-muted">Carregando opções de pagamento...</p>
                        </div>

                        <!-- Container do Payment Brick -->
                        <div id="cardPaymentBrick_container"></div>

                        <!-- Container do Resultado da Operação -->
                        <div id="result_operation" class="d-none">
                            <div id="statusScreenBrick_container"></div>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Mercado Pago SDK -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    
    <script>
        $(document).ready(function() {
            
            // Obter public key do backend
            const publicKey = "{{ env('MERCADO_PAGO_PUBLIC_KEY') }}";
            if (!publicKey) {
                console.error('MERCADO_PAGO_PUBLIC_KEY não configurada');
                showError('Configuração de pagamento inválida. Entre em contato com o suporte.');
                return;
            }
            
            const mp = new MercadoPago(publicKey, {
                locale: 'pt-BR'
            });

            const bricksBuilder = mp.bricks();

            let maxDate = "{{ $event->max_event_dates() }} 00:00:00";
            let maxDateObj = new Date(maxDate);
            let now = new Date();
            now.setDate(now.getDate() + 3);

            // Definir métodos de pagamento baseado na data
            let paymentMethods = {
                creditCard: 'all',
                bankTransfer: ['pix']
            };

            if (now < maxDateObj) {
                paymentMethods.ticket = ['bolbradesco'];
            }

            // Função para mostrar erro
            function showError(message) {
                $('#error_message').text(message);
                $('#error_container').removeClass('d-none');
                $('#loading_indicator').addClass('d-none');
                const errorContainer = document.getElementById('error_container');
                if (errorContainer && errorContainer.offsetTop) {
                    $('html, body').animate({
                        scrollTop: errorContainer.offsetTop - 100
                    }, 500);
                }
            }

            // Função para ocultar loading
            function hideLoading() {
                $('#loading_indicator').addClass('d-none');
            }

            const renderCardPaymentBrick = async (bricksBuilder) => {
                try {
                    const settings = {
                        initialization: {
                            amount: {{ $total }},
                            locale: 'pt-BR',
                            marketplace: true  // 👈 HABILITA SPLIT PAYMENT PARA MARKETPLACE
                        },
                        customization: {
                            paymentMethods: paymentMethods,
                            visual: {
                                hideFormTitle: false,
                                style: {
                                    theme: 'bootstrap',
                                }
                            },
                        },
                        callbacks: {
                            onReady: () => {
                                hideLoading();
                                console.log('Payment Brick carregado com sucesso');
                            },
                            onSubmit: (cardFormData) => {
                                // Mostrar loading durante processamento
                                const submitButton = document.querySelector('[data-cy="submit-button"]');
                                if (submitButton) {
                                    submitButton.disabled = true;
                                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processando...';
                                }

                                return new Promise((resolve, reject) => {
                                    // Adicionar CSRF token
                                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                                    
                                    fetch("{{ route('conference.thanks', $event->slug) }}", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": csrfToken
                                        },
                                        body: JSON.stringify(cardFormData)
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error(`HTTP error! status: ${response.status}`);
                                        }
                                        return response.json();
                                    })
                                    .then((result) => {
                                        // Ocultar o formulário de pagamento
                                        document.getElementById('cardPaymentBrick_container').style.display = 'none';
                                        
                                        // Renderizar tela de status
                                        renderStatusScreenBrick(bricksBuilder, result.id);
                                        
                                        resolve();
                                    })
                                    .catch((error) => {
                                        console.error('Erro no pagamento:', error);
                                        
                                        // Reabilitar botão
                                        if (submitButton) {
                                            submitButton.disabled = false;
                                            submitButton.innerHTML = 'Pagar';
                                        }
                                        
                                        // Mostrar erro
                                        if (error.message.includes('400')) {
                                            showError('Dados de pagamento inválidos. Verifique as informações e tente novamente.');
                                        } else if (error.message.includes('500')) {
                                            showError('Erro interno do servidor. Tente novamente em alguns minutos.');
                                        } else {
                                            showError('Erro ao processar pagamento. Tente novamente.');
                                        }
                                        
                                        reject(error);
                                    });
                                });
                            },
                            onError: (error) => {
                                console.error('Erro no Brick:', error);
                                hideLoading();
                                showError('Erro ao carregar as opções de pagamento. Recarregue a página e tente novamente.');
                            },
                        },
                    };

                    const cardPaymentBrickController = await bricksBuilder.create(
                        'payment',
                        'cardPaymentBrick_container', 
                        settings
                    );
                    
                } catch (error) {
                    console.error('Erro ao criar Payment Brick:', error);
                    hideLoading();
                    showError('Erro ao inicializar o sistema de pagamento. Recarregue a página e tente novamente.');
                }
            };

            const renderStatusScreenBrick = async (bricksBuilder, paymentId) => {
                try {
                    const settings = {
                        initialization: {
                            paymentId: paymentId,
                        },
                        callbacks: {
                            onReady: () => {
                                const resultOperation = document.getElementById('result_operation');
                                if (resultOperation) {
                                    resultOperation.classList.remove('d-none');
                                }
                                const statusContainer = document.getElementById('statusScreenBrick_container');
                                if (statusContainer && statusContainer.offsetTop) {
                                    $('html, body').animate({
                                        scrollTop: statusContainer.offsetTop - 100
                                    }, 500);
                                }
                            },
                            onError: (error) => {
                                console.error('Erro no Status Screen Brick:', error);
                                showError('Erro ao exibir status do pagamento.');
                            },
                        },
                    };
                    
                    await bricksBuilder.create(
                        'statusScreen',
                        'statusScreenBrick_container',
                        settings
                    );
                } catch (error) {
                    console.error('Erro ao criar Status Screen Brick:', error);
                    showError('Erro ao exibir status do pagamento.');
                }
            };

            // Inicializar o Payment Brick
            renderCardPaymentBrick(bricksBuilder);

            // Verificar se há erros de sessão do Laravel
            @if ($errors->any())
                hideLoading();
                showError('{{ $errors->first() }}');
            @endif
        });
    </script>
</body>
</html>