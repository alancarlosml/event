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

        <section class="inner-page module-page" id="contact-detail">
            <div class="container">
                <div class="app-page-head module-hero">
                    <div class="app-page-copy">
                        <span class="app-page-kicker">Comunicacao</span>
                        <h1 class="app-page-title">Detalhes da mensagem</h1>
                        <p class="app-page-subtitle">Visualize o contato completo do participante sem perder contexto do evento e do historico da caixa.</p>
                    </div>
                </div>

                <div class="mb-3 px-3 module-alerts">
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

                <div class="card module-card">
                    <div class="card-header bg-white py-3 module-card-head">
                        <h4 class="mb-0">
                            <i class="fas fa-envelope-open-text me-2"></i>
                            Mensagem de Contato
                        </h4>
                    </div>
                    <div class="card-body module-card-body">
                        <div class="row module-detail-grid">
                            <div class="col-md-6">
                                <div class="module-detail-item">
                                    <span class="module-detail-label"><i class="fas fa-user text-primary"></i>Nome</span>
                                    <div class="module-detail-value">{{ $contact->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="module-detail-item">
                                    <span class="module-detail-label"><i class="fas fa-envelope text-primary"></i>Email</span>
                                    <div class="module-detail-value">{{ $contact->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="module-detail-item">
                                    <span class="module-detail-label"><i class="fas fa-phone text-primary"></i>Telefone</span>
                                    <div class="module-detail-value">{{ $contact->phone }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="module-detail-item">
                                    <span class="module-detail-label"><i class="fas fa-calendar text-primary"></i>Data de recebimento</span>
                                    <div class="module-detail-value">{{ \Carbon\Carbon::parse($contact->created_at)->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="module-detail-item">
                                    <span class="module-detail-label"><i class="fas fa-tag text-primary"></i>Assunto</span>
                                    <div class="module-detail-value">{{ $contact->subject }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="module-detail-item">
                                    <span class="module-detail-label"><i class="fas fa-comment-dots text-primary"></i>Mensagem</span>
                                    <div class="module-rich-box">
                                        <p class="text-muted mb-0" style="white-space: pre-wrap;">{{ $contact->text }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3 module-actions">
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
        <link rel="stylesheet" href="{{ asset('assets_admin/css/manage-modules.css') }}" type="text/css">
    @endpush

</x-site-layout>
