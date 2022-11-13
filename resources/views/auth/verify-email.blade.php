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
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">Verique seu email</h3>
                                </div>
                            </div>

                            <div>
                        
                                <div class="mb-4 text-sm text-gray-600">
                                    {{ __('Obrigado por se cadastrar na Loja de Eventos! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, teremos o prazer de lhe enviar outro.') }}
                                </div>
                        
                                @if (session('status') == 'verification-link-sent')
                                    <div class="mb-4 font-medium text-sm text-green-600">
                                        {{ __('Um novo link de verificação foi enviado para o endereço de e-mail fornecido durante o registro.') }}
                                    </div>
                                @endif
                        
                                <div class="mt-4 d-flex items-center justify-between">
                                    <form method="POST" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Reenviar email') }}
                                        </button> &nbsp;&nbsp;&nbsp;
                                    </form>
                        
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary">
                                            {{ __('Sair') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-guestsite-layout>
