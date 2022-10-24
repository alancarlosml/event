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




    {{-- <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h3>Admin</h3>
            </div>
            
            <div class="card-body">

                <p class="login-box-msg">Faça o login para ter acesso ao sistema</p>
      
                 <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                Lembrar
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
        
                {{-- <div class="social-auth-links text-center mt-2 mb-3">
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div>
            
                <p class="mb-1">
                    <a href="{{ route('password.request') }}">Esqueci a senha</a>
                </p>
            </div>
        </div>
    </div> --}}
    <!-- /.login-box -->
</x-guest-layout>