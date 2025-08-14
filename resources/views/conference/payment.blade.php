<x-guestsite-layout>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center gy-4"> <!-- gy-4 para responsividade -->
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
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <div class="mb-4 mt-5">
                                <div id="cardPaymentBrick_container"></div>
                                <div id="statusScreenBrick_container"></div>
                            </div>
                            <div id="result_operation" class="flex d-none">
                                <p class="text-center">
                                    Verifique a situação dos seus ingressos em <a href="{{ route('event_home.my_registrations') }}" class="alert-link text-decoration-none">Minhas inscrições</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal com ARIA -->
                <div class="modal fade mt-5" id="cupomModal" tabindex="-1" aria-labelledby="cupomModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <strong class="modal-title" id="cupomModalLabel"><img id="modal_icon" src="/assets_conference/imgs/success.png" style="max-height: 48px" alt="Ícone do modal"> <span id="modal_txt">Cupom adicionado com sucesso!</span></strong>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="modal_close" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Fechar">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>

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

                const mp = new MercadoPago('{{ env('MERCADO_PAGO_PUBLIC_KEY', '') }}', {
                    locale: 'pt-BR'
                });

                const bricksBuilder = mp.bricks();

                let maxDate = "{{$event->max_event_dates()}} 00:00:00";

                let maxDateObj = new Date(maxDate);

                let now = new Date();

                now.setDate(now.getDate() + 3);

                let paymentMethods = {
                    creditCard: 'all',
                    bankTransfer: ['pix']
                };

                if(now < maxDateObj) {
                    paymentMethods.ticket = ['bolbradesco'];
                }

                const renderCardPaymentBrick = async (bricksBuilder) => {
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
                                /*
                                Callback chamado quando o Brick estiver pronto.
                                Aqui você pode ocultar loadings do seu site, por exemplo.
                                */
                            },
                            onSubmit: (cardFormData) => {
                                // callback chamado o usuário clicar no botão de submissão dos dados

                                // ejemplo de envío de los datos recolectados por el Brick a su servidor
                                return new Promise((resolve, reject) => {
                                    fetch("{{ route('conference.thanks', $event->slug) }}", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json",
                                            },
                                            body: JSON.stringify(cardFormData)
                                        }).then(response => {
                                            document.getElementById(
                                                    'cardPaymentBrick_container').style
                                                .display = 'none';
                                            return response.json();
                                        })
                                        .then((result) => {
                                            const renderStausScreenBrick = async (
                                                bricksBuilder) => {
                                                const settings = {
                                                    initialization: {
                                                        paymentId: result
                                                            .id, // id de pagamento gerado pelo Mercado Pago
                                                    },
                                                    callbacks: {
                                                        onReady: () => {
                                                            // callback chamado quando o Brick estiver pronto
                                                        },
                                                        onError: (error) => {
                                                            // callback chamado para todos os casos de erro do Brick
                                                        },
                                                    },
                                                };
                                                window.statusBrickController =
                                                    await bricksBuilder.create(
                                                        'statusScreen',
                                                        'statusScreenBrick_container',
                                                        settings
                                                    );
                                                document.getElementById(
                                                    'result_operation').classList.remove('d-none');
                                            };
                                            renderStausScreenBrick(bricksBuilder);
                                            // receber o resultado do pagamento
                                            resolve();
                                        })
                                        .catch((error) => {
                                            console.error(error);
                                            reject();
                                        })
                                });
                            },
                            onError: (error) => {
                                // callback chamado para todos os casos de erro do Brick
                                console.error(error);
                            },
                        },
                    };
                    const cardPaymentBrickController = await bricksBuilder.create('payment',
                        'cardPaymentBrick_container', settings);

                };
                renderCardPaymentBrick(bricksBuilder);

            });


            $(document).on('submit', '#checkout_submit', function() {

                if ($(".form-check-input").is(":checked")) {
                    $('#finalizar_comprar').attr('disabled', 'disabled');
                } else {
                    $('#modal_txt').text('Por favor, selecione ao menos uma forma de pagamento.');
                    $('#modal_icon').attr('src', '/assets_conference/imgs/alert.png');
                    $('#cupomModal').modal('show');
                }
            });
        </script>
    @endpush

</x-guestsite-layout>