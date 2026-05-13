<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="Plataforma de eventos moderna e acessível" name="description"> <!-- Melhorado para SEO -->
    <meta content="eventos, ingressos, gerenciamento" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Ticket DZ6</title>

    <!-- Favicons (adicionado mais tamanhos para melhor compatibilidade) -->
    <link rel="icon" href="{{ asset('assets/img/favicon/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/img/favicon/site.webmanifest') }}">

    <!-- Google Fonts (adicionado Inter para look mais moderno) -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files (padronizado Bootstrap 5.3.3) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />

    <!-- Template Main CSS File (adicionado CSS variables para modernização) -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/frontend-unified.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff; /* Cor primária moderna */
            --secondary-color: #6c757d;
            --bg-light: #f8f9fa;
            --shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombras suaves */
            --transition: all 0.3s ease; /* Transições suaves */
        }
        body { 
            font-family: 'Manrope', sans-serif; 
            padding-top: 70px; /* Espaço para header fixo */
        }
        .info-box { transition: var(--transition); box-shadow: var(--shadow); border-radius: 8px; }
        .info-box:hover { transform: translateY(-5px); }
        
        /* Melhorias adicionais para o menu mobile */
        @media (max-width: 991px) {
            body {
                padding-top: 60px;
            }
            
            /* Garante que o header não se mova */
            .header .container-fluid,
            .header .container-xl {
                position: relative;
                min-height: 60px;
            }
            
            /* Fixa a logo e o botão hambúrguer */
            .modern-navbar {
                position: relative;
                width: 100%;
            }
            
            .modern-navbar .navbar-collapse {
                max-height: calc(100vh - 100px);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            /* Smooth scroll para mobile */
            .modern-navbar .navbar-collapse::-webkit-scrollbar {
                width: 4px;
            }
            
            .modern-navbar .navbar-collapse::-webkit-scrollbar-track {
                background: transparent;
            }
            
            .modern-navbar .navbar-collapse::-webkit-scrollbar-thumb {
                background: rgba(24, 92, 164, 0.2);
                border-radius: 2px;
            }
        }
        
        /* Melhora a acessibilidade do dropdown */
        .modern-dropdown .dropdown-item:focus {
            background-color: rgba(24, 92, 164, 0.1);
            outline: 2px solid rgba(24, 92, 164, 0.3);
            outline-offset: -2px;
        }

        .locale-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .locale-flag {
            font-size: 1rem;
            line-height: 1;
        }

        .locale-code {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        body.embedded-mode {
            padding-top: 0;
            background: transparent;
        }

        body.embedded-mode #header,
        body.embedded-mode #footer,
        body.embedded-mode .breadcrumbs {
            display: none !important;
        }

        body.embedded-mode #main,
        body.embedded-mode .inner-page,
        body.embedded-mode .inner-page > .container {
            margin: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        body.embedded-mode .inner-page > .container {
            max-width: none;
            width: 100%;
        }

        /* Correção para botões do menu */
        .navbar-nav .nav-link.btn-create-event {
            background-color: var(--primary-color);
            color: #ffffff !important;
            border-radius: 8px;
            padding: 8px 18px !important;
            margin: 0 8px;
            font-weight: 600;
        }
        .navbar-nav .nav-link.btn-create-event:hover {
            background-color: #0056b3 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(0,123,255,0.3);
        }

        .navbar-nav .nav-link.btn-login {
            border: 2px solid var(--primary-color);
            color: var(--primary-color) !important;
            border-radius: 8px;
            padding: 6px 18px !important;
            font-weight: 600;
            background-color: transparent;
        }
        .navbar-nav .nav-link.btn-login:hover {
            background-color: var(--primary-color) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(0,123,255,0.2);
        }
    </style>

    <!-- Async Feedback CSS -->
    <link href="{{ asset('assets_admin/css/async-feedback.css') }}" rel="stylesheet">

    @stack('head')
</head>

<body class="{{ request()->boolean('embedded') ? 'embedded-mode' : '' }}">
    @php
        $currentLocale = app()->getLocale();
        $supportedLocales = [
            'pt' => 'Português',
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
        ];
        $localeMeta = [
            'pt' => ['code' => 'PT', 'flag' => 'br'],
            'en' => ['code' => 'EN', 'flag' => 'gb'],
            'es' => ['code' => 'ES', 'flag' => 'es'],
            'fr' => ['code' => 'FR', 'flag' => 'fr'],
        ];
        $localePrefix = '/' . $currentLocale;
    @endphp

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top" role="banner">
        <div class="container-fluid container-xl">
            <nav class="navbar navbar-expand-lg navbar-light modern-navbar">
                <a href="{{ $localePrefix }}" class="navbar-brand logo d-flex align-items-center">
                    <img src="{{ asset('assets/img/logo_principal.png') }}" alt="Ticket DZ6">
                </a>

                <button class="navbar-toggler modern-nav-toggle" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
                        aria-label="{{ __('site.nav.toggle_navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                    <span class="nav-toggle-text">{{ __('site.nav.menu') }}</span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $localePrefix }}/eventos">
                                <i class="bi bi-calendar-event d-lg-none me-2"></i>
                                <span>{{ __('site.nav.events') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $localePrefix }}/contato">
                                <i class="bi bi-envelope d-lg-none me-2"></i>
                                <span>{{ __('site.nav.contact') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-create-event" href="{{ route('event_home.create_event_link') }}">
                                <i class="bi bi-plus-circle me-1"></i>
                                <span>{{ __('site.nav.create_event') }}</span>
                            </a>
                        </li>
                        @if (!Auth::user())
                            <li class="nav-item">
                                <a class="nav-link btn-login" href="{{ route('login') }}">
                                    <i class="fa-regular fa-circle-user me-1"></i>
                                    <span>{{ __('site.nav.login') }}</span>
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle user-menu-toggle" href="#" id="userDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-circle-user me-1"></i>
                                    <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                                    <span class="d-lg-none">{{ __('site.nav.my_account') }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow modern-dropdown" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('event_home.my_events') }}">
                                            <i class="bi bi-calendar-check me-2"></i>{{ __('site.account.my_events') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('event_home.my_registrations') }}">
                                            <i class="bi bi-ticket-perforated me-2"></i>{{ __('site.account.my_registrations') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('event_home.profile') }}">
                                            <i class="bi bi-person me-2"></i>{{ __('site.account.profile') }}
                                        </a>
                                    </li>
                                    @if(Auth::user()->hasRole('super_admin'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('event_home.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>{{ __('site.account.control_panel') }}
                                        </a>
                                    </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>{{ __('site.account.logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="languageDropdown"
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="locale-chip">
                                    <span class="locale-flag fi fi-{{ $localeMeta[$currentLocale]['flag'] ?? 'un' }}" aria-hidden="true"></span>
                                    <span class="locale-code">{{ $localeMeta[$currentLocale]['code'] ?? strtoupper($currentLocale) }}</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow modern-dropdown" aria-labelledby="languageDropdown">
                                @foreach ($supportedLocales as $localeCode => $localeName)
                                    <li>
                                        <a class="dropdown-item {{ $localeCode === $currentLocale ? 'active' : '' }}"
                                           href="{{ route('locale.switch', ['locale' => $localeCode, 'redirect' => request()->getRequestUri()]) }}">
                                            <span class="locale-chip">
                                                <span class="locale-flag fi fi-{{ $localeMeta[$localeCode]['flag'] ?? 'un' }}" aria-hidden="true"></span>
                                                <span class="locale-code">{{ $localeMeta[$localeCode]['code'] ?? strtoupper($localeCode) }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header><!-- End Header -->

    <!-- ======= Hero Section ======= -->
    {{ $slot }}

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer" role="contentinfo">

        <div class="footer-top">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-5 col-md-12 footer-info">
                        <a href="{{ $localePrefix }}" class="logo d-flex align-items-center">
                            <img src="{{ asset('assets/img/logo_principal.png') }}" alt="">
                        </a>
                        <p>{{ __('site.footer.tagline') }}</p>
                        <div class="social-links mt-3">
                            <a href="https://www.instagram.com/ticketdz6?igsh=OHZub25iZHd0d3A4" class="instagram"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-6 footer-links">
                        <h4>{{ __('site.footer.useful_links') }}</h4>
                        <ul>
                            <li><i class="bi bi-chevron-right"></i> <a href="{{ $localePrefix }}">{{ __('site.nav.home') }}</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="{{ $localePrefix }}/#planos">{{ __('site.footer.pricing') }}</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="{{ $localePrefix }}/eventos">{{ __('site.nav.events') }}</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="{{ $localePrefix }}/#faq">{{ __('site.footer.faq') }}</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="{{ $localePrefix }}/contato">{{ __('site.nav.contact') }}</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="/painel/cadastrar">{{ __('site.footer.signup') }}</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="{{ $localePrefix }}/politica">{{ __('site.footer.privacy_policy') }}</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="{{ $localePrefix }}/termos">{{ __('site.footer.terms') }}</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-6 footer-links">
                        <h4>{{ __('site.footer.our_services') }}</h4>
                        <ul>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Gerenciamento de Eventos</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Ingressos</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Certificados</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                        <h4>{{ __('site.footer.contact_title') }}</h4>
                        <p>
                            {{ __('site.footer.address_line_1') }}<br>
                            {{ __('site.footer.address_line_2') }}<br>
                            {{ __('site.footer.address_line_3') }}<br><br>
                            <strong>{{ __('site.footer.phone_label') }}:</strong> {{ __('site.footer.phone_value') }}<br>
                            <strong>{{ __('site.footer.email_label') }}:</strong> {{ __('site.footer.email_value') }}<br>
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Ticket DZ6</span></strong>. {{ __('site.footer.rights_reserved') }}
            </div>
            <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center" aria-label="{{ __('site.accessibility.back_to_top') }}"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files (atualizado jQuery para 3.7.1) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Async Feedback JS -->
    <script src="{{ asset('assets_admin/js/async-feedback.js') }}"></script>

    @stack('footer')
</body>
</html>