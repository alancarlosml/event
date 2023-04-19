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
                                    <h3 class="mb-4">Redefinir a sua senha</h3>
                                </div>
                            </div>

                            <div>

                                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                                <div class="mt-4 d-flex items-center justify-between">

                                    <form method="POST" action="{{ route('password.update') }}" id="form-submit" class="col-6">
                                        @csrf

                                        <!-- Password Reset Token -->
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                        <!-- Email Address -->
                                        <div>
                                            <x-label for="email" :value="__('Email')" />

                                            <x-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus />
                                        </div>

                                        <!-- Password -->
                                        <div class="mt-4">
                                            <x-label for="password" :value="__('Senha')" />

                                            <x-input id="password" class="form-control" type="password" name="password" required />
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mt-4">
                                            <x-label for="password_confirmation" :value="__('Confirmar senha')" />

                                            <x-input id="password_confirmation" class="form-control"
                                                                type="password"
                                                                name="password_confirmation" required />
                                        </div>

                                        <div class="flex items-center justify-end mt-4">
                                            <x-button class="btn btn-primary" id="btn-submit">
                                                {{ __('Redefinir senha') }}
                                            </x-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
