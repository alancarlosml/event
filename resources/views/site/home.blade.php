<x-site-layout>
    <section id="hero" class="hero owl-carousel">
        <div class="hero_img">
            <img src="{{ asset('site/home1.jpg')}}" alt=""> 
        </div>
        <div class="hero_img">
            <img src="{{ asset('site/home2.jpg')}}" alt="">
        </div>
        <div class="hero_img">
            <img src="{{ asset('site/home3.jpg')}}" alt="">
        </div>
        <div class="hero_img">
            <img src="{{ asset('site/home4.jpg')}}" alt="">
        </div>
    </section><!-- End Hero -->

    <div class="container hero_absolute">
        <div class="row">
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <h1 data-aos="fade-up">Agora ficou muito mais fácil organizar seus próprios eventos!</h1>
                <h2 data-aos="fade-up" data-aos-delay="400">Crie seu própio evento e comece a lucrar com ele agora mesmo.</h2>
                <div>
                    <div class="text-center text-lg-start">
                    <a href="/cadastro" class="btn-get-started d-inline-flex align-items-center justify-content-center align-self-center">
                        <span>Começar agora!</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-img">
                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/XOTq6z3QdX8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    
    <main id="main">
        <!-- ======= Values Section ======= -->
        <section id="values" class="values">

            <div class="container" data-aos="fade-up">
                <header class="section-header">
                        <p>Comece a vender em 5 minutos!</p>
                        <h2 class="mt-3">Uma plataforma de eventos para te ajudar a não perder tempo e focar no que importa</h2>
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
                        <p>Características</p>
                    </header>

                    <div class="row">
                        <div class="col-xl-4 text-center" data-aos="fade-right" data-aos-delay="100">
                            <img src="assets/img/features-3.png" class="img-fluid p-4" alt="">
                        </div>
                        <div class="col-xl-8 d-flex content">
                            <div class="row align-self-center gy-4">
                
                                <div class="col-md-6 icon-box" data-aos="fade-up">
                                    <i class="ri-line-chart-line"></i>
                                    <div>
                                        <h4>Site do evento</h4>
                                        <p>Crie um site completo para seu evento em poucos minutos e de forma fácil. Basta preencher as informações, escolher o layout e publicar. Pronto! Site no ar e inscrições disponíveis para venda.</p>
                                    </div>
                                </div>
                
                                <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
                                    <i class="ri-stack-line"></i>
                                    <div>
                                        <h4>Inscrições</h4>
                                        <p>Venda inscrições online através de cartão de crédito (em até 12x), boleto e até mesmo empenho. Você ainda pode receber pagamentos internacionais. E o melhor: receba o dinheiro em 2 dias*!</p>
                                    </div>
                                </div>
                
                                <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
                                    <i class="ri-brush-4-line"></i>
                                    <div>
                                        <h4>Credenciamento</h4>
                                        <p>Todas as ferramentas de secretaria à sua disposição. Gere etiquetas para crachás, faça o check-in de participantes e controle a entrada e saída deles. Aproveite o app Doity Check-in e acelere o credenciamento!</p>
                                    </div>
                                </div>
                
                                <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
                                    <i class="ri-magic-line"></i>
                                    <div>
                                        <h4>Certificados</h4>
                                        <p>Emita certificados online para participantes, palestrantes, autores, apresentadores, avaliadores e organizadores do evento. Personalize e envie por email com um simples clique!
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
                            <div class="service-box">
                                <h3><a href="/{{$category->slug}}">{{$category->description}}</a></h3>
                            </div>
                        </div>
                    @endforeach
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
        <section id="planos" class="pricing" style="background: #4154f1;">
            <div class="container" data-aos="fade-up">
                <header class="section-header">
                    {{-- <h2>Planos e preços</h2> --}}
                    <p style="color: #ffffff">Planos e preços</p>
                </header>
        
                <div class="row gy-4 justify-content-center" data-aos="fade-left">
        
                    <div class="col-lg-4 col-md-4">
                        <div class="box">
                            <h3 style="color: #07d5c0;">Eventos gratuitos</h3>
                            <div class="price">Grátis</div>
                            {{-- <img src="assets/img/pricing-free.png" class="img-fluid" alt=""> --}}
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
                            {{-- <img src="assets/img/pricing-ultimate.png" class="img-fluid" alt=""> --}}
                            <ul>
                            <li>Pague apenas 10% por inscrição vendida e utilize todas as ferramentas. Custos de meios de pagamentos inclusos.</li>
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-1">
                                    Non consectetur a erat nam at lectus urna duis?
                                    </button>
                                </h2>
                                <div id="faq-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                    Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.
                                    </div>
                                </div>
                            </div>
            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-2">
                                    Feugiat scelerisque varius morbi enim nunc faucibus a pellentesque?
                                    </button>
                                </h2>
                                <div id="faq-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
                                    </div>
                                </div>
                            </div>
            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-3">
                                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi?
                                    </button>
                                </h2>
                                <div id="faq-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                    Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2-content-1">
                                    Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?
                                    </button>
                                </h2>
                                <div id="faq2-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
                                    </div>
                                </div>
                            </div>
            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2-content-2">
                                    Tempus quam pellentesque nec nam aliquam sem et tortor consequat?
                                    </button>
                                </h2>
                                <div id="faq2-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                    Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in
                                    </div>
                                </div>
                            </div>
            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2-content-3">
                                    Varius vel pharetra vel turpis nunc eget lorem dolor?
                                    </button>
                                </h2>
                                <div id="faq2-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                    Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque. Pellentesque diam volutpat commodo sed egestas egestas fringilla phasellus faucibus. Nibh tellus molestie nunc non blandit massa enim nec.
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
                        <div class="swiper-slide"><img src="assets/img/clients/client-1.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-2.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-3.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-4.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-5.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-6.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-7.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-8.png" class="img-fluid" alt=""></div>
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
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.
                            </p>
                            <div class="profile mt-auto">
                                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                                <h3>Saul Goodman</h3>
                                <h4>Ceo &amp; Founder</h4>
                            </div>
                            </div>
                        </div><!-- End testimonial item -->
            
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.
                            </p>
                            <div class="profile mt-auto">
                                <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
                                <h3>Sara Wilsson</h3>
                                <h4>Designer</h4>
                            </div>
                            </div>
                        </div><!-- End testimonial item -->
            
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.
                            </p>
                            <div class="profile mt-auto">
                                <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
                                <h3>Jena Karlis</h3>
                                <h4>Store Owner</h4>
                            </div>
                            </div>
                        </div><!-- End testimonial item -->
            
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.
                            </p>
                            <div class="profile mt-auto">
                                <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
                                <h3>Matt Brandon</h3>
                                <h4>Freelancer</h4>
                            </div>
                            </div>
                        </div><!-- End testimonial item -->
            
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.
                            </p>
                            <div class="profile mt-auto">
                                <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
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

    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="{{ asset('assets/vendor/owlcarousel/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/owlcarousel/owl.theme.default.min.css') }}">
    @endpush

    @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush
        
    @push('footer')
        <script src="{{ asset('assets/vendor/owlcarousel/owl.carousel.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.owl-carousel').owlCarousel({
                    loop:true,
                    autoplay:true,
                    autoplayTimeout:3000,
                    animateOut: 'fadeOut',
                    margin:0,
                    nav:false,
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:1
                        },
                        1000:{
                            items:1
                        }
                    }
                })
            });
        </script>
    @endpush

</x-site-layout>