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
                        <div class="img" style="background-image:url({{ asset('site/home4.jpg')}})"></div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">Entrar</h3>
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
                            <form method="POST" action="{{ route('login') }}" class="signin-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="label" for="email">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email" required="">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="password">Senha</label>
                                    <input type="password" class="form-control" name="password" placeholder="Senha" required="">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Entrar</button>
                                </div>
                                <div class="form-group d-flex mt-2">
                                    <div class="w-50 text-left">
                                        <label class="label">
                                            <input type="checkbox" checked=""> Lembrar
                                        </label>
                                    </div>
                                    <div class="w-50" style="text-align: right">
                                        <a href="{{ route('password.request') }}">Esqueceu a senha?</a>
                                    </div>
                                </div>
                            </form>
                            <p class="text-center mt-2">Não é cadastrado? <a href="/painel/cadastrar">Cadastrar</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guestsite-layout>