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
                                        <h2>{{ __('auth.login.cover_title') }}</h2>
                                        <p class="mt-3 mb-0">{{ __('auth.login.cover_subtitle') }}</p>
                                    </div>
                                    <div>
                                        <ul class="mb-0 ps-3">
                                            <li>{{ __('auth.login.benefit_1') }}</li>
                                            <li>{{ __('auth.login.benefit_2') }}</li>
                                            <li>{{ __('auth.login.benefit_3') }}</li>
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

                                    <span class="auth-shell-kicker">{{ __('auth.login.welcome_back') }}</span>
                                    <h1 class="auth-shell-title">{{ __('auth.login.title') }}</h1>
                                    <p class="auth-shell-copy">{{ __('auth.login.description') }}</p>

                                    <x-auth-session-status class="mb-4" :status="session('status')" />
                                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                                    <form method="POST" action="{{ route('login') }}" class="signin-form" id="form-submit">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label class="label" for="email">{{ __('auth.login.email') }}</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="{{ __('auth.login.email') }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="label" for="password">{{ __('auth.login.password') }}</label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="{{ __('auth.login.password') }}" required>
                                        </div>
                                        <div class="form-group">
                                            {!! RecaptchaV3::field('login') !!}
                                            <button type="submit" id="btn-submit" class="btn btn-primary w-100 submit px-3">{{ __('auth.login.title') }}</button>
                                        </div>
                                        <div class="form-group d-flex justify-content-between align-items-center mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                                <label class="form-check-label" for="remember">
                                                    {{ __('auth.login.remember') }}
                                                </label>
                                            </div>
                                            <div>
                                                <a href="{{ route('password.request') }}">{{ __('auth.login.forgot_password') }}</a>
                                            </div>
                                        </div>
                                    </form>
                                    <p class="text-center mt-4 mb-0">{{ __('auth.login.not_registered') }} <a href="/painel/cadastrar" class="fw-bold">{{ __('auth.login.register_link') }}</a></p>
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
