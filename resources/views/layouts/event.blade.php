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
                                    <a class="nav-link" href="#contato">
                                        Contato
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link btn btn-common" href="javacript:;">
                                        Entrar
                                    </a>
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
                                    <a class="nav-link" href="#contato" role="menuitem">
                                        Contato
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript;;" role="menuitem">
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
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-12 col-xs-12">
                        <div class="subscribe-inner wow fadeInDown animated" data-wow-delay="0.3s" style="visibility: visible;-webkit-animation-delay: 0.3s; -moz-animation-delay: 0.3s; animation-delay: 0.3s;">
                            <h2 class="subscribe-title">To Get Nearly Updates</h2>
                            <form class="text-center form-inline">
                                <input class="mb-20 form-control" name="email" placeholder="Enter Your Email Here">
                                <button type="submit" class="btn btn-common sub-btn" data-style="zoom-in" data-spinner-size="30" name="submit" id="submit">
                                <span class="ladda-label"><i class="lni-check-box"></i> Subscribe</span>
                                </button>
                            </form>
                        </div>
                        <div class="footer-logo">
                            {{-- <img src="assets_conference/logo.png" alt=""> --}}
                        </div>
                        <div class="social-icons-footer">
                            <ul>
                                <li class="facebook"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="lni-facebook-filled"></i></a></li>
                                <li class="twitter"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="lni-twitter-filled"></i></a></li>
                                <li class="linkedin"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="lni-linkedin-original"></i></a></li>
                                <li class="google"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="lni-google"></i></a></li>
                                <li class="pinterest"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="lni-pinterest"></i></a></li>
                            </ul>
                        </div>
                        <div class="site-info">
                            <p>Designed and Developed by <a href="http://uideck.com/" rel="nofollow">UIdeck</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <a href="#" class="back-to-top" style="display: block;">
            <i class="fa-solid fa-arrow-up"></i>
        </a>
        <div id="preloader" style="display: none;">
            <div class="sk-circle">
                <div class="sk-circle1 sk-child"></div>
                <div class="sk-circle2 sk-child"></div>
                <div class="sk-circle3 sk-child"></div>
                <div class="sk-circle4 sk-child"></div>
                <div class="sk-circle5 sk-child"></div>
                <div class="sk-circle6 sk-child"></div>
                <div class="sk-circle7 sk-child"></div>
                <div class="sk-circle8 sk-child"></div>
                <div class="sk-circle9 sk-child"></div>
                <div class="sk-circle10 sk-child"></div>
                <div class="sk-circle11 sk-child"></div>
                <div class="sk-circle12 sk-child"></div>
            </div>
        </div>
        {{-- <script src="assets_conference/jquery-min.js.download"></script>
        <script src="assets_conference/popper.min.js.download"></script>
        <script src="assets_conference/bootstrap.min.js.download"></script>
        <script src="assets_conference/color-switcher.js.download"></script>
        <script src="assets_conference/jquery.countdown.min.js.download"></script>
        <script src="assets_conference/waypoints.min.js.download"></script>
        <script src="assets_conference/jquery.counterup.min.js.download"></script>
        <script src="assets_conference/jquery.nav.js.download"></script>
        <script src="assets_conference/jquery.easing.min.js.download"></script>
        <script src="assets_conference/wow.js.download"></script>
        <script src="assets_conference/jquery.slicknav.js.download"></script>
        <script src="assets_conference/nivo-lightbox.js.download"></script>
        <script src="assets_conference/video.js.download"></script>
        <script src="assets_conference/main.js.download"></script>
        <script src="assets_conference/form-validator.min.js.download"></script>
        <script src="assets_conference/contact-form-script.min.js.download"></script> --}}
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