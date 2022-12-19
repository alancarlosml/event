<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <h2 class="mt-4">Contato</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section id="contato" class="contact">
    
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
                                    <h3>Endereço
                                        <p>A108 Adam Street,<br>New York, NY 535022</p>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-telephone"></i>
                                    <h3>Telefones
                                        <p>+1 5589 55488 55<br>+1 6678 254445 41</p>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-envelope"></i>
                                    <h3>Email
                                        <p>info@example.com<br>contact@example.com</p>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-clock"></i>
                                    <h3>Funcionamento
                                        <p>Seg - Sex<br> 9:00AM - 5:00PM</p>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-lg-6">
                        <form action="{{route('contact')}}" method="post" class="php-email-form">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" placeholder="Seu nome" required>
                                </div>
                
                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email" placeholder="Seu email" required>
                                </div>
                
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="subject" placeholder="Assunto" required>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Telefone" required>
                                </div>
                
                                <div class="col-md-12">
                                    <textarea class="form-control" name="text" rows="6" placeholder="Mensagem" required></textarea>
                                </div>
                
                                <div class="col-md-12 text-center">
                                    <div class="loading">Carregando...</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Sua mensagem foi enviada. Obrigado!</div>
                
                                    <button type="submit">Enviar mensagem</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

        @push('bootstrap_version')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        @endpush

        @push('footer')
            <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
            <script>
                $(document).ready(function() { 

                    $('#phone').mask('(00) 00000-0000');
                });
            </script>
        @endpush

</x-site-layout>