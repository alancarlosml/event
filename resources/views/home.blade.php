<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="" name="description">
    <meta content="" name="keywords">

    <title>Laravel</title>

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
          rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
          integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        #start_event i {
            color: #4154f1;
            font-size: 2em;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

            <a href="index.html" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span>FlexStart</span>
            </a>

            <nav id="navbar" class="navbar">
                <ul>
                    <li class="dropdown"><a href="#"><span>Serviços</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="#">Drop Down 1</a></li>
                            <li><a href="#">Drop Down 2</a></li>
                            <li><a href="#">Drop Down 3</a></li>
                            <li><a href="#">Drop Down 4</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link scrollto active" href="#planos">Planos e Preços</a></li>
                    <li><a class="nav-link" href="/eventos">Eventos</a></li>
                    <li><a class="nav-link" href="/contato">Contato</a></li>
                    <li><a class="getstarted" href="/login">Criar evento</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="hero d-flex align-items-center">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center">
                    <h1 data-aos="fade-up">Agora ficou muito mais fácil organizar seus próprios eventos!</h1>
                    <h2 data-aos="fade-up" data-aos-delay="400">Crie seu própio evento e comece a lucrar com ele agora
                        mesmo.</h2>
                    <div data-aos="fade-up" data-aos-delay="600">
                        <div class="text-center text-lg-start">
                            <a href="/cadastro"
                               class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                                <span>Começar agora!</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
                    <img src="assets/img/hero-img.png" class="img-fluid" alt="">
                </div>
            </div>
        </div>

    </section><!-- End Hero -->

    <main id="main">

        <!-- ======= Values Section ======= -->
        <section id="values" class="values">

            <div class="container" data-aos="fade-up">
                <header class="section-header">
                    <p>Comece a vender em 5 minutos!</p>
                    <h2 class="mt-3">Uma plataforma de eventos para te ajudar a não perder tempo e focar no que
                        importa</h2>
                </header>
                <div class="row justify-content-center" id="start_event">
                    <div class="col-lg-2" data-aos="fade-up" data-aos-delay="200">
                        <div class="box">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <h5>Faça seu cadastro</h5>
                        </div>
                    </div>

                    <div class="col-lg-2 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="400">
                        <div class="box">
                            <i class="fa-solid fa-calendar-check"></i>
                            <h5>Crie seu evento</h5>
                        </div>
                    </div>

                    <div class="col-lg-2 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="600">
                        <div class="box">
                            <i class="fa-solid fa-users-gear"></i>
                            <h5>Gerencia suas inscrições</h5>
                        </div>
                    </div>

                    <div class="col-lg-2" data-aos="fade-up" data-aos-delay="200">
                        <div class="box">
                            <i class="fa-solid fa-bullhorn"></i>
                            <h5>Publique seu evento</h5>
                        </div>
                    </div>

                    <div class="col-lg-2 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="400">
                        <div class="box">
                            <i class="fa-solid fa-dollar-sign"></i>
                            <h5>Começa a faturar</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Values Section -->

        <!-- ======= Features Section ======= -->
        <section id="features" class="features">

            <div class="container" data-aos="fade-up">

                <header class="section-header">
                    <h2>Features</h2>
                    <p>Laboriosam et omnis fuga quis dolor direda fara</p>
                </header>

                <div class="row">

                    <div class="col-lg-6">
                        <img src="assets/img/features.png" class="img-fluid" alt="">
                    </div>

                    <div class="col-lg-6 mt-5 mt-lg-0 d-flex">
                        <div class="row align-self-center gy-4">

                            <div class="col-md-6" data-aos="zoom-out" data-aos-delay="200">
                                <div class="feature-box d-flex align-items-center">
                                    <i class="bi bi-check"></i>
                                    <h3>Eos aspernatur rem</h3>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="zoom-out" data-aos-delay="300">
                                <div class="feature-box d-flex align-items-center">
                                    <i class="bi bi-check"></i>
                                    <h3>Facilis neque ipsa</h3>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="zoom-out" data-aos-delay="400">
                                <div class="feature-box d-flex align-items-center">
                                    <i class="bi bi-check"></i>
                                    <h3>Volup amet voluptas</h3>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="zoom-out" data-aos-delay="500">
                                <div class="feature-box d-flex align-items-center">
                                    <i class="bi bi-check"></i>
                                    <h3>Rerum omnis sint</h3>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="zoom-out" data-aos-delay="600">
                                <div class="feature-box d-flex align-items-center">
                                    <i class="bi bi-check"></i>
                                    <h3>Alias possimus</h3>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="zoom-out" data-aos-delay="700">
                                <div class="feature-box d-flex align-items-center">
                                    <i class="bi bi-check"></i>
                                    <h3>Repellendus mollitia</h3>
                                </div>
                            </div>

                        </div>
                    </div>

                </div> <!-- / row -->

                <!-- Feature Tabs -->
                <div class="row feture-tabs" data-aos="fade-up">
                    <div class="col-lg-6">
                        <h3>Neque officiis dolore maiores et exercitationem quae est seda lidera pat claero</h3>

                        <!-- Tabs -->
                        <ul class="nav nav-pills mb-3">
                            <li>
                                <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Saepe fuga</a>
                            </li>
                            <li>
                                <a class="nav-link" data-bs-toggle="pill" href="#tab2">Voluptates</a>
                            </li>
                            <li>
                                <a class="nav-link" data-bs-toggle="pill" href="#tab3">Corrupti</a>
                            </li>
                        </ul><!-- End Tabs -->

                        <!-- Tab Content -->
                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="tab1">
                                <p>Consequuntur inventore voluptates consequatur aut vel et. Eos doloribus expedita.
                                    Sapiente atque consequatur minima nihil quae aspernatur quo suscipit voluptatem.</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check2"></i>
                                    <h4>Repudiandae rerum velit modi et officia quasi facilis</h4>
                                </div>
                                <p>Laborum omnis voluptates voluptas qui sit aliquam blanditiis. Sapiente minima commodi
                                    dolorum non eveniet magni quaerat nemo et.</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check2"></i>
                                    <h4>Incidunt non veritatis illum ea ut nisi</h4>
                                </div>
                                <p>Non quod totam minus repellendus autem sint velit. Rerum debitis facere soluta
                                    tenetur. Iure molestiae assumenda sunt qui inventore eligendi voluptates nisi at.
                                    Dolorem quo tempora. Quia et perferendis.</p>
                            </div><!-- End Tab 1 Content -->

                            <div class="tab-pane fade show" id="tab2">
                                <p>Consequuntur inventore voluptates consequatur aut vel et. Eos doloribus expedita.
                                    Sapiente atque consequatur minima nihil quae aspernatur quo suscipit voluptatem.</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check2"></i>
                                    <h4>Repudiandae rerum velit modi et officia quasi facilis</h4>
                                </div>
                                <p>Laborum omnis voluptates voluptas qui sit aliquam blanditiis. Sapiente minima commodi
                                    dolorum non eveniet magni quaerat nemo et.</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check2"></i>
                                    <h4>Incidunt non veritatis illum ea ut nisi</h4>
                                </div>
                                <p>Non quod totam minus repellendus autem sint velit. Rerum debitis facere soluta
                                    tenetur. Iure molestiae assumenda sunt qui inventore eligendi voluptates nisi at.
                                    Dolorem quo tempora. Quia et perferendis.</p>
                            </div><!-- End Tab 2 Content -->

                            <div class="tab-pane fade show" id="tab3">
                                <p>Consequuntur inventore voluptates consequatur aut vel et. Eos doloribus expedita.
                                    Sapiente atque consequatur minima nihil quae aspernatur quo suscipit voluptatem.</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check2"></i>
                                    <h4>Repudiandae rerum velit modi et officia quasi facilis</h4>
                                </div>
                                <p>Laborum omnis voluptates voluptas qui sit aliquam blanditiis. Sapiente minima commodi
                                    dolorum non eveniet magni quaerat nemo et.</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check2"></i>
                                    <h4>Incidunt non veritatis illum ea ut nisi</h4>
                                </div>
                                <p>Non quod totam minus repellendus autem sint velit. Rerum debitis facere soluta
                                    tenetur. Iure molestiae assumenda sunt qui inventore eligendi voluptates nisi at.
                                    Dolorem quo tempora. Quia et perferendis.</p>
                            </div><!-- End Tab 3 Content -->

                        </div>

                    </div>

                    <div class="col-lg-6">
                        <img src="assets/img/features-2.png" class="img-fluid" alt="">
                    </div>

                </div><!-- End Feature Tabs -->

                <!-- Feature Icons -->
                <div class="row feature-icons" data-aos="fade-up">
                    <h3>Ratione mollitia eos ab laudantium rerum beatae quo</h3>

                    <div class="row">
                        <div class="col-xl-4 text-center" data-aos="fade-right" data-aos-delay="100">
                            <img src="assets/img/features-3.png" class="img-fluid p-4" alt="">
                        </div>
                        <div class="col-xl-8 d-flex content">
                            <div class="row align-self-center gy-4">

                                <div class="col-md-6 icon-box" data-aos="fade-up">
                                    <i class="ri-line-chart-line"></i>
                                    <div>
                                        <h4>Corporis voluptates sit</h4>
                                        <p>Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut
                                            aliquip</p>
                                    </div>
                                </div>

                                <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
                                    <i class="ri-stack-line"></i>
                                    <div>
                                        <h4>Ullamco laboris nisi</h4>
                                        <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                                            deserunt</p>
                                    </div>
                                </div>

                                <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
                                    <i class="ri-brush-4-line"></i>
                                    <div>
                                        <h4>Labore consequatur</h4>
                                        <p>Aut suscipit aut cum nemo deleniti aut omnis. Doloribus ut maiores omnis
                                            facere</p>
                                    </div>
                                </div>

                                <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
                                    <i class="ri-magic-line"></i>
                                    <div>
                                        <h4>Beatae veritatis</h4>
                                        <p>Expedita veritatis consequuntur nihil tempore laudantium vitae denat pacta
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="400">
                                    <i class="ri-command-line"></i>
                                    <div>
                                        <h4>Molestiae dolor</h4>
                                        <p>Et fuga et deserunt et enim. Dolorem architecto ratione tensa raptor marte
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="500">
                                    <i class="ri-radar-line"></i>
                                    <div>
                                        <h4>Explicabo consectetur</h4>
                                        <p>Est autem dicta beatae suscipit. Sint veritatis et sit quasi ab aut inventore
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Feature Icons -->
            </div>
        </section><!-- End Features Section -->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container" data-aos="fade-up">
                <header class="section-header">
                    <p>Eventos</p>
                </header>
                <div class="row gy-4">
                    @foreach ($categories as $category)
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                            <div class="service-box blue">
                                <h3>{{ $category->description }}</h3>
                                <a href="/{{ $category->slug }}" class="read-more"><span>Saiba mais</span> <i
                                       class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section><!-- End Services Section -->

        <!-- ======= Counts Section ======= -->
        <section id="counts" class="counts" style="background: #4154f1;">
            <div class="container" data-aos="fade-up">

                <div class="row gy-4">

                    <div class="col-lg-3 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-emoji-smile"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="232"
                                      data-purecounter-duration="1" class="purecounter"></span>
                                <p>Happy Clients</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-journal-richtext" style="color: #ee6c20;"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="521"
                                      data-purecounter-duration="1" class="purecounter"></span>
                                <p>Projects</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-headset" style="color: #15be56;"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="1463"
                                      data-purecounter-duration="1" class="purecounter"></span>
                                <p>Hours Of Support</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-people" style="color: #bb0852;"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="15"
                                      data-purecounter-duration="1" class="purecounter"></span>
                                <p>Hard Workers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Counts Section -->

        <!-- ======= Pricing Section ======= -->
        <section id="planos" class="pricing">
            <div class="container" data-aos="fade-up">
                <header class="section-header">
                    {{-- <h2>Planos e preços</h2> --}}
                    <p>Planos e preços</p>
                </header>

                <div class="row gy-4 justify-content-center" data-aos="fade-left">

                    <div class="col-lg-4 col-md-4">
                        <div class="box">
                            <h3 style="color: #07d5c0;">Eventos gratuitos</h3>
                            <div class="price">Grátis</div>
                            {{-- <img src="assets/img/pricing-free.png" class="img-fluid" alt=""> --}}
                            <ul>
                                <li>Limite de 3 mil inscrições por evento.</li>
                                <li>Em eventos com inscrições gratuitas, não cobramos taxa de serviço. Utilize a
                                    plataforma sem custo!</li>
                            </ul>
                            <a href="#" class="btn-buy">Saiba mais</a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <div class="box">
                            <h3 style="color: #ff0071;">Eventos pagos</h3>
                            <div class="price">10%</div>
                            {{-- <img src="assets/img/pricing-ultimate.png" class="img-fluid" alt=""> --}}
                            <ul>
                                <li>Pague apenas 10% por inscrição vendida e utilize todas as ferramentas. Custos de
                                    meios de pagamentos inclusos.</li>
                                <li><small><a href="#">Sem tarifa mínima.</a></small></li>
                            </ul>
                            <a href="#" class="btn-buy">Saiba mais</a>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Pricing Section -->

        <!-- ======= F.A.Q Section ======= -->
        <section id="faq" class="faq">

            <div class="container" data-aos="fade-up">

                <header class="section-header">
                    <p>Perguntas frequentes</p>
                </header>

                <div class="row">
                    <div class="col-lg-6">
                        <!-- F.A.Q List 1-->
                        <div class="accordion accordion-flush" id="faqlist1">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-1">
                                        Non consectetur a erat nam at lectus urna duis?
                                    </button>
                                </h2>
                                <div id="faq-content-1" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus
                                        laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor
                                        rhoncus dolor purus non.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-2">
                                        Feugiat scelerisque varius morbi enim nunc faucibus a pellentesque?
                                    </button>
                                </h2>
                                <div id="faq-content-2" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id
                                        interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus
                                        scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim.
                                        Mauris ultrices eros in cursus turpis massa tincidunt dui.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-3">
                                        Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi?
                                    </button>
                                </h2>
                                <div id="faq-content-3" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci.
                                        Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl
                                        suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis
                                        convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- F.A.Q List 2-->
                        <div class="accordion accordion-flush" id="faqlist2">

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2-content-1">
                                        Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?
                                    </button>
                                </h2>
                                <div id="faq2-content-1" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id
                                        interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus
                                        scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim.
                                        Mauris ultrices eros in cursus turpis massa tincidunt dui.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2-content-2">
                                        Tempus quam pellentesque nec nam aliquam sem et tortor consequat?
                                    </button>
                                </h2>
                                <div id="faq2-content-2" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim
                                        suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan.
                                        Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit
                                        turpis cursus in
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2-content-3">
                                        Varius vel pharetra vel turpis nunc eget lorem dolor?
                                    </button>
                                </h2>
                                <div id="faq2-content-3" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies
                                        leo integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet.
                                        Turpis nunc eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu
                                        scelerisque. Pellentesque diam volutpat commodo sed egestas egestas fringilla
                                        phasellus faucibus. Nibh tellus molestie nunc non blandit massa enim nec.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End F.A.Q Section -->

        <!-- ======= Clients Section ======= -->
        <section id="clients" class="clients">
            <div class="container" data-aos="fade-up">

                <header class="section-header">
                    <p>Nossos clientes</p>
                </header>

                <div class="clients-slider swiper">
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide"><img src="assets/img/clients/client-1.png" class="img-fluid"
                                 alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-2.png" class="img-fluid"
                                 alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-3.png" class="img-fluid"
                                 alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-4.png" class="img-fluid"
                                 alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-5.png" class="img-fluid"
                                 alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-6.png" class="img-fluid"
                                 alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-7.png" class="img-fluid"
                                 alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-8.png" class="img-fluid"
                                 alt=""></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

        </section><!-- End Clients Section -->


        <!-- ======= Testimonials Section ======= -->
        <section id="testimonials" class="testimonials">

            <div class="container" data-aos="fade-up">

                <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="200">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit
                                    rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam,
                                    risus at semper.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img"
                                         alt="">
                                    <h3>Saul Goodman</h3>
                                    <h4>Ceo &amp; Founder</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid
                                    cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet
                                    legam anim culpa.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img"
                                         alt="">
                                    <h3>Sara Wilsson</h3>
                                    <h4>Designer</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem
                                    veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint
                                    minim.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img"
                                         alt="">
                                    <h3>Jena Karlis</h3>
                                    <h4>Store Owner</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim
                                    fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem
                                    dolore labore illum veniam.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img"
                                         alt="">
                                    <h3>Matt Brandon</h3>
                                    <h4>Freelancer</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                       class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster
                                    veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam
                                    culpa fore nisi cillum quid.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img"
                                         alt="">
                                    <h3>John Larson</h3>
                                    <h4>Entrepreneur</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>

        </section><!-- End Testimonials Section -->

        {{-- <!-- ======= Recent Blog Posts Section ======= -->
            <section id="recent-blog-posts" class="recent-blog-posts">
        
                <div class="container" data-aos="fade-up">
        
                <header class="section-header">
                    <h2>Blog</h2>
                    <p>Recent posts form our Blog</p>
                </header>
        
                <div class="row">
        
                    <div class="col-lg-4">
                    <div class="post-box">
                        <div class="post-img"><img src="assets/img/blog/blog-1.jpg" class="img-fluid" alt=""></div>
                        <span class="post-date">Tue, September 15</span>
                        <h3 class="post-title">Eum ad dolor et. Autem aut fugiat debitis voluptatem consequuntur sit</h3>
                        <a href="blog-single.html" class="readmore stretched-link mt-auto"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                    </div>
                    </div>
        
                    <div class="col-lg-4">
                    <div class="post-box">
                        <div class="post-img"><img src="assets/img/blog/blog-2.jpg" class="img-fluid" alt=""></div>
                        <span class="post-date">Fri, August 28</span>
                        <h3 class="post-title">Et repellendus molestiae qui est sed omnis voluptates magnam</h3>
                        <a href="blog-single.html" class="readmore stretched-link mt-auto"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                    </div>
                    </div>
        
                    <div class="col-lg-4">
                    <div class="post-box">
                        <div class="post-img"><img src="assets/img/blog/blog-3.jpg" class="img-fluid" alt=""></div>
                        <span class="post-date">Mon, July 11</span>
                        <h3 class="post-title">Quia assumenda est et veritatis aut quae</h3>
                        <a href="blog-single.html" class="readmore stretched-link mt-auto"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                    </div>
                    </div>
        
                </div>
        
                </div>
        
            </section><!-- End Recent Blog Posts Section --> --}}

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">

            <div class="container" data-aos="fade-up">

                <header class="section-header">
                    <p>Entre em contato</p>
                </header>

                <div class="row gy-4">
                    <div class="col-lg-6">
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-geo-alt"></i>
                                    <h3>Address</h3>
                                    <p>A108 Adam Street,<br>New York, NY 535022</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-telephone"></i>
                                    <h3>Call Us</h3>
                                    <p>+1 5589 55488 55<br>+1 6678 254445 41</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-envelope"></i>
                                    <h3>Email Us</h3>
                                    <p>info@example.com<br>contact@example.com</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-clock"></i>
                                    <h3>Open Hours</h3>
                                    <p>Monday - Friday<br>9:00AM - 05:00PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <form action="forms/contact.php" method="post" class="php-email-form">
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Seu nome"
                                           required>
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email"
                                           placeholder="Seu email" required>
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Assunto"
                                           required>
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Mensagem" required></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>

                                    <button type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">

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
                        <a href="index.html" class="logo d-flex align-items-center">
                            <img src="assets/img/logo.png" alt="">
                            <span>FlexStart</span>
                        </a>
                        <p>Cras fermentum odio eu feugiat lide par naso tierra. Justo eget nada terra videa magna derita
                            valies darta donna mare fermentum iaculis eu non diam phasellus.</p>
                        <div class="social-links mt-3">
                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-6 footer-links">
                        <h4>Useful Links</h4>
                        <ul>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy policy</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-6 footer-links">
                        <h4>Our Services</h4>
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
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>FlexStart</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/flexstart-bootstrap-startup-template/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
           class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>
