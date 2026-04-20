<x-site-layout>
    <section id="hero" class="hero owl-carousel">
        <div class="hero_img">
            <img src="{{ asset('site/home1.jpg') }}" alt="{{ __('home.hero.image_alt_1') }}" loading="lazy">
        </div>
        <div class="hero_img">
            <img src="{{ asset('site/home2.jpg') }}" alt="{{ __('home.hero.image_alt_2') }}" loading="lazy">
        </div>
        <div class="hero_img">
            <img src="{{ asset('site/home3.jpg') }}" alt="{{ __('home.hero.image_alt_3') }}" loading="lazy">
        </div>
        <div class="hero_img">
            <img src="{{ asset('site/home4.jpg') }}" alt="{{ __('home.hero.image_alt_4') }}" loading="lazy">
        </div>
    </section><!-- End Hero -->

    <div class="container hero_absolute">
        <div class="row">
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <h1 data-aos="fade-up">{{ __('home.hero.title') }}</h1>
                <h2 data-aos="fade-up" data-aos-delay="400">{{ __('home.hero.subtitle') }}</h2>
                <div>
                    <div class="text-center text-lg-start">
                        <a href="@if (!Auth::user()) {{ route('register') }} @else {{ route('event_home.create_event_link') }} @endif"
                           class="btn-get-started d-inline-flex align-items-center justify-content-center align-self-center"
                           aria-label="{{ __('home.hero.cta_aria') }}">
                            <span>{{ __('home.hero.cta') }}</span>
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-img">
                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/XOTq6z3QdX8"
                        title="{{ __('home.hero.video_title') }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <main id="main">
        <!-- ======= Values Section ======= -->
        <section id="values" class="values">

            <div class="container" data-aos="fade-up">
                <header class="section-header">
                    <p>{{ __('home.steps.kicker') }}</p>
                    <h2 class="mt-3">{{ __('home.steps.subtitle') }}</h2>
                </header>
                <div class="row justify-content-center" id="start_event">
                    <div class="col-lg-2 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="box">
                            <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                            <h5>{{ __('home.steps.item_1_title') }}</h5>
                            <p class="small text-muted mt-2">{{ __('home.steps.item_1_text') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="400">
                        <div class="box">
                            <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                            <h5>{{ __('home.steps.item_2_title') }}</h5>
                            <p class="small text-muted mt-2">{{ __('home.steps.item_2_text') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="600">
                        <div class="box">
                            <i class="fa-solid fa-ticket" aria-hidden="true"></i>
                            <h5>{{ __('home.steps.item_3_title') }}</h5>
                            <p class="small text-muted mt-2">{{ __('home.steps.item_3_text') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="800">
                        <div class="box">
                            <i class="fa-solid fa-bullhorn" aria-hidden="true"></i>
                            <h5>{{ __('home.steps.item_4_title') }}</h5>
                            <p class="small text-muted mt-2">{{ __('home.steps.item_4_text') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="1000">
                        <div class="box">
                            <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                            <h5>{{ __('home.steps.item_5_title') }}</h5>
                            <p class="small text-muted mt-2">{{ __('home.steps.item_5_text') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Values Section -->

        <!-- ======= Features Section ======= -->
        <section id="features" class="features">

            {{-- <div class="container" data-aos="fade-up">
        
                <header class="section-header">
                    <p>Características</p>
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
                                    <h3>Tenha um site próprio para seu evento</h3>
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
                        <p>Consequuntur inventore voluptates consequatur aut vel et. Eos doloribus expedita. Sapiente atque consequatur minima nihil quae aspernatur quo suscipit voluptatem.</p>
                        <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-check2"></i>
                        <h4>Repudiandae rerum velit modi et officia quasi facilis</h4>
                        </div>
                        <p>Laborum omnis voluptates voluptas qui sit aliquam blanditiis. Sapiente minima commodi dolorum non eveniet magni quaerat nemo et.</p>
                        <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-check2"></i>
                        <h4>Incidunt non veritatis illum ea ut nisi</h4>
                        </div>
                        <p>Non quod totam minus repellendus autem sint velit. Rerum debitis facere soluta tenetur. Iure molestiae assumenda sunt qui inventore eligendi voluptates nisi at. Dolorem quo tempora. Quia et perferendis.</p>
                    </div><!-- End Tab 1 Content -->
        
                    <div class="tab-pane fade show" id="tab2">
                        <p>Consequuntur inventore voluptates consequatur aut vel et. Eos doloribus expedita. Sapiente atque consequatur minima nihil quae aspernatur quo suscipit voluptatem.</p>
                        <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-check2"></i>
                        <h4>Repudiandae rerum velit modi et officia quasi facilis</h4>
                        </div>
                        <p>Laborum omnis voluptates voluptas qui sit aliquam blanditiis. Sapiente minima commodi dolorum non eveniet magni quaerat nemo et.</p>
                        <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-check2"></i>
                        <h4>Incidunt non veritatis illum ea ut nisi</h4>
                        </div>
                        <p>Non quod totam minus repellendus autem sint velit. Rerum debitis facere soluta tenetur. Iure molestiae assumenda sunt qui inventore eligendi voluptates nisi at. Dolorem quo tempora. Quia et perferendis.</p>
                    </div><!-- End Tab 2 Content -->
        
                    <div class="tab-pane fade show" id="tab3">
                        <p>Consequuntur inventore voluptates consequatur aut vel et. Eos doloribus expedita. Sapiente atque consequatur minima nihil quae aspernatur quo suscipit voluptatem.</p>
                        <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-check2"></i>
                        <h4>Repudiandae rerum velit modi et officia quasi facilis</h4>
                        </div>
                        <p>Laborum omnis voluptates voluptas qui sit aliquam blanditiis. Sapiente minima commodi dolorum non eveniet magni quaerat nemo et.</p>
                        <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-check2"></i>
                        <h4>Incidunt non veritatis illum ea ut nisi</h4>
                        </div>
                        <p>Non quod totam minus repellendus autem sint velit. Rerum debitis facere soluta tenetur. Iure molestiae assumenda sunt qui inventore eligendi voluptates nisi at. Dolorem quo tempora. Quia et perferendis.</p>
                    </div><!-- End Tab 3 Content -->
        
                    </div>
        
                </div>
        
                <div class="col-lg-6">
                    <img src="assets/img/features-2.png" class="img-fluid" alt="">
                </div>
        
                </div><!-- End Feature Tabs --> --}}

            <!-- Feature Icons -->
            <div class="row feature-icons" data-aos="fade-up">
                <header class="section-header">
                    <p>{{ __('home.features.kicker') }}</p>
                    <h2>{{ __('home.features.subtitle') }}</h2>
                </header>

                <div class="row">
                    <div class="col-xl-4 text-center" data-aos="fade-right" data-aos-delay="100">
                        <img src="assets/img/features-3.png" class="img-fluid p-4" alt="{{ __('home.features.image_alt') }}">
                    </div>
                    <div class="col-xl-8 d-flex content">
                        <div class="row align-self-center g-md-5">

                            <div class="col-md-6" data-aos="fade-up">
                                <div class="icon-box">
                                    <i class="ri-global-line"></i>
                                    <div>
                                        <h4>{{ __('home.features.item_1_title') }}</h4>
                                        <p>{{ __('home.features.item_1_text') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                                <div class="icon-box">
                                    <i class="ri-money-dollar-circle-line"></i>
                                    <div>
                                        <h4>{{ __('home.features.item_2_title') }}</h4>
                                        <p>{{ __('home.features.item_2_text') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="icon-box">
                                    <i class="ri-qr-code-line"></i>
                                    <div>
                                        <h4>{{ __('home.features.item_3_title') }}</h4>
                                        <p>{{ __('home.features.item_3_text') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                                <div class="icon-box">
                                    <i class="ri-bar-chart-box-line"></i>
                                    <div>
                                        <h4>{{ __('home.features.item_4_title') }}</h4>
                                        <p>{{ __('home.features.item_4_text') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                                <div class="icon-box">
                                    <i class="ri-coupon-line"></i>
                                    <div>
                                        <h4>{{ __('home.features.item_5_title') }}</h4>
                                        <p>{{ __('home.features.item_5_text') }}</p>
                                    </div>
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
                    <p>{{ __('home.categories.kicker') }}</p>
                    <h2>{{ __('home.categories.subtitle') }}</h2>
                </header>
                <div class="row gy-4">
                    @if(isset($categories) && $categories->count() > 0)
                        @foreach ($categories as $category)
                            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <div class="service-box">
                                    <h3><a href="/{{ $category->slug }}" class="text-decoration-none">{{ $category->description }}</a></h3>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center">
                            <p class="text-muted">{{ __('home.categories.empty') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section><!-- End Services Section -->

        {{-- <!-- ======= Counts Section ======= -->
        <section id="counts" class="counts" style="background: #4154f1;">
            <div class="container" data-aos="fade-up">
    
                <div class="row gy-4">
        
                    <div class="col-lg-3 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-emoji-smile"></i>
                            <div>
                            <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Happy Clients</p>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-lg-3 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-journal-richtext" style="color: #ee6c20;"></i>
                            <div>
                            <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Projects</p>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-lg-3 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-headset" style="color: #15be56;"></i>
                            <div>
                            <span data-purecounter-start="0" data-purecounter-end="1463" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Hours Of Support</p>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-lg-3 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-people" style="color: #bb0852;"></i>
                            <div>
                            <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Hard Workers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Counts Section --> --}}

        <!-- ======= Pricing Section ======= -->
        {{-- <section id="planos" class="pricing" style="background: #4154f1;">
            <div class="container" data-aos="fade-up">
                <header class="section-header">
                    <p style="color: #ffffff">Planos e preços</p>
                </header>
        
                <div class="row gy-4 justify-content-center" data-aos="fade-left">
        
                    <div class="col-lg-4 col-md-4">
                        <div class="box">
                            <h3 style="color: #07d5c0;">Eventos gratuitos</h3>
                            <div class="price">Grátis</div>
                            <ul>
                                <li>Limite de 3 mil inscrições por evento.</li>
                                <li>Em eventos com inscrições gratuitas, não cobramos taxa de serviço. Utilize a plataforma sem custo!</li>
                            </ul>
                            <a href="#" class="btn-buy">Saiba mais</a>
                        </div>
                    </div>
        
                    <div class="col-lg-4 col-md-4">
                        <div class="box">
                            <h3 style="color: #ff0071;">Eventos pagos</h3>
                            <div class="price">10%</div>
                            <ul>
                                <li>Pague apenas 10% por inscrição vendida e utilize todas as ferramentas. Custos de meios de pagamentos inclusos.</li>
                                <li><small><a href="#">Sem tarifa mínima.</a></small></li>
                            </ul>
                            <a href="#" class="btn-buy">Saiba mais</a>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Pricing Section --> --}}

        <!-- ======= F.A.Q Section ======= -->
        <section id="faq" class="faq">

            <div class="container" data-aos="fade-up">

                <header class="section-header">
                    <p>{{ __('home.faq.kicker') }}</p>
                    <h2>{{ __('home.faq.title') }}</h2>
                </header>

                <div class="row">
                    <div class="col-lg-6">
                        <!-- F.A.Q List 1-->
                        <div class="accordion accordion-flush" id="faqlist1">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-1"
                                            aria-expanded="false" aria-controls="faq-content-1">
                                        {{ __('home.faq.q1_title') }}
                                    </button>
                                </h2>
                                <div id="faq-content-1" class="accordion-collapse collapse"
                                      data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        {{ __('home.faq.q1_text') }}
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-2"
                                            aria-expanded="false" aria-controls="faq-content-2">
                                        {{ __('home.faq.q2_title') }}
                                    </button>
                                </h2>
                                <div id="faq-content-2" class="accordion-collapse collapse"
                                      data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        {{ __('home.faq.q2_text') }}
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-3"
                                            aria-expanded="false" aria-controls="faq-content-3">
                                        {{ __('home.faq.q3_title') }}
                                    </button>
                                </h2>
                                <div id="faq-content-3" class="accordion-collapse collapse"
                                      data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        {{ __('home.faq.q3_text') }}
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-4"
                                            aria-expanded="false" aria-controls="faq-content-4">
                                        {{ __('home.faq.q4_title') }}
                                    </button>
                                </h2>
                                <div id="faq-content-4" class="accordion-collapse collapse"
                                      data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        {{ __('home.faq.q4_text') }}
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
                                            data-bs-toggle="collapse" data-bs-target="#faq2-content-1"
                                            aria-expanded="false" aria-controls="faq2-content-1">
                                        {{ __('home.faq.q5_title') }}
                                    </button>
                                </h2>
                                <div id="faq2-content-1" class="accordion-collapse collapse"
                                      data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        {{ __('home.faq.q5_text') }}
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2-content-3"
                                            aria-expanded="false" aria-controls="faq2-content-3">
                                        {{ __('home.faq.q6_title') }}
                                    </button>
                                </h2>
                                <div id="faq2-content-3" class="accordion-collapse collapse"
                                      data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        {{ __('home.faq.q6_text') }}
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2-content-4"
                                            aria-expanded="false" aria-controls="faq2-content-4">
                                        {{ __('home.faq.q7_title') }}
                                    </button>
                                </h2>
                                <div id="faq2-content-4" class="accordion-collapse collapse"
                                      data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        {{ __('home.faq.q7_text') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End F.A.Q Section -->



        <!-- ======= Testimonials Section ======= -->
        <section id="testimonials" class="testimonials">

            <div class="container" data-aos="fade-up">
                <header class="section-header">
                    <p>{{ __('home.testimonials.kicker') }}</p>
                    <h2>{{ __('home.testimonials.title') }}</h2>
                </header>

                <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="200">
                    <div class="swiper-wrapper">

                        {{-- Depoimentos podem ser adicionados aqui quando houver clientes reais --}}
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars" aria-label="5 estrelas">
                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                </div>
                                <p>
                                    {{ __('home.testimonials.quote') }}
                                </p>
                                <div class="profile mt-auto">
                                    <div class="testimonial-img bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 60px; height: 60px;" aria-hidden="true">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>
                                    <h3>{{ __('home.testimonials.author') }}</h3>
                                    <h4>{{ __('home.testimonials.role') }}</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                    </div>
                    <div class="swiper-pagination" aria-label="Navegação de depoimentos"></div>
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

    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="{{ asset('assets/vendor/owlcarousel/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/owlcarousel/owl.theme.default.min.css') }}">
    @endpush

    @push('footer')
        <script src="{{ asset('assets/vendor/owlcarousel/owl.carousel.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // Configuração do carrossel com melhor performance
                $('.owl-carousel').owlCarousel({
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    animateOut: 'fadeOut',
                    margin: 0,
                    nav: false,
                    dots: true,
                    responsive: { // Melhor responsividade
                        0: { items: 1 },
                        768: { items: 1 }
                    }
                });
                
                // Animações suaves para scroll
                $('a[href^="#"]').on('click', function(event) {
                    var target = $(this.getAttribute('href'));
                    if (target.length) {
                        event.preventDefault();
                        $('html, body').stop().animate({
                            scrollTop: target.offset().top - 80
                        }, 1000);
                    }
                });
                
                // Efeito hover nos cards de eventos
                $('.single-blog-item').hover(
                    function() {
                        $(this).addClass('shadow-lg').css('transform', 'translateY(-5px)');
                    },
                    function() {
                        $(this).removeClass('shadow-lg').css('transform', 'translateY(0)');
                    }
                );
                
                // Feedback visual para botões
                $('.btn-get-started').hover(
                    function() {
                        $(this).addClass('pulse-animation');
                    },
                    function() {
                        $(this).removeClass('pulse-animation');
                    }
                );
                
                // Lazy loading para imagens
                if ('IntersectionObserver' in window) {
                    const imageObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
                                img.src = img.dataset.src;
                                img.classList.remove('lazy');
                                imageObserver.unobserve(img);
                            }
                        });
                    });
                    
                    document.querySelectorAll('img[data-src]').forEach(img => {
                        imageObserver.observe(img);
                    });
                }
            });
        </script>
        
        <style>
            .pulse-animation {
                animation: pulse 0.6s ease-in-out;
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            .single-blog-item {
                transition: all 0.3s ease;
            }
            
            .lazy {
                opacity: 0;
                transition: opacity 0.3s;
            }
            
            .lazy.loaded {
                opacity: 1;
            }

            /* Melhorias Features Section */
            .feature-icons .icon-box {
                padding: 2.5rem 2rem;
                border-radius: 16px;
                transition: all 0.3s ease;
                background: #ffffff;
                border: 1px solid rgba(0,0,0,0.05);
                box-shadow: 0 10px 30px rgba(1, 41, 112, 0.05);
                height: 100%;
                display: flex;
                align-items: flex-start;
            }

            .feature-icons .icon-box i {
                font-size: 38px;
                line-height: 1;
                color: #0245bc;
                margin-right: 20px;
                flex-shrink: 0;
                background: #f1f4ff;
                width: 64px;
                height: 64px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                transition: all 0.3s ease;
            }

            .feature-icons .icon-box:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 35px rgba(1, 41, 112, 0.1);
                border-color: rgba(65, 84, 241, 0.2);
            }

            .feature-icons .icon-box:hover i {
                background: #0245bc;
                color: #fff;
            }

            .feature-icons .icon-box h4 {
                font-size: 20px;
                font-weight: 700;
                margin: 0 0 12px 0;
                color: #073b58;
            }

            .feature-icons .icon-box p {
                font-size: 15px;
                color: #666;
                line-height: 1.6;
                margin-bottom: 0;
            }

            /* Melhorias Services Section */
            .services .service-box {
                transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                border-radius: 20px;
                padding: 3rem 2rem;
                height: 100%;
                background: #ffffff;
                border: 1px solid rgba(0,0,0,0.03);
                box-shadow: 0 5px 20px rgba(1, 41, 112, 0.05);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                position: relative;
                overflow: hidden;
            }

            .services .service-box::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: var(--primary-color, #4154f1);
                opacity: 0;
                transition: 0.3s;
            }

            .services .service-box:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(1, 41, 112, 0.1);
                background-color: #ffffff;
            }

            .services .service-box:hover::before {
                opacity: 1;
            }

            .services .service-box h3 a {
                color: #073b58;
                font-weight: 700;
                font-size: 22px;
                transition: 0.3s;
            }

            .services .service-box:hover h3 a {
                color: var(--primary-color, #4154f1);
            }
            
            @media (max-width: 768px) {
                .hero h1 {
                    font-size: 2rem;
                }
                
                .hero h2 {
                    font-size: 1.2rem;
                }
                
                #start_event .box {
                    margin-bottom: 1.5rem;
                }
                
                #start_event .box h5 {
                    font-size: 0.9rem;
                }
                
                #start_event .box .small {
                    font-size: 0.75rem;
                }
            }
        </style>
    @endpush

</x-site-layout>
