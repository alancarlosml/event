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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files (padronizado Bootstrap 5.3.3) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Template Main CSS File (adicionado CSS variables para modernização) -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff; /* Cor primária moderna */
            --secondary-color: #6c757d;
            --bg-light: #f8f9fa;
            --shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombras suaves */
            --transition: all 0.3s ease; /* Transições suaves */
        }
        body { 
            font-family: 'Inter', sans-serif; 
            padding-top: 70px; /* Espaço para header fixo */
        }
        img { loading: lazy; } /* Lazy loading global */
        .card, .info-box { transition: var(--transition); box-shadow: var(--shadow); border-radius: 8px; } /* Moderno cards */
        .card:hover, .info-box:hover { transform: translateY(-5px); }
        
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
    </style>

    <!-- Async Feedback CSS -->
    <link href="{{ asset('assets_admin/css/async-feedback.css') }}" rel="stylesheet">

    @stack('head')
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top" role="banner">
        <div class="container-fluid container-xl">
            <nav class="navbar navbar-expand-lg navbar-light modern-navbar">
                <a href="/" class="navbar-brand logo d-flex align-items-center">
                    <img src="{{ asset('assets/img/logo_principal.png') }}" alt="Ticket DZ6">
                </a>

                <button class="navbar-toggler modern-nav-toggle" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    <span class="nav-toggle-text">Menu</span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="/eventos">
                                <i class="bi bi-calendar-event d-lg-none me-2"></i>
                                <span>Eventos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contato">
                                <i class="bi bi-envelope d-lg-none me-2"></i>
                                <span>Contato</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-create-event" href="{{ route('event_home.create_event_link') }}">
                                <i class="bi bi-plus-circle me-1"></i>
                                <span>Criar evento</span>
                            </a>
                        </li>
                        @if (!Auth::user())
                            <li class="nav-item">
                                <a class="nav-link btn-login" href="{{ route('login') }}">
                                    <i class="fa-regular fa-circle-user me-1"></i>
                                    <span>Entrar</span>
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle user-menu-toggle" href="#" id="userDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-circle-user me-1"></i>
                                    <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                                    <span class="d-lg-none">Minha conta</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow modern-dropdown" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('event_home.my_events') }}">
                                            <i class="bi bi-calendar-check me-2"></i>Meus eventos
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('event_home.my_registrations') }}">
                                            <i class="bi bi-ticket-perforated me-2"></i>Minhas inscrições
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('event_home.profile') }}">
                                            <i class="bi bi-person me-2"></i>Perfil
                                        </a>
                                    </li>
                                    @if(Auth::user()->hasRole('super_admin'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('event_home.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Painel de controle
                                        </a>
                                    </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>Sair
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
        </div>
    </header><!-- End Header -->

    <!-- ======= Hero Section ======= -->
    {{ $slot }}

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer" role="contentinfo">

        {{-- <div class="footer-newsletter">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                  <h4>Our Newsletter</h4>
                  <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
                </div>
                <div class="col-lg-6">
                  <form action="" method="post">
                    <input type="email" name="email"><input type="submit" value="Subscribe">
                  </form>
                </div>
              </div>
            </div>
          </div> --}}

        <div class="footer-top">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-5 col-md-12 footer-info">
                        <a href="/" class="logo d-flex align-items-center">
                            <img src="{{ asset('assets/img/logo_principal.png') }}" alt="">
                        </a>
                        <p>Sua nova plataforma de criação e gerenciamento de eventos. </p>
                        <div class="social-links mt-3">
                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
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
                            <li><i class="bi bi-chevron-right"></i> <a href="/painel/cadastrar">Cadastro</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="/politica">Política de privacidade</a>
                            </li>
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
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Ticket DZ6</span></strong>. Todos os direitos reservados
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/flexstart-bootstrap-startup-template/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center" aria-label="Voltar ao topo"><i class="bi bi-arrow-up-short"></i></a>

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
