<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                <li>Usuários convidados</li>
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
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2" aria-hidden="true"></i>
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
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0">Lista de Usuários</h4>
                        <a href="{{route('event_home.guest_add', $event->hash)}}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Cadastrar novo usuário
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="table_guests">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Papel</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usuarios as $usuario)
                                        <tr data-guest-id="{{$usuario->id}}">
                                            <td>{{$usuario->id}}</td>
                                            <td>{{$usuario->name}}</td>
                                            <td>{{$usuario->email}}</td>
                                            <td>
                                                @if($usuario->role == 'admin') 
                                                    <span class="badge bg-primary">Admin</span>
                                                @elseif($usuario->role == 'monitor')
                                                    <span class="badge bg-info text-dark">Monitor</span>
                                                @elseif($usuario->role == 'vendedor')
                                                    <span class="badge bg-warning text-dark">Vendedor</span>
                                                @else 
                                                    <span class="badge bg-secondary">Convidado</span> 
                                                @endif
                                            </td>
                                            <td>
                                                @if($usuario->status == 1) 
                                                    <span class="badge bg-success">Ativo</span> 
                                                @else 
                                                    <span class="badge bg-danger">Inativo</span> 
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a class="btn btn-sm btn-outline-info" href="{{route('event_home.guest_edit', $usuario->id)}}" data-bs-toggle="tooltip" title="Editar">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    @if($usuario->role != 'admin')
                                                        <button class="btn btn-sm btn-outline-danger" onclick="removeData({{$usuario->id}})" data-bs-toggle="tooltip" title="Remover">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        
                                                        <form action="{{ route('event_home.destroy_guest', $usuario->id) }}" method="POST" id="form-remove-{{$usuario->id}}" class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Modal de Remoção -->
        <div class="modal fade" id="modalMsgRemove" tabindex="-1" aria-labelledby="modalMsgRemoveLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMsgRemoveLabel">Remoção de Usuário Convidado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja realmente remover esse usuário?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btn-confirm-remove">Sim, remover</button>
                    </div>
                </div>
            </div>
        </div>
    
      </main><!-- End #main -->

      @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
      @endpush

      @push('footer')
        <script>
            let guestIdToRemove = null;

            function removeData(id){
                guestIdToRemove = id;
                var modal = new bootstrap.Modal(document.getElementById('modalMsgRemove'));
                modal.show();
            }

            document.getElementById('btn-confirm-remove').addEventListener('click', function() {
                if (guestIdToRemove) {
                    document.getElementById('form-remove-' + guestIdToRemove).submit();
                }
            });
            
            $(document).ready(function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            });
        </script>
      @endpush

</x-site-layout>