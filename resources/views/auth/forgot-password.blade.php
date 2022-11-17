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
                                    <h3 class="mb-4">Esqueceu sua senha?</h3>
                                </div>
                            </div>

                            <div class="mb-4 text-lg text-gray-600">
                                {{ __('Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail e nós lhe enviaremos um link de redefinição de senha que permitirá que você escolha uma nova.') }}
                            </div>
                    
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />
                    
                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    
                            <form method="POST" action="{{ route('password.email') }}" id="form-submit">
                                @csrf
                    
                                <!-- Email Address -->
                                <div>
                                    <x-label for="email" :value="__('Informe seu email')" />
                    
                                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
                                </div>
                    
                                <div class="flex items-center justify-end mt-4">
                                    <x-button class="btn btn-primary" id="btn-submit">
                                        {{ __('Enviar email') }}
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('footer')
        <script>
            $(document).ready(function() {
                $(document).on('submit', '#form-submit', function() {
                    $('#btn-submit').attr('disabled', 'disabled');
                });
            });
        </script>
    @endpush
</x-guestsite-layout>