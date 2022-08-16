<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Todos os eventos</h2>
    
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
                                    <h3>Endereço</h3>
                                    <p>A108 Adam Street,<br>New York, NY 535022</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-telephone"></i>
                                    <h3>Telefones</h3>
                                    <p>+1 5589 55488 55<br>+1 6678 254445 41</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-envelope"></i>
                                    <h3>Email</h3>
                                    <p>info@example.com<br>contact@example.com</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-clock"></i>
                                    <h3>Horário de funcionamento</h3>
                                    <p>Seg - Sex<br>9:00AM - 05:00PM</p>
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
                                    <input type="text" class="form-control" name="phone" placeholder="Telefone" required>
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

      @push('head')

      
      @endpush

</x-site-layout>