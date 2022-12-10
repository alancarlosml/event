<!DOCTYPE html>
<!-- saved from url=(0063)javascript:; -->
<html lang="en" class="nivo-lightbox-notouch">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>EventUp - Event and Conference Template</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets_conference/css/bootstrap.min.css') }}">
        {{-- <link rel="stylesheet" type="text/css" href="assets_conference/css/line-icons.css"> --}}
        <link rel="stylesheet" type="text/css" href="{{ asset('assets_conference/css/slicknav.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets_conference/css/nivo-lightbox.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets_conference/css/color-switcher.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets_conference/css/animate.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets_conference/css/main.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets_conference/css/responsive.css') }}">
        @stack('theme')
        <link rel="stylesheet" href="{{ asset('assets_conference/css/color-switcher.css') }}" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <header id="header-wrap">
            <nav class="navbar navbar-expand-lg bg-inverse fixed-top scrolling-navbar top-nav-collapse">
                <div class="container">
                    <div class="theme-header clearfix">
                        {{-- <a href="javascript:;" class="navbar-brand"><img src="assets_conference/logo.png" alt=""></a> --}}
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="lni-menu"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarCollapse">
                            <ul class="navbar-nav mr-auto w-100 justify-content-end">
                                <li class="nav-item active">
                                    <a class="nav-link" href="javascript:;">
                                        Sobre o evento
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#inscricoes">
                                        Inscrições
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#local">
                                        Local
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#contato">
                                        Contato
                                    </a>
                                </li>
                                <li class="mt-2">
                                    @if(!Auth::user())
                                        {{-- <li><a class="nav-link nav-login" href="{{route('login')}}"><i class="fa-regular fa-circle-user"></i>&nbsp;Entrar</a></li> --}}
                                        <a class="btn btn-common sub-btn" href="{{route('login')}}">
                                            Entrar
                                        </a>
                                        @else
                                        <a class="btn btn-common sub-btn" href="javascript:;">
                                            {{ Auth::user()->name }}
                                        </a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- <div class="mobile-menu" data-logo="assets/img/logo.png"> --}}
                <div class="mobile-menu">
                    <div class="slicknav_menu">
                        {{-- <div class="slicknav_brand"><a href="javascript:;"><img src="assets_conference/logo.png" class="img-responsive" alt="logo"></a></div> --}}
                        <a href="javascript:;" aria-haspopup="true" role="button" tabindex="0" class="slicknav_btn slicknav_collapsed" style=""><span class="slicknav_menutxt"></span><span class="slicknav_icon slicknav_no-text"><span class="slicknav_icon-bar"></span><span class="slicknav_icon-bar"></span><span class="slicknav_icon-bar"></span></span></a>
                        <div class="slicknav_nav slicknav_hidden" aria-hidden="true" role="menu" style="display: none;">
                            <ul class="navbar-nav mr-auto w-100 justify-content-end">
                                <li class="nav-item active">
                                    <a class="nav-link" href="javascript:;" role="menuitem">
                                        Sobre o evento
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#inscricoes" role="menuitem">
                                        Inscrições
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#local" role="menuitem">
                                        Local
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#contato" role="menuitem">
                                        Contato
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript:;" role="menuitem">
                                        Entrar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        {{$slot}}
        <footer>
            <div class="container">
                <div class="row justify-content-center gy-4">
                    <div class="col-lg-5 col-md-12">
                        <div class="footer-logo">
                            <img src="{{ asset('assets/img/logo_principal.png') }}" alt="">
                        </div>
                        <div class="social-icons-footer">
                            <ul>
                                <li class="facebook"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="lni-facebook-filled"></i></a></li>
                                <li class="twitter"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="lni-twitter-filled"></i></a></li>
                                <li class="linkedin"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="lni-linkedin-original"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-6 footer-links">
                        <h4>Links úteis</h4>
                        <ul>
                          <li><i class="bi bi-chevron-right"></i> <a href="/">Home</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="/#planos">Planos e Preços</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="/eventos">Eventos</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="/#faq">Perguntas frequentes</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="/contato">Contato</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="/politica">Política de privacidade</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="/termos">Termos de uso</a></li>
                        </ul>
                      </div>
            
                      <div class="col-lg-2 col-6 footer-links">
                        <h4>Nossos serviços</h4>
                        <ul>
                          <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
                          <li><i class="bi bi-chevron-right"></i> <a href="#">Graphic Design</a></li>
                        </ul>
                      </div>
            
                      <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                        <h4>Contact Us</h4>
                        <p>
                          A108 Adam Street <br>
                          New York, NY 535022<br>
                          United States <br><br>
                          <strong>Phone:</strong> +1 5589 55488 55<br>
                          <strong>Email:</strong> info@example.com<br>
                        </p>
            
                      </div>
                </div>
            </div>
        </footer>
        <a href="#" class="back-to-top" style="display: block;">
            <i class="fa-solid fa-arrow-up"></i>
        </a>
    </body>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.2/js/OverlayScrollbars.min.js" integrity="sha512-5UqQ4jRiUk3pxl9wZxAtep5wCxqcoo6Yu4FI5ufygoOMV2I2/lOtH1YjKdt3FcQ9uhcKFJapG0HAQ0oTC5LnOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.back-to-top').on('click',function(event){
            event.preventDefault();
            $('html, body').animate({scrollTop:0},600);
            return false;
        });
    </script>
    @stack('footer')
</html>