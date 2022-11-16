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
                        <div class="img" style="background-image:url({{ asset('site/verify_email.png')}}); background-size: 200px"></div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">Obrigado por inscrever-se!</h3>
                                </div>
                            </div>
                            <div>
                                <strong>Verifique o e-mail de confirmação em <b>{{$email_participante}}</b></strong><br><br>
                                <span>Atenção! Se você não receber o e-mail em alguns minutos: </span><br>
                                <ul>
                                    <li>verifique a pasta de spam</li>
                                    <li>verifique se você digitou seu e-mail corretamente</li>
                                    <li>se você não conseguir resolver o problema, entre em contato com contato@lojadeeventos.com.br</li>
                                </ul>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guestsite-layout>