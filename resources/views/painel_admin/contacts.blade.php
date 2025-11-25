<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                    <li>Contatos</li>
                </ol>
                <h2>Gerenciar Contatos</h2>
            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page" id="contact-list">
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
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0">Mensagens Recebidas</h4>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog me-1"></i> Ações em massa
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#" id="checkAllRead"><i class="fas fa-envelope-open me-2"></i>Marcar como lida</a></li>
                                <li><a class="dropdown-item" href="#" id="checkAllUnread"><i class="fas fa-envelope me-2"></i>Marcar como não lida</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" id="deleteAll"><i class="fas fa-trash me-2"></i>Deletar selecionadas</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="list_events">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;"><input type="checkbox" class="form-check-input" id="checkAll"/></th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Assunto</th>
                                        <th>Data</th>
                                        <th style="width: 100px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($messages as $message)
                                        <tr data-message-id="{{ $message->id }}" class="{{ $message->read == 0 ? 'fw-bold bg-light-primary' : '' }}">
                                            <td><input type="checkbox" class="form-check-input message-checkbox" /></td>
                                            <td onclick="window.location='{{ route('event_home.show_message', $message->id) }}';" style="cursor: pointer;">
                                                {{ $message->name }}
                                                @if($message->read == 0) <span class="badge bg-primary ms-1">Novo</span> @endif
                                            </td>
                                            <td onclick="window.location='{{ route('event_home.show_message', $message->id) }}';" style="cursor: pointer;">{{ $message->email }}</td>
                                            <td onclick="window.location='{{ route('event_home.show_message', $message->id) }}';" style="cursor: pointer;">{{ $message->phone }}</td>
                                            <td onclick="window.location='{{ route('event_home.show_message', $message->id) }}';" style="cursor: pointer;">{{ Str::limit($message->subject, 30) }}</td>
                                            <td onclick="window.location='{{ route('event_home.show_message', $message->id) }}';" style="cursor: pointer;">{{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('event_home.show_message', $message->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver mensagem">
                                                    <i class="fas fa-eye"></i>
                                                </a>
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

        <!-- Modal de Confirmação de Exclusão em Massa -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Tem certeza de que deseja excluir todas as mensagens selecionadas? Esta ação não pode ser desfeita.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Sim, excluir</button>
                    </div>
                </div>
            </div>
        </div>

    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
        <style>
            .bg-light-primary {
                background-color: #f8f9fa; /* Light gray for unread, or use a subtle blue tint */
            }
            tr.fw-bold td {
                font-weight: 600;
                color: #2c3e50;
            }
        </style>
    @endpush

    @push('footer')
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

        <script>
            $(document).ready(function() {
                // Inicializa Tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                // Inicializa DataTable
                var table = $('#list_events').DataTable({
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                    },
                    order: [[ 5, "desc" ]], // Ordenar por data decrescente
                    columnDefs: [
                        { orderable: false, targets: [0, 6] } // Desabilitar ordenação na coluna de checkbox e ações
                    ]
                });

                // Checkbox "Selecionar Todos"
                $('#checkAll').on('change', function() {
                    $('.message-checkbox').prop('checked', $(this).prop('checked'));
                });

                // Ações em massa
                function getSelectedIds() {
                    var ids = [];
                    $('.message-checkbox:checked').each(function() {
                        ids.push($(this).closest('tr').data('message-id'));
                    });
                    return ids;
                }

                $('#checkAllRead').click(function(e) {
                    e.preventDefault();
                    var ids = getSelectedIds();
                    if(ids.length === 0) {
                        showToast('Selecione pelo menos uma mensagem.', 'warning');
                        return;
                    }
                    
                    $.ajax({
                        url: "{{ route('event_home.marcar_como_lida') }}",
                        type: 'POST',
                        data: { action: 'marcarLida', ids: ids, _token: '{{ csrf_token() }}' },
                        success: function() { location.reload(); },
                        error: function() { showToast('Erro ao processar requisição.', 'error'); }
                    });
                });

                $('#checkAllUnread').click(function(e) {
                    e.preventDefault();
                    var ids = getSelectedIds();
                    if(ids.length === 0) {
                        showToast('Selecione pelo menos uma mensagem.', 'warning');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('event_home.marcar_como_nao_lida') }}",
                        type: 'POST',
                        data: { action: 'marcarNaoLida', ids: ids, _token: '{{ csrf_token() }}' },
                        success: function() { location.reload(); },
                        error: function() { showToast('Erro ao processar requisição.', 'error'); }
                    });
                });

                $('#deleteAll').click(function(e) {
                    e.preventDefault();
                    var ids = getSelectedIds();
                    if(ids.length === 0) {
                        showToast('Selecione pelo menos uma mensagem.', 'warning');
                        return;
                    }
                    var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                    modal.show();
                });

                $('#confirmDeleteButton').click(function() {
                    var ids = getSelectedIds();
                    var btn = $(this);
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');

                    $.ajax({
                        url: "{{ route('event_home.deletar_mensagens') }}",
                        type: 'POST',
                        data: { action: 'deletarMensagens', ids: ids, _token: '{{ csrf_token() }}' },
                        success: function() { location.reload(); },
                        error: function() { 
                            showToast('Erro ao excluir mensagens.', 'error'); 
                            btn.prop('disabled', false).text('Sim, excluir');
                        }
                    });
                });
            });
        </script>
    @endpush

</x-site-layout>
