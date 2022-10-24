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
                                    <h3 class="mb-4">Cadastrar</h3>
                                </div>
                                {{-- 
                                <div class="w-100">
                                    <p class="social-media d-flex justify-content-end">
                                        <a href="https://preview.colorlib.com/theme/bootstrap/login-form-14/#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
                                        <a href="https://preview.colorlib.com/theme/bootstrap/login-form-14/#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
                                    </p>
                                </div>
                                --}}
                            </div>
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />
                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="mb-4" :errors="$errors" />

                            <form method="POST" action="{{ route('register') }}" class="signin-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="label" for="name">Nome completo</label>
                                    <input type="text" class="form-control" name="name" placeholder="Nome" required="" value="{{old('name')}}">
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-md-6">
                                        <label class="label" for="email">Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="Email" required="" value="{{old('email')}}">
                                    </div>
                                    <div class="form-group mb-3 col-md-6">
                                        <label class="label" for="phone">Telefone</label>
                                        <input type="text" class="form-control" name="phone" placeholder="Telefone" required="" value="{{old('phone')}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-md-6">
                                        <label class="label" for="password">Senha</label>
                                        <input type="password" class="form-control" name="password" placeholder="Senha" required="">
                                    </div>
                                    <div class="form-group mb-3 col-md-6">
                                        <label class="label" for="password_confirmation">Confirmar senha</label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmar senha" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Cadastrar</button>
                                </div>
                            </form>
                            <p class="text-center mt-2">Já cadastrado? <a href="/painel/login">Entrar</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    {{-- <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card> --}}
</x-guest-layout>
