<x-guest-layout>
    <section class="auth-shell-wrap d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="auth-shell-card">
                        <div class="row g-0">
                            <div class="col-lg-5 d-none d-lg-block">
                                <div class="auth-shell-cover">
                                    <div>
                                        <span class="auth-shell-kicker">Acesso administrativo</span>
                                        <h2>Painel interno com a mesma identidade do produto.</h2>
                                        <p class="mt-3 mb-0">Quando esse acesso ainda existir, ele deve parecer parte do Ticket DZ6 e não um sistema paralelo.</p>
                                    </div>
                                    <div>
                                        <ul class="mb-0 ps-3">
                                            <li>Entradas rápidas para operação e supervisão.</li>
                                            <li>Hierarquia visual consistente com o login padrão.</li>
                                            <li>Fluxo mais claro em desktop e mobile.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="auth-shell-panel">
                                    <div class="auth-shell-logo">
                                        <a href="/" class="d-inline-block">
                                            <img src="{{ asset('assets/img/logo_principal.png') }}" alt="Ticket DZ6" class="img-fluid" style="max-height: 54px;">
                                        </a>
                                    </div>

                                    <span class="auth-shell-kicker">Área restrita</span>
                                    <h1 class="auth-shell-title">Entrar como administrador</h1>
                                    <p class="auth-shell-copy">Use seu acesso para acompanhar a operação da plataforma e entrar no painel interno.</p>

                                    <x-auth-session-status class="mb-4" :status="session('status')" />
                                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                                    <form method="POST" action="{{ route('admin.login') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" name="email" id="email" class="form-control" placeholder="seu@email.com" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Senha</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input type="password" name="password" id="password" class="form-control" placeholder="Digite sua senha" required>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                                            <div class="form-check m-0">
                                                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                                <label for="remember" class="form-check-label">Lembrar acesso</label>
                                            </div>
                                            <a href="{{ route('password.request') }}">Esqueci a senha</a>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            Entrar no painel
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
</x-guest-layout>
