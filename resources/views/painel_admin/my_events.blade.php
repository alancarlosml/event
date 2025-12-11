<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">{{ $isSuperAdmin ?? false ? 'Todos os eventos' : 'Meus eventos' }}</a></li>
            </ol>
            <h2>{{ $isSuperAdmin ?? false ? 'Todos os eventos da plataforma' : 'Listar todos' }}</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="mb-3 ps-3 pe-3">
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

                <!-- Toast Container -->
                <div id="toast-container" class="toast-container"></div>

                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-hover" id="list_events">
                            <thead>
                                <tr>
                                <th>ID</th>
                                <th>Detalhes</th>
                                <th>Responsável</th>
                                <th>Local</th>
                                <th>Data Criação</th>
                                <th>Status</th>
                                <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    @php
                                        $isIncomplete = $event->place_name == "" || $event->participante_name == "" || $event->event_date == "" || $event->lote_name == "";
                                    @endphp
                                    <tr data-event-id="{{$event->hash}}" class="{{ $isIncomplete ? 'table-warning' : '' }}">
                                        <td>{{$event->id}}</td>
                                        <td>
                                            <b>Nome:</b> {{$event->name}} <br/>
                                            <b>Data do evento:</b> @if($event->date_event_min == $event->date_event_max){{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }} @else De {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($event->date_event_max)->format('d/m/Y') }} @endif
                                        </td>
                                        <td>
                                            {{$event->admin_name}} <br>
                                            <small>{{$event->admin_email}}</small>
                                        </td>
                                        <td>{{$event->place_name}}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            @php
                                                $missing = [];
                                                if(empty($event->place_name)) $missing[] = 'Cadastrar local do evento';
                                                if(empty($event->event_date)) $missing[] = 'Cadastrar data do evento';
                                                if(empty($event->lote_name)) $missing[] = 'Cadastrar lotes';
                                                $popoverContent = implode('<br>', array_map(fn($item) => "- {$item}", $missing));
                                            @endphp
                                            @if(!empty($missing))
                                                <a href="javascript:void(0)" 
                                                   class="badge bg-danger text-decoration-none" 
                                                   data-bs-toggle="popover" 
                                                   data-bs-trigger="hover" 
                                                   data-bs-html="true"
                                                   data-bs-title="O que falta?" 
                                                   data-bs-content="{{ $popoverContent }}">
                                                    Incompleto
                                                </a>
                                            @else
                                                @if($event->status == 1) 
                                                    <span class="badge bg-success">Ativo</span> 
                                                @else
                                                    <span class="badge bg-warning">Não ativo</span> 
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton-{{$event->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa-solid fa-gear"></i> Ações
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{$event->id}}">
                                                    @if($event->place_name != "" && $event->participante_name != "" && $event->event_date != "" && $event->lote_name != "")
                                                    <li>
                                                        <a class="dropdown-item" href="/{{ $event->slug }}" target="_blank">
                                                            <i class="fa-solid fa-arrow-up-right-from-square me-2"></i>Link evento
                                                        </a>
                                                    </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item" href="{{route('event_home.reports', $event->hash)}}">
                                                            <i class="fa-solid fa-chart-pie me-2"></i>Relatórios
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{route('event_home.my_events_show', $event->hash)}}">
                                                            <i class="fas fa-eye me-2"></i>Detalhes
                                                        </a>
                                                    </li>
                                                    @if($event->role == 'admin' || ($isSuperAdmin ?? false))
                                                        <li>
                                                            <a class="dropdown-item" href="{{route('event_home.guests', $event->hash)}}">
                                                                <i class="fas fa-person me-2"></i>Usuários
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{route('event_home.messages', $event->hash)}}">
                                                                <i class="fa-solid fa-envelope me-2"></i>Contatos
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{route('event_home.event_clone', $event->hash)}}">
                                                                <i class="fa-solid fa-copy me-2"></i>Duplicar
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{route('event_home.my_events_edit', $event->hash)}}">
                                                                <i class="fas fa-pencil-alt me-2"></i>Editar
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a href="javascript:;" class="dropdown-item text-danger" onclick="removeData('{{$event->hash}}')">
                                                                <i class="fas fa-trash me-2"></i>Remover
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      <!-- Modal de Remoção (Fora do Loop) -->
      <div class="modal fade" id="modalMsgRemove" tabindex="-1" role="dialog" aria-labelledby="modalMsgRemoveLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="modalMsgRemoveLabel">Remoção de evento</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                  </div>
                  <div class="modal-body">
                      Deseja realmente remover esse evento?
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                      <button type="button" class="btn btn-danger" id="btn-remove-confirm">Sim</button>
                  </div>
              </div>
          </div>
      </div>

      <!-- Form para remoção -->
      <form id="form-remove-event" method="POST" style="display: none;">
          @csrf
          @method('DELETE')
      </form>

      @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="{{ asset('assets_admin/jquery.datetimepicker.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
        <style>
            /* Corrigir dropdown cortado - apenas z-index */
            #list_events .dropdown-menu {
                z-index: 1050 !important;
            }
        </style>
      @endpush

      @push('footer')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="{{ asset('assets_admin/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

        <script>
        // Função Toast
        function showToast(message, type = 'info', duration = 3000) {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas ${icons[type] || icons.info}"></i>
                </div>
                <div class="toast-message">${message}</div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 10);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        let eventHashToRemove = null;

        function removeData(hash){
            eventHashToRemove = hash;
            $('#modalMsgRemove').modal('show');
        }

        $('#btn-remove-confirm').click(function() {
            if(eventHashToRemove) {
                const button = $(this);
                const originalText = button.text();
                
                // Loading state
                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');
                button.prop('disabled', true);

                // Configurar e submeter o formulário
                const form = $('#form-remove-event');
                form.attr('action', '/painel/meus-eventos/' + eventHashToRemove); // Ajuste a rota conforme necessário
                
                // Como o método DELETE é simulado pelo Laravel, precisamos garantir que o form tenha o _method input
                // O form já tem @method('DELETE') que gera <input type="hidden" name="_method" value="DELETE">
                
                // Submeter via AJAX para melhor UX (opcional, mas mantendo o padrão do arquivo anterior)
                // Ou submeter normal. O código anterior usava um form hidden por linha.
                // Vamos submeter o form normalmente para garantir compatibilidade com o backend
                form.submit();
            }
        });

        $(document).ready(function() {
            // Inicializa os popovers manualmente
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.forEach(function (popoverTriggerEl) {
                var popover = new bootstrap.Popover(popoverTriggerEl, {
                    html: true,
                    trigger: 'hover',
                    container: 'body',
                    sanitize: false
                });
                
                // Força a exibição do popover no hover
                popoverTriggerEl.addEventListener('mouseenter', function() {
                    popover.show();
                });
                
                popoverTriggerEl.addEventListener('mouseleave', function() {
                    setTimeout(function() {
                        if (!$(popover._element).is(':hover') && !$(popover._tip).is(':hover')) {
                            popover.hide();
                        }
                    }, 100);
                });
                
                // Evita que o popover feche ao mover o mouse para ele
                $(document).on('mouseenter', '.popover', function() {
                    $('[data-bs-toggle="popover"]').each(function() {
                        var popover = bootstrap.Popover.getInstance(this);
                        if (popover) {
                            clearTimeout($(this).data('bs.popover')._timeout);
                        }
                    });
                }).on('mouseleave', '.popover', function() {
                    $('[data-bs-toggle="popover"]').each(function() {
                        var popover = bootstrap.Popover.getInstance(this);
                        if (popover) {
                            popover.hide();
                        }
                    });
                });
            });

            var table = $('#list_events').DataTable({
                order: [[0, 'desc']],
                language: {
                    "decimal":        "",
                    "emptyTable":     "Sem dados disponíveis na tabela",
                    "info":           "Exibindo _START_ de _END_ de um total de _TOTAL_ registros",
                    "infoEmpty":      "Exibindo 0 de 0 de um total de 0 registros",
                    "infoFiltered":   "(filtrados do total de _MAX_ registros)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "Exibir _MENU_ registros",
                    "loadingRecords": "Carregando...",
                    "processing":     "",
                    "search":         "Busca: ",
                    "zeroRecords":    "Nenhum registro correspondente encontrado",
                    "paginate": {
                        "first":      "Primeiro",
                        "last":       "Último",
                        "next":       "Próximo",
                        "previous":   "Anterior"
                    },
                    "aria": {
                        "sortAscending":  ": ative para classificar a coluna em ordem crescente",
                        "sortDescending": ": ativar para ordenar a coluna decrescente"
                    }
                },
                dom: 'Bfrtip',
                scrollX: false,
                autoWidth: false,
                buttons: [
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Meus Eventos',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 5 ]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Meus Eventos',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 5 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'Meus Eventos',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 5 ]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        title: 'Meus Eventos',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 5 ]
                        }
                    }
                ]
            });
            
            // Corrigir dropdown cortado - mostrar acima quando está na última linha
            $(document).on('show.bs.dropdown', '#list_events .dropdown', function(e) {
                var $dropdown = $(this);
                var $menu = $dropdown.find('.dropdown-menu');
                var $row = $dropdown.closest('tr');
                
                // Verificar se é uma das últimas linhas
                var $tbody = $row.closest('tbody');
                var totalRows = $tbody.find('tr').length;
                var rowIndex = $row.index();
                var isLastRows = rowIndex >= totalRows - 3; // Últimas 3 linhas
                
                if (isLastRows) {
                    // Verificar se há espaço abaixo
                    var dropdownBottom = $dropdown.offset().top + $dropdown.outerHeight();
                    var menuHeight = $menu.outerHeight() || 200; // Altura estimada
                    var windowBottom = $(window).scrollTop() + $(window).height();
                    var tableBottom = $dropdown.closest('table').offset().top + $dropdown.closest('table').outerHeight();
                    
                    // Se não há espaço suficiente abaixo, mostrar acima
                    if (dropdownBottom + menuHeight > Math.min(windowBottom, tableBottom + 50)) {
                        $menu.addClass('dropup');
                        $menu.css({
                            'top': 'auto',
                            'bottom': '100%',
                            'margin-bottom': '0.125rem',
                            'margin-top': '0'
                        });
                    } else {
                        $menu.removeClass('dropup');
                        $menu.css({
                            'top': '',
                            'bottom': '',
                            'margin-bottom': '',
                            'margin-top': ''
                        });
                    }
                } else {
                    // Resetar estilos se não for última linha
                    $menu.removeClass('dropup');
                    $menu.css({
                        'top': '',
                        'bottom': '',
                        'margin-bottom': '',
                        'margin-top': ''
                    });
                }
            });
        });
    
    </script>
      
    @endpush

</x-site-layout>