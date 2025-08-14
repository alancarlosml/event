<x-guestsite-layout>

    <section class="ftco-section min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-auto">
                    <a href="/" class="d-block">
                        <img src="{{ asset('assets/img/logo_principal.png') }}" alt="Logo" class="img-fluid" style="max-height: 100px;" loading="lazy">
                    </a>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-10">
                    <div class="row g-0 shadow-lg rounded overflow-hidden">
                        <div class="col-6 d-none d-md-block">
                            <div class="h-100" style="background: url({{ asset('site/home4.jpg') }}) center/cover; min-height: 500px;" loading="lazy"></div>
                        </div>
                        <div class="col-12 col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">Entrar</h3>    
                                </div>
                            </div>
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />
                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="mb-4" :errors="$errors" />
                            <form method="POST" action="{{ route('login') }}" class="signin-form" id="form-submit">
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
                                    {!! RecaptchaV3::field('login') !!}
                                    <button type="submit" id="btn-submit" class="form-control btn btn-primary rounded submit px-3">Entrar</button>
                                </div>
                                <div class="form-group d-flex justify-content-between align-items-center mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Lembrar
                                        </label>
                                    </div>
                                    <div>
                                        <a href="{{ route('password.request') }}">Esqueceu a senha?</a>
                                    </div>
                                </div>
                            </form>
                            <p class="text-center mt-4">Não é cadastrado? <a href="/painel/cadastrar" class="fw-bold">Cadastrar</a></p>
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