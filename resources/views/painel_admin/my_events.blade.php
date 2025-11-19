<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">Meus eventos</a></li>
            </ol>
            <h2>Listar todos</h2>
    
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
                <div class="card-body table-responsive p-0">
                    <table class="table table-head-fixed text-wrap hover" id="list_events">
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
                                <tr data-event-id="{{$event->hash}}" @if($event->place_name == "" || $event->participante_name == "" || $event->event_date == "" || $event->lote_name == "") style="background:#faceca" @endif>
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
                                    {{-- <td>@if($event->date_event_min == $event->date_event_max){{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }} @else De {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }} <br/> a {{ \Carbon\Carbon::parse($event->date_event_max)->format('d/m/Y') }} @endif</td> --}}
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
                                               class="badge bg-danger" 
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
                                        <div class="d-flex">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop" type="button" class="btn btn-primary btn-sm me-1 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa-solid fa-gear"></i> Ações
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop">
                                                    @if($event->place_name != "" && $event->participante_name != "" && $event->event_date != "" && $event->lote_name != "")
                                                    <a class="dropdown-item" href="/{{ $event->slug }}" target="_blank">
                                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                        Link evento
                                                    </a>
                                                    @endif
                                                    <a class="dropdown-item" href="{{route('event_home.reports', $event->hash)}}">
                                                        <i class="fa-solid fa-chart-pie"></i>
                                                        Relatórios
                                                    </a>
                                                    <a class="dropdown-item" href="{{route('event_home.my_events_show', $event->hash)}}">
                                                        <i class="fas fa-eye"></i>
                                                        Detalhes
                                                    </a>
                                                    @if($event->role == 'admin')
                                                        <a class="dropdown-item" href="{{route('event_home.guests', $event->hash)}}">
                                                            <i class="fas fa-person"></i>
                                                            Usuários
                                                        </a>
                                                        <a class="dropdown-item" href="{{route('event_home.messages', $event->hash)}}">
                                                            <i class="fa-solid fa-envelope"></i>
                                                            Contatos
                                                        </a>
                                                        <a class="dropdown-item" href="{{route('event_home.event_clone', $event->hash)}}">
                                                            <i class="fa-solid fa-copy"></i>
                                                            Duplicar
                                                        </a>
                                                        <a class="dropdown-item" href="{{route('event_home.my_events_edit', $event->hash)}}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            Editar
                                                        </a>
                                                        <form action="{{ route('event_home.destroy', $event->hash) }}" method="POST">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <a href="javascript:;" class="dropdown-item" onclick="removeData('{{$event->hash}}')">
                                                                <i class="fas fa-trash"></i>
                                                                Remover
                                                            </a>
                                                            <button class="d-none" id="btn-remove-hidden-{{$event->hash}}">Remover</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <div class="modal fade modalMsgRemove" id="modalMsgRemove-{{$event->hash}}" tabindex="-1" role="dialog" aria-labelledby="modalMsgRemoveLabel" aria-hidden="true">
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
                                                    <button type="button" class="btn btn-danger" id="btn-remove-ok-{{$event->hash}}" onclick="removeSucc('{{$event->hash}}')">Sim</button>
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
        </section>
    
      </main><!-- End #main -->

      @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="{{ asset('assets_admin/jquery.datetimepicker.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('assets_admin/css/painel-admin-improvements.css') }}" type="text/css">
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

        function removeData(id){
            $('#modalMsgRemove-' + id).modal('show');
        }

        function removeSucc(id){
            const button = $('#btn-remove-ok-' + id);
            const originalText = button.text();
            
            // Mostra loading no botão
            setButtonLoading(button[0], 'Excluindo...');
            
            // Executa a remoção
            $('#btn-remove-hidden-' + id).click();
            
            // Fecha o modal
            $('#modalMsgRemove-' + id).modal('hide');
            
            // Mostra notificação de sucesso
            showToast('Evento removido com sucesso!', 'success');
            
            // Remove a linha da tabela após um pequeno delay
            setTimeout(() => {
                const row = $(`tr[data-event-id="${id}"]`);
                if (row.length) {
                    row.fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            }, 500);
        }

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
                        title: 'Situação do pagamento dos inscritos',
                        exportOptions: {
                            modifier: {
                                page: 'current'
                            },
                            columns: [ 0, 1, 2, 3, 5 ],
                            stripNewlines: false,
                            format: {
                                body: function(data, column, row) {
                                    if (typeof data === 'string' || data instanceof String) {
                                        data = data.replace(/<br>/gi, "").replace(/<small>/gi, " - ").replace(/<\/small>/gi, "").replace(/<b>/gi, "").replace(/<\/b>/gi, "");
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Situação do pagamento dos inscritos',
                        exportOptions: {
                            modifier: {
                                page: 'current'
                            },
                            columns: [ 0, 1, 2, 3, 5 ],
                            format: {
                                body: function(data, column, row) {
                                    if (typeof data === 'string' || data instanceof String) {
                                        data = data.replace(/<br>/gi, "").replace(/<small>/gi, " - ").replace(/<\/small>/gi, "").replace(/<b>/gi, "").replace(/<\/b>/gi, "");
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'Situação do pagamento dos inscritos',
                        exportOptions: {
                            modifier: {
                                page: 'current'
                            },
                            columns: [ 0, 1, 2, 3, 5 ],
                            stripNewlines: false
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        title: 'Situação do pagamento dos inscritos',
                        exportOptions: {
                            modifier: {
                                page: 'current'
                            },
                            columns: [ 0, 1, 2, 3, 5 ],
                            stripHtml: false
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