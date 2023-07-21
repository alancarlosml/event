<!DOCTYPE html>
<!-- saved from url=(0063)javascript:; -->
<html lang="en" class="nivo-lightbox-notouch">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@stack('event_name') - Ticket DZ6</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
          integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    {!! RecaptchaV3::initJs() !!}
    @stack('head')
</head>

<body data-spy="scroll" data-target="#navbarCollapse">
    <header id="header-wrap">
        <nav class="navbar navbar-expand-lg bg-inverse fixed-top scrolling-navbar top-nav-collapse">
            <div class="container">
                <div class="theme-header clearfix">
                    <a href="/{{explode('/',url()->current())[3]}}/" class="navbar-brand">
                        <img src="{{ asset('assets/img/logo_principal.png') }}" alt=""></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="lni-menu"></i>
                    </button>
                    <div class="collapse navbar-collapse navbarCollapse" id="navbarCollapse">
                        <ul class="navbar-nav mr-auto w-100 justify-content-end">
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#home">
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#sobre">
                                    Sobre o evento
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#inscricoes">
                                    Inscrições
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#local">
                                    Local
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#contato">
                                    Contato
                                </a>
                            </li>
                            <li class="nav-item mt-2">
                                <div class="d-flex">
                                    <ul>
                                        @if (!Auth::user())
                                            <li><a class="btn btn-common sub-btn" href="{{ route('login') }}">Entrar</a>
                                            </li>
                                        @else
                                            <li class="nav-item dropdown">
                                                <a class="btn btn-common sub-btn" data-toggle="dropdown"
                                                   href="javascript:;">
                                                    {{ Auth::user()->name }}
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                    <a href="{{ route('event_home.my_registrations') }}"
                                                       class="dropdown-item">
                                                        Minhas inscrições
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                                       onclick="event.preventDefault();
                                                      document.getElementById('logout-form').submit();">
                                                        Sair
                                                    </a>
                                                    <form id="logout-form" action="{{ route('logout') }}"
                                                          method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                            {{-- <li class="mt-2">
                                    @if (!Auth::user())
                                        <a class="btn btn-common sub-btn" href="{{route('login')}}">
                                            Entrar
                                        </a>
                                        @else
                                        <a class="btn btn-common sub-btn" href="javascript:;">
                                            {{ Auth::user()->name }}
                                        </a>
                                    @endif
                                </li> --}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mobile-menu">
                <div class="slicknav_menu">
                    <div class="slicknav_brand">
                        <a href="/"><img src="assets/img/logo_principal.png" class="img-responsive" alt="logo"></a>
                    </div>
                    <a href="javascript:;" aria-haspopup="true" role="button" tabindex="0" class="slicknav_btn slicknav_collapsed" style="">
                        <span class="slicknav_menutxt"></span><span class="slicknav_icon slicknav_no-text">
                        <span class="slicknav_icon-bar"></span><span class="slicknav_icon-bar"></span>
                        <span class="slicknav_icon-bar"></span></span>
                    </a>
                    <div class="navbarCollapse slicknav_nav slicknav_hidden none" aria-hidden="true" role="menu" id="navbarCollapseMobile">
                        <ul class="navbar-nav mr-auto w-100 justify-content-end">
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#home" role="menuitem">
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#sobre" role="menuitem">
                                    Sobre o evento
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#inscricoes" role="menuitem">
                                    Inscrições
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#local" role="menuitem">
                                    Local
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{explode('/',url()->current())[3]}}/#contato" role="menuitem">
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
    {{ $slot }}
    <footer>
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="col-lg-3 col-md-12 text-left">
                    <div class="footer-logo">
                        <a href="/" class="logo d-flex align-items-center">
                            <img src="{{ asset('assets/img/logo_principal_sf.png') }}" alt="">
                        </a>
                    </div>
                    <p>Sua nova plataforma de criação e gerenciamento de eventos. </p>
                    <div class="social-icons-footer">
                        <ul>
                            <li class="facebook"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="fa-brands fa-facebook"></i></a></li>
                            <li class="twitter"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="fa-brands fa-twitter"></i></a></li>
                            <li class="instagram"><a target="_blank" href="https://preview.uideck.com/items/event-up/multi-page/3"><i class="fa-brands fa-instagram"></i></a></li>
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
                    <h4>Contato</h4>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.2/js/OverlayScrollbars.min.js"
        integrity="sha512-5UqQ4jRiUk3pxl9wZxAtep5wCxqcoo6Yu4FI5ufygoOMV2I2/lOtH1YjKdt3FcQ9uhcKFJapG0HAQ0oTC5LnOw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('.back-to-top').on('click', function(event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, 600);
        return false;
    });
    $('.slicknav_btn').on('click', function(event) {
        event.preventDefault();
        $('.slicknav_nav').toggle();
    });

    (function() {
        "use strict";

        const select = (el, all = false) => {
            el = el.trim()
            if (all) {
                return [...document.querySelectorAll(el)]
            } else {
                return document.querySelector(el)
            }
        }

        /**
         * Easy event listener function
         */
        const on = (type, el, listener, all = false) => {
            let selectEl = select(el, all)
            if (selectEl) {
                if (all) {
                    selectEl.forEach(e => e.addEventListener(type, listener))
                } else {
                    selectEl.addEventListener(type, listener)
                }
            }
        }

        /**
         * Easy on scroll event listener 
         */
        const onscroll = (el, listener) => {
            el.addEventListener('scroll', listener);
        }

        /**
         * Navbar links active state on scroll
         */
        let navbarlinks = select('.navbarCollapse .nav-link', true);
        const navbarlinksActive = () => {
            let position = window.scrollY + 200;
            navbarlinks.forEach(navbarlink => {
                // console.log(navbarlinks);
                if (!navbarlink.hash) return
                let section = select(navbarlink.hash)
                if (!section) return
                if (position >= section.offsetTop && position <= (section.offsetTop + section
                        .offsetHeight)) {
                    // console.log(navbarlink.parentElement.classList);
                    navbarlink.parentElement.classList.add('active')
                } else {
                    navbarlink.parentElement.classList.remove('active')
                }
            })
        }
        window.addEventListener('load', navbarlinksActive)
        onscroll(document, navbarlinksActive)

        /**
         * Scrolls to an element with header offset
         */
        const scrollto = (el) => {
            let header = select('#header-wrap')
            let offset = header.offsetHeight

            let elementPos = select(el).offsetTop
            window.scrollTo({
                top: elementPos - offset,
                behavior: 'smooth'
            })
        }

        on('click', '.nav-link', function(e) {
            if (select(this.hash)) {
                e.preventDefault()
                scrollto(this.hash)
            }
        }, true)

        /**
         * Scroll with ofset on page load with hash links in the url
         */
        window.addEventListener('load', () => {
            if (window.location.hash) {
                if (select(window.location.hash)) {
                    scrollto(window.location.hash)
                }
            }
        });




    })()

    // $(document).ready(function() {
    //     $('body').scrollspy({ target: '#navbarCollapse' })
    // });
</script>
@stack('footer')

</html>
