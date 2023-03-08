<x-guestsite-layout>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="logo text-center mb-5 mt-5">
                    <a href="/">
                        <img src="{{ asset('assets/img/logo_principal.png') }}" alt="">
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
                                <div id="paymentBrick_container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade mt-5" id="cupomModal" tabindex="-1" role="dialog" aria-labelledby="cupomModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <strong class="modal-title" id="cupomModalLabel"><img id="modal_icon" src="/assets_conference/imgs/success.png" style="max-height: 48px"> <span id="modal_txt">Cupom adicionado com sucesso!</span></strong>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="modal_close" class="btn btn-secondary" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>

            </section><!-- End Contact Section -->
        </div>
    </section>

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
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
        <script>

                // $('#checkout_submit').validate({
                //     errorClass: "error fail-alert d-block"
                // });

                // $('#finalizar_comprar').show();
                // $('#carregando_comprar').addClass('d-none');
                
                // // $('.date_mask').mask('00/00/0000', {placeholder: "__/__/____"});
                // // $('.cep').mask('00000-000');
                // $('.phone_with_ddd_mask').mask('(00) 00000-0000');
                // $('.cpf_mask').mask('000.000.000-00', {reverse: false});
                // $('.cnpj_mask').mask('00.000.000/0000-00', {reverse: false});
                // $('.cep_mask').mask('00000-000');
                // $('.expiration_mask').mask('00/0000');
                // $('.cc_cvc_mask').mask('000');
                // $('.cc_number_mask').mask('YYYY YYYY YYYY YYYY', {'translation': {
                //         Y: {pattern: /[0-9]/}
                //     }
                // });
                // $('.cc_cvc_mask').mask('YYY', {'translation': {
                //         Y: {pattern: /[0-9]/}
                //     }
                // });

            // $(document).ready(function() { 

            //     const mp = new MercadoPago('{{env('MERCADO_PAGO_PUBLIC_KEY', '')}}', {
            //         locale: 'pt-BR'
            //     });

            //     const bricksBuilder = mp.bricks();
            //     const renderPaymentBrick = async (bricksBuilder) => {
            //         const settings = {
            //             initialization: {
            //                 amount: 100, // valor total a ser pago
            //                 locale: 'pt-BR'
            //             },
            //             customization: {
            //                 //maxInstallments: 10,
            //                 paymentMethods: {
            //                     creditCard: 'all',
            //                     ticket: ['bolbradesco'],
            //                     bankTransfer: ['pix'],
            //                 },
            //                 visual: {
            //                     hideFormTitle: true,
            //                     style: {
            //                         theme: 'bootstrap', // | 'dark' | 'bootstrap' | 'flat'
            //                     }
            //                 },
            //             },
            //             callbacks: {
            //                 onFormUnmounted: () =>{
            //                 },
            //                 onReady: () => {
            //                 },
            //                 onSubmit: ({ selectedPaymentMethod, formData }) => {
                            
            //                     return new Promise((resolve, reject) => {
            //                         fetch("{{route('conference.thanks', $event->slug)}}", {
            //                             method: "POST",
            //                             headers: {
            //                                 "Content-Type": "application/json",
            //                             },
            //                             // "_token": "csrf_token()",
            //                             body: JSON.stringify(formData) 
            //                         })
            //                         .then((response) => {
            //                             console.log(response);
            //                             resolve();
            //                         })
            //                         .catch((error) => {
            //                             console.log(error);
            //                             reject();
            //                         })
            //                     });
            //                 },
            //                 onError: (error) => {
            //                     console.error(error);
            //                 },
            //             },
            //         };
            //         window.paymentBrickController = await bricksBuilder.create(
            //             'payment',
            //             'paymentBrick_container',
            //             settings);
            //     };
            //     renderPaymentBrick(bricksBuilder);
            // });

            $(document).ready(function() {
	
                const mp = new MercadoPago('{{env('MERCADO_PAGO_PUBLIC_KEY', '')}}');

                const bricksBuilder = mp.bricks();

                const renderPaymentBrick = async (bricksBuilder) => {
                    const settings = {
                    initialization: {
                        amount: 100, // valor total a ser pago
                    },
                    customization: {
                        paymentMethods: {
                        creditCard: 'all',
                        debitCard: 'all',
                        },
                    },
                    callbacks: {
                            onReady: () => {
                            console.log('brick ready');
                            },
                            onError: (error) => {
                                alert(JSON.stringify(error))
                            },
                            onSubmit: (cardFormData) => {
                                fetch("{{route('conference.thanks', $event->slug)}}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                    },
                                    body: JSON.stringify(cardFormData),
                                })
                                .then(response => {
                                    return response.json();
                                })
                                .catch(error => {
                                    alert("Unexpected error\n"+JSON.stringify(error));
                                });
                            },   
                        },
                    };
                    window.paymentBrickController = await bricksBuilder.create('payment', 'paymentBrick_container', settings);
                };
                renderPaymentBrick(bricksBuilder);
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