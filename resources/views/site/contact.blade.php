<x-site-layout>
    <main id="main">
        <!-- Breadcrumbs (adicionado ARIA para navegação) -->
        <section class="breadcrumbs" aria-label="breadcrumb">
            <div class="container">
                <h2 class="mt-4">Contato</h2>
            </div>
        </section>

        <section id="contato" class="contact">
            <div class="container" data-aos="fade-up">
                <header class="section-header">
                    <p>Entre em contato</p>
                </header>

                <div class="row gy-4">
                    <div class="col-lg-6">
                        <div class="row gy-4">
                            <!-- Info boxes com sombras e transitions (já via CSS global) -->
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-geo-alt" aria-hidden="true"></i>
                                    <h3>Endereço</h3>
                                    <p>A108 Adam Street,<br>New York, NY 535022</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-telephone" aria-hidden="true"></i>
                                    <h3>Telefones
                                        <p>+1 5589 55488 55<br>+1 6678 254445 41</p>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-envelope" aria-hidden="true"></i>
                                    <h3>Email
                                        <p>info@example.com<br>contact@example.com</p>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <i class="bi bi-clock" aria-hidden="true"></i>
                                    <h3>Funcionamento
                                        <p>Seg - Sex<br> 9:00AM - 5:00PM</p>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <form action="{{ route('contact') }}" method="post" class="php-email-form" id="contactForm" aria-labelledby="contact-form-title">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Seu nome" required aria-required="true">
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Seu email" required aria-required="true">
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Assunto" required aria-required="true">
                                    <div class="invalid-feedback" id="subject-error"></div>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Telefone" required aria-required="true">
                                    <div class="invalid-feedback" id="phone-error"></div>
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="text" id="text" rows="6" placeholder="Mensagem" required aria-required="true"></textarea>
                                    <div class="invalid-feedback" id="text-error"></div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Carregando...</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Sua mensagem foi enviada. Obrigado!</div>
                                    {!! RecaptchaV3::field('contact') !!}
                                    <button type="submit" id="submitBtn" class="btn btn-primary" aria-label="Enviar mensagem">Enviar mensagem</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    @push('head')
        {!! RecaptchaV3::initJs() !!}
    @endpush

    @push('footer')
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#phone').mask('(00) 00000-0000');
                // Validação em tempo real
                $('#name').on('blur', function() {
                    const name = $(this).val().trim();
                    if (name.length < 2) {
                        $(this).addClass('is-invalid');
                        $('#name-error').text('Nome deve ter pelo menos 2 caracteres');
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#name-error').text('');
                    }
                });

                $('#email').on('blur', function() {
                    const email = $(this).val().trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        $(this).addClass('is-invalid');
                        $('#email-error').text('Email inválido');
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#email-error').text('');
                    }
                });

                $('#subject').on('blur', function() {
                    const subject = $(this).val().trim();
                    if (subject.length < 3) {
                        $(this).addClass('is-invalid');
                        $('#subject-error').text('Assunto deve ter pelo menos 3 caracteres');
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#subject-error').text('');
                    }
                });

                $('#phone').on('blur', function() {
                    const phone = $(this).val().trim();
                    if (phone.length < 10) {
                        $(this).addClass('is-invalid');
                        $('#phone-error').text('Telefone deve ter pelo menos 10 dígitos');
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#phone-error').text('');
                    }
                });

                $('#text').on('blur', function() {
                    const text = $(this).val().trim();
                    if (text.length < 10) {
                        $(this).addClass('is-invalid');
                        $('#text-error').text('Mensagem deve ter pelo menos 10 caracteres');
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#text-error').text('');
                    }
                });

                // Validação do formulário antes do envio
                $('#contactForm').on('submit', function(e) {
                    let isValid = true;
                    const requiredFields = $(this).find('[required]');
                    
                    requiredFields.each(function() {
                        const $field = $(this);
                        const value = $field.val().trim();
                        
                        if (!value) {
                            $field.addClass('is-invalid');
                            isValid = false;
                        } else {
                            $field.removeClass('is-invalid');
                        }
                    });
                    
                    // Validação específica para email
                    const emailField = $('#email');
                    const emailValue = emailField.val().trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    
                    if (emailValue && !emailRegex.test(emailValue)) {
                        emailField.addClass('is-invalid');
                        $('#email-error').text('Email inválido');
                        isValid = false;
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        $('html, body').animate({
                            scrollTop: $('.is-invalid:first').offset().top - 100
                        }, 500);
                        return false;
                    }
                    
                    $('#contactForm').on('submit', function(e) {
                        if (!this.checkValidity()) {
                            e.preventDefault();
                            $(this).addClass('was-validated');
                        } else {
                            $('#submitBtn').prop('disabled', true).text('Enviando...');
                        }
                    });
                });
            });
        </script>
    @endpush

</x-site-layout>
