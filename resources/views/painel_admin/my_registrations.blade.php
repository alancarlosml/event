<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Minhas inscrições</h2>
    
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
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-head-fixed text-wrap hover" id="list_events">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Detalhes</th>
                            <th>Local</th>
                            <th>Data Compra</th>
                            <th>Status</th>
                            <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td>{{$event->id}}</td>
                                    <td>
                                        <b>Nome:</b> {{$event->name}} <br/>
                                        <b>Data evento:</b> @if($event->date_event_min == $event->date_event_max){{ \Carbon\Carbon::parse($event->date_event_min)->format('j/m/Y') }} @else De {{ \Carbon\Carbon::parse($event->date_event_min)->format('j/m/Y') }} <br/> a {{ \Carbon\Carbon::parse($event->date_event_max)->format('j/m/Y') }} @endif
                                    </td>
                                    <td>{{$event->place_name}}</td>
                                    {{-- <td>@if($event->date_event_min == $event->date_event_max){{ \Carbon\Carbon::parse($event->date_event_min)->format('j/m/Y') }} @else De {{ \Carbon\Carbon::parse($event->date_event_min)->format('j/m/Y') }} <br/> a {{ \Carbon\Carbon::parse($event->date_event_max)->format('j/m/Y') }} @endif</td> --}}
                                    <td>{{ \Carbon\Carbon::parse($event->created_at)->format('j/m/Y') }}</td>
                                    <td>
                                        @if($event->status == 1) 
                                            <span class="badge badge-success">Ativo</span> 
                                        @else
                                            <span class="badge badge-warning">Não ativo</span> 
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop" type="button" class="btn btn-primary btn-sm mr-1 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa-solid fa-gear"></i> Configurações
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop">
                                                    <a class="dropdown-item" href="{{route('event_home.my_events_show', $event->hash)}}">
                                                        <i class="fas fa-eye"></i>
                                                        Detalhes
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
      @endpush

      @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="../../../assets_admin/jquery.datetimepicker.min.css " rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
      @endpush

      @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="../../../assets_admin/jquery.datetimepicker.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
        });
    
    </script>
      
    @endpush

</x-site-layout>