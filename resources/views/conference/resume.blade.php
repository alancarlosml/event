<x-event-layout>
    <section id="checkout" class="section-bg" style="margin-top: 120px">
        <div class="container pb-5">
            <div class="py-5 text-center">
                <div class="section-header">
                    <h2>Finalizar compra</h2>
                </div>
            </div>
            <div class="card mb-5 pt-3 pb-3">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <img src="{{ URL::asset('storage/' . $event->banner) }}" alt="{{ $event->name }}"
                                class="img-fluid">
                        </div>
                        <div class="col-8">
                            <h3 style="font-size: 24px">{{ $event->name }}</h3>
                            <span><b>Data:</b> </span><br>
                            {{-- <span><b>Data:</b> {{ \Carbon\Carbon::parse($eventDate->date)->format('d/m/y') }}</span><br> --}}
                            <span><b>Local:</b> {{ $event->place->name }}</span><br>
                            <span><b>Lote(s) selecionado(s):</b> </span>
                            <ul class="list-style">
                                @foreach ($array_lotes as $array_lote)
                                    <li class="ml-4" style="list-style-type: circle">{{ $array_lote['quantity'] }}
                                        x {{ $array_lote['name'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <div class="section-title-header text-center">
                        <h2 class="section-title wow fadeInUp animated">Resumo da compra</h2>
                    </div>
                    <ul class="list-group mb-3">
                        @foreach ($array_lotes as $array_lote)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>{{ $array_lote['quantity'] }} @if ($array_lote['quantity'] == 1)
                                            ingresso
                                        @else
                                            ingressos
                                        @endif
                                    </div>
                                    @if ($array_lote['value'] == 0)
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
                                    <h6 class="my-0">Cupom ({{ $coupon[0]['code'] }})</h6>
                                </div>
                                <span class="text-success">
                                    -
                                    @if ($coupon[0]['type'] == 0)
                                        @money(($subtotal * $coupon[0]['value']) / 100)
                                    @else
                                        @money($coupon[0]['value'])
                                    @endif
                                </span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between" style="border-top: solid 1px #999">
                            <strong>Total</strong>
                            <strong>@money($total)</strong>
                        </li>
                    </ul>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <strong>Compra 100% segura!&nbsp;&nbsp;&nbsp;</strong>
                                <img src="/assets_conference/imgs/mercado-pago-logo.png" alt="Mercado Pago"
                                    height="30px" />
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
                    <form method="POST" id="checkout_submit" action="{{ route('conference.payment', $event->slug) }}">
                        @csrf
                        <div class="mb-3">
                            <div class="section-title-header text-center mb-4">
                                <h2 class="section-title wow fadeInUp animated">Dados da inscrição</h2>
                            </div>
                            @foreach ($array_lotes_obj as $k => $lote_obj)
                                <strong class="d-block mb-3"><u>Informações do participante #{{ $k + 1 }} ({{ $lote_obj['name'] }})</u></strong>
                                @foreach ($questions as $id => $question)
                                    @switch($question->option_id)
                                        @case(1)
                                            {{-- Campo texto --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <input type="text" class="form-control new_field"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required="required" title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(2)
                                            {{-- Campo seleção --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <select class="form-control new_field"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                                    <option value="0">Selecione</option>
                                                    @foreach ($question->value() as $value)
                                                        <option>{{ $value->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @break

                                        @case(3)
                                            {{-- Campo marcação --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <br />
                                                <p class='container_radio'>
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
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <p class='container_checkbox'>
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
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <input type="text" class="form-control new_field cpf_mask"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(6)
                                            {{-- Campo CNPJ --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <input type="text" class="form-control new_field cnpj_mask"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(7)
                                            {{-- Campo Data --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <input type="date" class="form-control new_field date_mask"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(8)
                                            {{-- Campo Telefone --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <input type="text" class="form-control new_field phone_with_ddd_mask"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(9)
                                            {{-- Campo Número inteiro --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <input type="number" class="form-control new_field"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(10)
                                            {{-- Campo Número decimal --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <input type="number" class="form-control new_field"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(11)
                                            {{-- Campo arquivo --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <input type="file" class="form-control new_field"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(12)
                                            {{-- Campo Textarea --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <textarea class="form-control new_field" name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif></textarea>
                                            </div>
                                        @break

                                        @case(13)
                                            {{-- Campo Email --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <input type="email" class="form-control new_field"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                            </div>
                                        @break

                                        @case(14)
                                            {{-- Campo estados --}}
                                            <div class="form-group">
                                                <label for="new_field">{{ $question->question }}@if ($question->required == 1)
                                                        *
                                                    @endif
                                                </label>
                                                <select class="form-control new_field"
                                                    name="newfield_{{ $k + 1 }}_{{ $question->id }}"
                                                    @if ($question->required) required title="Este campo é obrigatório" @endif>
                                                    <option value="0">Selecione</option>
                                                    @foreach ($question->value() as $value)
                                                        <option>{{ $value->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @break

                                        @default
                                    @endswitch
                                @endforeach
                                @if (!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-common sub-btn float-right"
                            id="finalizar_comprar">Continuar para pagamento</button>
                    </form>
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
                            data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- End Contact Section -->

    @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('theme')
        @if ($event->theme == 'red')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/red.css') }}" type="text/css">
        @elseif($event->theme == 'blue')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/blue.css') }}" type="text/css">
        @elseif($event->theme == 'green')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/green.css') }}"
                type="text/css">
        @elseif($event->theme == 'purple')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/purple.css') }}"
                type="text/css">
        @elseif($event->theme == 'orange')
            <link rel="stylesheet" id="colors" href="{{ asset('assets_conference/css/orange.css') }}"
                type="text/css">
        @endif
    @endpush

    @push('head')
    @endpush

    @push('footer')
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js">
        </script>
        <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
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

                // const mp = new MercadoPago('{{ env('MERCADO_PAGO_PUBLIC_KEY', '') }}', {
                //     locale: 'pt-BR'
                // });

                // const bricksBuilder = mp.bricks();
                // const renderPaymentBrick = async (bricksBuilder) => {
                // const settings = {
                // initialization: {
                //     amount: 100, // valor total a ser pago
                //     locale: 'pt-BR',
                // },
                // customization: {
                //     maxInstallments: 10,
                //     paymentMethods: {
                //         creditCard: 'all',
                //         ticket: ['bolbradesco'],
                //         bankTransfer: ['pix'],
                //     },
                // },
                // callbacks: {
                //     onReady: () => {
                //         /*
                //         Callback chamado quando o Brick estiver pronto.
                //         Aqui você pode ocultar loadings do seu site, por exemplo.
                //         */
                //     },
                //     onSubmit: ({ selectedPaymentMethod, formData }) => {
                //     // callback chamado ao clicar no botão de submissão dos dados

                //         return new Promise((resolve, reject) => {
                //         fetch("/processar-pago", {
                //             method: "POST",
                //             headers: {
                //             "Content-Type": "application/json",
                //             },
                //             body: JSON.stringify(formData)
                //         })
                //             .then((response) => {
                //             // receber o resultado do pagamento
                //             resolve();
                //             })
                //             .catch((error) => {
                //             // lidar com a resposta de erro ao tentar criar o pagamento
                //             reject();
                //             })
                //         });

                //     },
                //     onError: (error) => {
                //     // callback chamado para todos os casos de erro do Brick
                //     console.error(error);
                //     },
                // },
                // };
                // window.paymentBrickController = await bricksBuilder.create(
                //     'payment',
                //     'paymentBrick_container',
                //     settings
                //     );
                // };
                // renderPaymentBrick(bricksBuilder);

            });


            $(document).on('submit', '#checkout_submit', function() {

                // if ($(".form-check-input").is(":checked")) {
                //     $('#finalizar_comprar').attr('disabled', 'disabled');
                // } else {
                //     $('#modal_txt').text('Por favor, selecione ao menos uma forma de pagamento.');
                //     $('#modal_icon').attr('src', '/assets_conference/imgs/alert.png');
                //     $('#cupomModal').modal('show');
                // }
            });
        </script>
    @endpush

</x-event-layout>
