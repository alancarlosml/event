<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="/">Home</a></li>
              <li><a href="/painel/meus-eventos">Meus eventos</a></li>
              <li>Editar usuário</li>
            </ol>
            <h2>Gerenciar usuários convidados</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="mb-3 px-3">
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
                
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Editar usuário convidado</h4>
                        <form method="POST" action="{{ route('event_home.guest_update', $guest->id) }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Nome" value="{{ $guest->name ?? old('name') }}" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="email" name="email"
                                       placeholder="Email" value="{{ $guest->email ?? old('email') }}" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">Papel <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="admin" @if ($guest->role == 'admin') selected @endif>Admin</option>
                                    <option value="convidado" @if ($guest->role == 'convidado') selected @endif>Convidado</option>
                                    <option value="monitor" @if ($guest->role == 'monitor') selected @endif>Monitor</option>
                                    <option value="vendedor" @if ($guest->role == 'vendedor') selected @endif>Vendedor</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" @if ($guest->status == 1) checked @endif>
                                    <label class="form-check-label" for="status">Ativo</label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('event_home.guests', $guest->event_hash) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar
                                </a>
                                <button type="submit" class="btn btn-primary">
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