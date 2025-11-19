<x-site-layout>
        <section id="hero" class="hero owl-carousel">
        <div class="hero_img">
            <img src="{{ asset('site/home1.jpg') }}" alt="Plataforma completa para criação e gestão de eventos" loading="lazy">
        </div>
        <div class="hero_img">
            <img src="{{ asset('site/home2.jpg') }}" alt="Venda de ingressos online com segurança e praticidade" loading="lazy">
        </div>
        <div class="hero_img">
            <img src="{{ asset('site/home3.jpg') }}" alt="Check-in rápido com QR Code para seus eventos" loading="lazy">
        </div>
        <div class="hero_img">
            <img src="{{ asset('site/home4.jpg') }}" alt="Gerencie todos os aspectos do seu evento em um só lugar" loading="lazy">
        </div>
    </section><!-- End Hero -->

    <div class="container hero_absolute">
        <div class="row">
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <h1 data-aos="fade-up">Plataforma completa para criar e gerenciar seus eventos</h1>
                <h2 data-aos="fade-up" data-aos-delay="400">Crie seu evento, venda inscrições online e gerencie tudo em um só lugar. Simples, rápido e profissional.</h2>
                <div>
                    <div class="text-center text-lg-start">
                        <a href="@if (!Auth::user()) {{ route('register') }} @else {{ route('event_home.create_event_link') }} @endif"
                           class="btn-get-started d-inline-flex align-items-center justify-content-center align-self-center"
                           aria-label="Começar a criar seu evento agora">
                            <span>Começar agora</span>
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-img">
                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/XOTq6z3QdX8"
                        title="Vídeo demonstrativo da plataforma de eventos"
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
                    <p>Comece a vender em 5 minutos!</p>
                    <h2 class="mt-3">Tudo que você precisa para criar e gerenciar eventos profissionais em uma única plataforma</h2>
                </header>
                <div class="row justify-content-center" id="start_event">
                    <div class="col-lg-2 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="box">
                            <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                            <h5>Cadastre-se gratuitamente</h5>
                            <p class="small text-muted mt-2">Crie sua conta em segundos</p>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="400">
                        <div class="box">
                            <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                            <h5>Configure seu evento</h5>
                            <p class="small text-muted mt-2">Preencha as informações básicas</p>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="600">
                        <div class="box">
                            <i class="fa-solid fa-ticket" aria-hidden="true"></i>
                            <h5>Crie seus lotes</h5>
                            <p class="small text-muted mt-2">Defina preços e quantidades</p>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="800">
                        <div class="box">
                            <i class="fa-solid fa-bullhorn" aria-hidden="true"></i>
                            <h5>Publique e divulgue</h5>
                            <p class="small text-muted mt-2">Seu evento fica online</p>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="1000">
                        <div class="box">
                            <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                            <h5>Gerencie e fatura</h5>
                            <p class="small text-muted mt-2">Acompanhe vendas em tempo real</p>
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
                    <p>Recursos da Plataforma</p>
                    <h2>Tudo que você precisa para eventos de sucesso</h2>
                </header>

                <div class="row">
                    <div class="col-xl-4 text-center" data-aos="fade-right" data-aos-delay="100">
                        <img src="assets/img/features-3.png" class="img-fluid p-4" alt="Recursos da plataforma de eventos">
                    </div>
                    <div class="col-xl-8 d-flex content">
                        <div class="row align-self-center gy-4">

                            <div class="col-md-6 icon-box" data-aos="fade-up">
                                <i class="ri-global-line"></i>
                                <div>
                                    <h4>Site personalizado do evento</h4>
                                    <p>Crie um site profissional e completo para seu evento em poucos minutos. Personalize cores, adicione informações, programação e muito mais. Seu evento terá uma página exclusiva e otimizada para conversão.</p>
                                </div>
                            </div>

                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
                                <i class="ri-money-dollar-circle-line"></i>
                                <div>
                                    <h4>Venda de inscrições online</h4>
                                    <p>Aceite pagamentos via cartão de crédito (em até 12x), PIX, boleto bancário e empenho. Receba pagamentos nacionais e internacionais de forma segura. Dinheiro na sua conta em até 2 dias úteis após a aprovação.</p>
                                </div>
                            </div>

                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
                                <i class="ri-qr-code-line"></i>
                                <div>
                                    <h4>Check-in com QR Code</h4>
                                    <p>Sistema completo de credenciamento com QR Code. Faça o check-in rápido dos participantes, controle entrada e saída, gere relatórios em tempo real e tenha total controle sobre a presença no seu evento.</p>
                                </div>
                            </div>

                            {{-- <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
                                <i class="ri-file-certificate-line"></i>
                                <div>
                                    <h4>Emissão de certificados</h4>
                                    <p>Gere certificados personalizados automaticamente para participantes, palestrantes, organizadores e demais envolvidos. Envie por email com um clique e economize tempo na organização do seu evento.</p>
                                </div>
                            </div> --}}

                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="400">
                                <i class="ri-bar-chart-box-line"></i>
                                <div>
                                    <h4>Relatórios e análises</h4>
                                    <p>Acompanhe vendas, participantes confirmados, receita gerada e muito mais através de dashboards intuitivos. Tome decisões baseadas em dados reais do seu evento.</p>
                                </div>
                            </div>

                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="500">
                                <i class="ri-coupon-line"></i>
                                <div>
                                    <h4>Cupons de desconto</h4>
                                    <p>Crie cupons promocionais com desconto percentual ou valor fixo. Aplique a lotes específicos, defina validade e limite de uso. Ideal para campanhas de marketing e incentivo de vendas.</p>
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
                    <p>Categorias de Eventos</p>
                    <h2>Explore eventos por categoria</h2>
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
                            <p class="text-muted">Nenhuma categoria disponível no momento.</p>
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
                    <p>Dúvidas Comuns</p>
                    <h2>Perguntas frequentes</h2>
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
                                        Como funciona a criação de eventos na plataforma?
                                    </button>
                                </h2>
                                <div id="faq-content-1" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        É muito simples! Após criar sua conta gratuita, você preenche as informações básicas do evento (nome, data, local, descrição), configura os lotes de ingressos com preços e quantidades, e publica. Em poucos minutos seu evento estará online e pronto para receber inscrições.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-2"
                                            aria-expanded="false" aria-controls="faq-content-2">
                                        Quais formas de pagamento são aceitas?
                                    </button>
                                </h2>
                                <div id="faq-content-2" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        Aceitamos cartão de crédito (em até 12x), PIX, boleto bancário e empenho. Também é possível receber pagamentos internacionais. O dinheiro cai na sua conta em até 2 dias úteis após a aprovação do pagamento.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-3"
                                            aria-expanded="false" aria-controls="faq-content-3">
                                        Existe algum custo para usar a plataforma?
                                    </button>
                                </h2>
                                <div id="faq-content-3" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        Para eventos gratuitos (sem cobrança de ingresso), a plataforma é totalmente gratuita. Para eventos pagos, cobramos uma taxa de 10% sobre cada venda, que já inclui todos os custos de meios de pagamento. Não há mensalidade, taxa de adesão ou valor mínimo.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-content-4"
                                            aria-expanded="false" aria-controls="faq-content-4">
                                        Como funciona o sistema de check-in com QR Code?
                                    </button>
                                </h2>
                                <div id="faq-content-4" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist1">
                                    <div class="accordion-body">
                                        Cada participante recebe um ingresso com QR Code único por email após a confirmação do pagamento. No dia do evento, basta escanear o código com um dispositivo móvel ou leitor de QR Code para fazer o check-in. O sistema registra a entrada em tempo real e você pode acompanhar tudo pelo painel administrativo.
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
                                        Posso criar cupons de desconto para meu evento?
                                    </button>
                                </h2>
                                <div id="faq2-content-1" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        Sim! Você pode criar quantos cupons quiser com desconto percentual ou valor fixo. Configure validade, limite de uso por cupom e aplique a lotes específicos. Ideal para campanhas promocionais e incentivo de vendas antecipadas.
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2-content-2"
                                            aria-expanded="false" aria-controls="faq2-content-2">
                                        Como são emitidos os certificados?
                                    </button>
                                </h2>
                                <div id="faq2-content-2" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        Após o evento, você pode gerar certificados personalizados para todos os participantes, palestrantes, organizadores e demais envolvidos. Os certificados são enviados automaticamente por email em formato PDF. Você pode personalizar o design e incluir informações específicas do evento.
                                    </div>
                                </div>
                            </div> --}}

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2-content-3"
                                            aria-expanded="false" aria-controls="faq2-content-3">
                                        Posso acompanhar as vendas em tempo real?
                                    </button>
                                </h2>
                                <div id="faq2-content-3" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        Sim! O painel administrativo oferece dashboards completos com gráficos de vendas, receita gerada, participantes confirmados, taxa de conversão e muito mais. Todas as informações são atualizadas em tempo real, permitindo que você tome decisões baseadas em dados reais.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2-content-4"
                                            aria-expanded="false" aria-controls="faq2-content-4">
                                        Meu evento terá uma página própria?
                                    </button>
                                </h2>
                                <div id="faq2-content-4" class="accordion-collapse collapse"
                                     data-bs-parent="#faqlist2">
                                    <div class="accordion-body">
                                        Sim! Cada evento recebe uma página exclusiva e personalizada com todas as informações, programação, localização, formulário de inscrição e muito mais. A página é otimizada para conversão e totalmente responsiva, funcionando perfeitamente em dispositivos móveis.
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
                    <p>O que nossos clientes dizem</p>
                    <h2>Depoimentos</h2>
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
                                    A plataforma facilitou muito a organização do nosso evento. Em poucos minutos conseguimos criar a página, configurar os lotes e começar a vender. O sistema de check-in com QR Code foi fundamental no dia do evento, agilizando muito o credenciamento.
                                </p>
                                <div class="profile mt-auto">
                                    <div class="testimonial-img bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 60px; height: 60px;" aria-hidden="true">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>
                                    <h3>Organizador de Eventos</h3>
                                    <h4>Cliente da Plataforma</h4>
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
