<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                </ol>
                <h2>Contatos</h2>

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
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erros encontrados:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body table-responsive p-0">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <p class="text-muted" style="font-size: 18px">
                                {{ $contact->name }}
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <p class="text-muted" style="font-size: 18px">
                                {{ $contact->email }}
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefone</label>
                            <p class="text-muted" style="font-size: 18px">
                                {{ $contact->phone }}
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="subject">Assunto</label>
                            <p class="text-muted" style="font-size: 18px">
                                {{ $contact->subject }}
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="text">Texto</label>
                            <p class="text-muted" style="font-size: 18px">
                                {{ $contact->text }}
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="created_at">Data de criação</label>
                            <p class="text-muted" style="font-size: 18px">
                                {{ \Carbon\Carbon::parse($contact->created_at)->format('j/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('event_home.messages', $contact->event->hash) }}" class="btn btn-primary">Voltar</a>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
              integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="{{ asset('assets_admin/jquery.datetimepicker.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
                integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
            $(document).ready(function() {

                $('#list_events').DataTable({
                    language: {
                        "decimal": "",
                        "emptyTable": "Sem dados disponíveis na tabela",
                        "info": "Exibindo _START_ de _END_ de um total de _TOTAL_ registros",
                        "infoEmpty": "Exibindo 0 de 0 de um total de 0 registros",
                        "infoFiltered": "(filtrados do total de _MAX_ registros)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Exibir _MENU_ registros",
                        "loadingRecords": "Carregando...",
                        "processing": "",
                        "search": "Busca: ",
                        "zeroRecords": "Nenhum registro correspondente encontrado",
                        "paginate": {
                            "first": "Primeiro",
                            "last": "Último",
                            "next": "Próximo",
                            "previous": "Anterior"
                        },
                        "aria": {
                            "sortAscending": ": ative para classificar a coluna em ordem crescente",
                            "sortDescending": ": ativar para ordenar a coluna decrescente"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'csv',
                            text: 'CSV',
                            title: 'Listagem das mensagens recebidas - Evento',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3, 5],
                                stripNewlines: false,
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            title: 'Listagem das mensagens recebidas - Evento',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3, 5],
                                format: {
                                    body: function(data, column, row) {
                                        if (typeof data === 'string' || data instanceof String) {
                                            data = data.replace(/<br>/gi, "").replace(/<small>/gi,
                                                " - ").replace(/<\/small>/gi, "").replace(/<b>/gi,
                                                "").replace(/<\/b>/gi, "");
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'Listagem das mensagens recebidas - Evento',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3, 5],
                                stripNewlines: false
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Listagem das mensagens recebidas - Evento',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                },
                                columns: [0, 1, 2, 3, 5],
                                stripHtml: false
                            }
                        }
                    ]
                });
            });
        </script>
    @endpush

</x-site-layout>
