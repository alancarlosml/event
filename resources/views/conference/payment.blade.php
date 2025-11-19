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
    @elseif ($event->theme == 'blue')
        <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/blue.css') }}" type="text/css">
    @elseif ($event->theme == 'green')
        <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/green.css') }}" type="text/css">
    @elseif ($event->theme == 'purple')
        <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/purple.css') }}" type="text/css">
    @elseif ($event->theme == 'orange')
        <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/orange.css') }}" type="text/css">
    @endif
</head>
<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="logo text-center mb-5 mt-5">
                    <a href="/">
                        <img src="{{ asset('assets/img/logo_principal.png') }}" alt="Logo Ticket DZ6" loading="lazy">
                    </a>
                </div>
            </div>
            
            <section id="checkout" class="section-bg">
                <div class="container pb-5">
                    <div class="py-5 text-center">
                        <div class="section-header">
                            <h2>Finalizar compra</h2>
                            <p class="text-muted">Total: R$ {{ number_format($total, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-10">
                            <!-- Loading indicator -->
                            <div id="loading_indicator" class="text-center mb-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                                <p class="mt-2">Carregando opções de pagamento...</p>
                            </div>
                            
                            <!-- Error container -->
                            <div id="error_container" class="alert alert-danger d-none" role="alert">
                                <strong>Erro:</strong> <span id="error_message"></span>
                            </div>
                            
                            <!-- Payment brick container -->
                            <div class="mb-4 mt-5">
                                <div id="cardPaymentBrick_container"></div>
                                <div id="statusScreenBrick_container"></div>
                            </div>
                            
                            <!-- Success message -->
                            <div id="result_operation" class="alert alert-success d-none text-center">
                                <h4 class="alert-heading">Pagamento processado!</h4>
                                <p class="mb-0">
                                    Verifique a situação dos seus ingressos em 
                                    <a href="{{ route('event_home.my_registrations') }}" class="alert-link text-decoration-none">
                                        Minhas inscrições
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>

    <!-- Bootstrap JS -->
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
                $('html, body').animate({
                    scrollTop: $("#error_container").offset().top - 100
                }, 500);
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
                            locale: 'pt-BR'
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
                                document.getElementById('result_operation').classList.remove('d-none');
                                $('html, body').animate({
                                    scrollTop: $("#statusScreenBrick_container").offset().top - 100
                                }, 500);
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