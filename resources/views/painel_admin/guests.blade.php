<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">Meus eventos</a></li>
            </ol>
            <h2>Gerenciar usuários convidados</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="form-group pl-3 pr-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erros encontrados:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body table-responsive p-0">
                    <div class="card-body">
                        {{-- <h4>Listar todos</h4> --}}
                        <div class="form-group text-right">
                            <a href="{{route('event_home.guest_add', $event->hash)}}" class="btn btn-success">Cadastrar novo usuário</a>
                        </div>
                        <table class="table table-head-fixed text-nowrap" id="table_lotes">
                            <thead>
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
                                    <tr>
                                        <td>{{$usuario->id}}</td>
                                        <td>{{$usuario->name}}</td>
                                        <td>{{$usuario->email}}</td>
                                        <td>@if($usuario->role == 'admin') Admin @else Convidado @endif</td>
                                        <td>@if($usuario->status == 1) <span class="badge bg-success">Ativo</span> @else <span class="badge bg-danger">Não ativo</span> @endif</td>
                                        <td>
                                            <div class="d-flex">
                                                <a class="btn btn-info btn-sm mr-1" href="{{route('event_home.guest_edit', $usuario->id)}}">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>
                                                    Editar
                                                </a>
                                                @if($usuario->role != 'admin')
                                                    <form action="{{ route('event_home.destroy_guest', $usuario->id) }}" method="POST">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <a class="btn btn-danger btn-sm mr-1"  href="javascript:;" onclick="removeData({{$usuario->id}})">
                                                            <i class="fas fa-trash">
                                                            </i>
                                                            Remover
                                                        </a>
                                                        <button class="d-none" id="btn-remove-hidden-{{$usuario->id}}">Remover</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                        <div class="modal fade modalMsgRemove" id="modalMsgRemove-{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="modalMsgRemoveLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalMsgRemoveLabel">Remoção de Usuário Convidado</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Deseja realmente remover esse usuário?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" id="btn-remove-ok-{{$usuario->id}}" onclick="removeSucc({{$usuario->id}})">Sim</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('footer')

        <script>

            function removeData(id){
                $('#modalMsgRemove-' + id).modal('show');
            }

            function removeSucc(id){
                const button = $('#btn-remove-ok-' + id);
                
                // Mostra loading no botão
                setButtonLoading(button[0], 'Excluindo...');
                
                // Executa a remoção
                $('#btn-remove-hidden-' + id).click();
                
                // Fecha o modal
                $('#modalMsgRemove-' + id).modal('hide');
                
                // Mostra notificação de sucesso
                showToast('Usuário removido com sucesso!', 'success');
                
                // Remove a linha da tabela após um pequeno delay
                setTimeout(() => {
                    const row = $(`tr[data-guest-id="${id}"]`);
                    if (row.length) {
                        row.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                }, 500);
            }

        </script>
      
    @endpush

</x-site-layout>