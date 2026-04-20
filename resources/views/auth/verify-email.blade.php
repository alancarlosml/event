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
                                    <h3 class="mb-4">{{ __('auth.verify_email.title') }}</h3>
                                </div>
                            </div>

                            <div>
                        
                                <div class="mb-4 text-base text-gray-600">
                                    {{ __('auth.verify_email.description') }}
                                </div>
                        
                                @if (session('status') == 'verification-link-sent')
                                    <div class="mb-4 font-medium text-sm text-green-600">
                                        {{ __('auth.verify_email.sent') }}
                                    </div>
                                @endif
                        
                                <div class="mt-4 d-flex items-center justify-between">
                                    <form method="POST" action="{{ route('verification.send') }}" id="form-submit">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" id="btn-submit">
                                            {{ __('auth.verify_email.resend_button') }}
                                        </button> &nbsp;&nbsp;&nbsp;
                                    </form>
                        
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary">
                                            {{ __('auth.verify_email.logout_button') }}
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
