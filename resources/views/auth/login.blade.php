<x-guestsite-layout>

    <section class="auth-shell-wrap d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="auth-shell-card">
                        <div class="row g-0">
                            <div class="col-lg-5 d-none d-lg-block">
                                <div class="auth-shell-cover">
                                    <div>
                                        <h2>Entre para organizar, vender e acompanhar seus eventos.</h2>
                                        <p class="mt-3 mb-0">Uma única conta para criar eventos, revisar inscrições, acompanhar certificados e operar o painel sem trocar de contexto.</p>
                                    </div>
                                    <div>
                                        <ul class="mb-0 ps-3">
                                            <li>Fluxo de compra, certificados e gestão no mesmo produto.</li>
                                            <li>Experiência consistente em desktop e mobile.</li>
                                            <li>Acesso rápido ao que você precisa fazer agora.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-7">
                                <div class="auth-shell-panel d-flex flex-column justify-content-center">
                                    <div class="auth-shell-logo">
                                        <a href="/" class="d-inline-block">
                                            <img src="{{ asset('assets/img/logo_principal.png') }}" alt="Logo" class="img-fluid" style="max-height: 64px;" loading="lazy">
                                        </a>
                                    </div>

                                    <span class="auth-shell-kicker">Bem-vindo de volta</span>
                                    <h1 class="auth-shell-title">Entrar</h1>
                                    <p class="auth-shell-copy">Acesse sua conta para continuar gerenciando eventos, inscrições e pagamentos em um só lugar.</p>

                                    <x-auth-session-status class="mb-4" :status="session('status')" />
                                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                                    <form method="POST" action="{{ route('login') }}" class="signin-form" id="form-submit">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label class="label" for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="label" for="password">Senha</label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Senha" required>
                                        </div>
                                        <div class="form-group">
                                            {!! RecaptchaV3::field('login') !!}
                                            <button type="submit" id="btn-submit" class="btn btn-primary w-100 submit px-3">Entrar</button>
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
                                    <p class="text-center mt-4 mb-0">Não é cadastrado? <a href="/painel/cadastrar" class="fw-bold">Cadastrar</a></p>
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
