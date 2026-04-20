<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="/">Home</a></li>
              <li><a href="/painel/meus-eventos">Meus eventos</a></li>
              <li>Adicionar usuário</li>
            </ol>
            <h2>Adicionar usuário convidado</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page module-page" id="create-event-form">
            <div class="container">
                <div class="app-page-head module-hero">
                    <div class="app-page-copy">
                        <span class="app-page-kicker">Equipe do evento</span>
                        <h1 class="app-page-title">Adicionar usuario</h1>
                        <p class="app-page-subtitle">Inclua um novo perfil de apoio para operacao, atendimento ou monitoria do evento.</p>
                    </div>
                </div>

                <div class="mb-3 px-3 module-alerts">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
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
                    <div class="card-body module-card-body">
                        <div class="module-form-section">
                            <h4 class="card-title mb-4">Adicionar novo usuario convidado</h4>
                        <form method="POST" action="{{route('event_home.guest_store', $event->hash)}}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email do convidado <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{old('email')}}" required>
                                <div id="emailHelp" class="form-text text-muted">Antes de realizar a adição, certifique-se de que o usuário já possui cadastro no site.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">Papel <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Selecione</option>
                                    <option value="admin">Admin</option>
                                    <option value="convidado">Convidado</option>
                                    <option value="monitor">Monitor</option>
                                    <option value="vendedor">Vendedor</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1">
                                    <label class="form-check-label" for="status">Ativo</label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top module-actions">
                                <a href="{{ route('event_home.guests', $event->hash) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar
                                </a>
                                <button type="submit" class="btn btn-primary" onclick="this.disabled=true;this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Salvando...';this.form.submit();">
                                    <i class="fas fa-save me-2"></i>Salvar
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
        </section>
    
      </main><!-- End #main -->

      @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('assets_admin/css/manage-modules.css') }}" type="text/css">
      @endpush

      @push('footer')
        <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
                placement : 'right'
            });
        });
        </script>
      @endpush

</x-site-layout>


