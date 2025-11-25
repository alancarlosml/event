<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                    <li><a href="{{ route('event_home.messages', $contact->event->hash) }}">Contatos</a></li>
                    <li>Mensagem</li>
                </ol>
                <h2>Detalhes da Mensagem</h2>
            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page" id="contact-detail">
            <div class="container">
                <div class="mb-3 px-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Erros encontrados:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-envelope-open-text me-2"></i>
                            Mensagem de Contato
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user me-2 text-primary"></i>Nome
                                </label>
                                <p class="text-muted ms-4">{{ $contact->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Email
                                </label>
                                <p class="text-muted ms-4">{{ $contact->email }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-phone me-2 text-primary"></i>Telefone
                                </label>
                                <p class="text-muted ms-4">{{ $contact->phone }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar me-2 text-primary"></i>Data de Recebimento
                                </label>
                                <p class="text-muted ms-4">{{ \Carbon\Carbon::parse($contact->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-tag me-2 text-primary"></i>Assunto
                                </label>
                                <p class="text-muted ms-4">{{ $contact->subject }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-comment-dots me-2 text-primary"></i>Mensagem
                                </label>
                                <div class="ms-4 p-3 bg-light rounded">
                                    <p class="text-muted mb-0" style="white-space: pre-wrap;">{{ $contact->text }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                        <a href="{{ route('event_home.messages', $contact->event->hash) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        @if($contact->read == 0)
                            <span class="badge bg-primary">Não lida</span>
                        @else
                            <span class="badge bg-success">Lida</span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
    @endpush

</x-site-layout>
