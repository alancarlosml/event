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
                                    <h3 class="mb-4">{{ __('auth.register.title') }}</h3>
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

                            <form method="POST" action="{{ route('register') }}" class="signin-form" id="form-submit">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="label" for="name">{{ __('auth.register.name') }}</label>
                                    <input type="text" class="form-control" name="name" placeholder="{{ __('auth.register.name_placeholder') }}" required="" value="{{old('name')}}">
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-md-6">
                                        <label class="label" for="email">{{ __('auth.register.email') }}</label>
                                        <input type="email" class="form-control" name="email" placeholder="{{ __('auth.register.email') }}" required="" value="{{old('email')}}">
                                    </div>
                                    <div class="form-group mb-3 col-md-6">
                                        <label class="label" for="phone">{{ __('auth.register.phone') }}</label>
                                        <input type="text" class="form-control" name="phone" id="phone_with_ddd_mask" placeholder="{{ __('auth.register.phone') }}" required="" value="{{old('phone')}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-md-6">
                                        <label class="label" for="password">{{ __('auth.register.password') }}</label>
                                        <input type="password" class="form-control" name="password" placeholder="{{ __('auth.register.password') }}" required="">
                                    </div>
                                    <div class="form-group mb-3 col-md-6">
                                        <label class="label" for="password_confirmation">{{ __('auth.register.password_confirmation') }}</label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="{{ __('auth.register.password_confirmation') }}" required="">
                                    </div>
                                </div>
                                <div class="form-group form-check mb-3 col-md-10">
                                    <input type="checkbox" class="form-check-input" id="read_terms" name="read_terms">
                                    <label class="form-check-label" for="read_terms">
                                        {!! __('auth.register.agree_terms', [
                                            'terms' => '<a href="/termos">'.__('auth.register.terms').'</a>',
                                            'privacy' => '<a href="/politica">'.__('auth.register.privacy').'</a>'
                                        ]) !!}
                                    </label>
                                </div>
                                <div class="form-group">
                                    {!! RecaptchaV3::field('register') !!}
                                    <button type="submit" id="btn-submit" class="form-control btn btn-primary rounded submit px-3">{{ __('auth.register.title') }}</button>
                                </div>
                            </form>
                            <p class="text-center mt-2">{{ __('auth.register.already_registered') }} <a href="/painel/login">{{ __('auth.register.login_link') }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('footer')
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>   
        <script>
            $(document).ready(function() {
                $(document).on('submit', '#form-submit', function() {
                    $('#btn-submit').attr('disabled', 'disabled');
                });
                $('#phone_with_ddd_mask').mask('(00) 00000-0000');
            });
        </script>
    @endpush
</x-guestsite-layout>
