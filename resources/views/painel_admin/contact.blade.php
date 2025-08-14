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
                            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
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

                // $('#description').summernote({
                //     placeholder: 'Descreva em detalhes o evento',
                //     tabsize: 2,
                //     height: 200
                // });

                // $('#name').keyup(function(e) {
                //     $.get('{{ route('event_home.check_slug') }}', 
                //         { 'title': $(this).val() }, 
                //         function( data ) {
                //             $('#slug').val(data.slug);
                //             if(data.slug_exists == '1'){
                //                 $('#slug').removeClass('is-valid');
                //                 $('#slug').addClass('is-invalid');
                //                 $('#slugHelp').removeClass('d-none');
                //             }else{
                //                 $('#slug').removeClass('is-invalid');
                //                 $('#slug').addClass('is-valid');
                //                 $('#slugHelp').addClass('d-none');
                //             }
                //         }
                //     );
                // });

                // $('#slug').keyup(function(e) {
                //     $.get('{{ route('event_home.create_slug') }}', 
                //         { 'title': $(this).val() }, 
                //         function( data ) {
                //             if(data.slug_exists == '1'){
                //                 $('#slug').removeClass('is-valid');
                //                 $('#slug').addClass('is-invalid');
                //             }else{
                //                 $('#slug').removeClass('is-invalid');
                //                 $('#slug').addClass('is-valid');
                //             }
                //         }
                //     );
                // });

                // $('#category').on('change', function() {
                //     var category_id = this.value;
                //     $("#area_id").html('');
                //     $.ajax({
                //         url:"{{ route('event_home.get_areas_by_category') }}",
                //         type: "POST",
                //         data: {
                //             category_id: category_id,
                //             _token: '{{ csrf_token() }}' 
                //         },
                //         dataType : 'json',
                //         success: function(result){
                //             $('#area_id').html('<option value="">Selecione</option>'); 
                //             $.each(result.areas,function(key,value){
                //                 $("#area_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                //             });
                //         }
                //     });
                // });

                // var category_id = $("#category").val();
                //     $("#area_id").html('');
                //     $.ajax({
                //         url:"{{ route('event_home.get_areas_by_category') }}",
                //         type: "POST",
                //         data: {
                //             category_id: category_id,
                //             _token: '{{ csrf_token() }}' 
                //         },
                //         dataType : 'json',
                //         success: function(result){
                //             $('#area_id').html('<option value="">Selecione</option>'); 
                //             area_id = $('#area_id_hidden').val();
                //             $.each(result.areas,function(key,value){
                //                 $("#area_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                //             });
                //             $('#area_id option[value='+area_id+']').attr('selected','selected');
                //         }
                //     });

                // $('#cmd').click(function(){
                //     $('#card-date').append('<div class="form-row">' + 
                //             '<div class="form-group col-md-3">' +
                //                 '<label for="number">Data</label>'+
                //                 '<div class="input-group date" data-target-input="nearest">'+
                //                     '<input class="form-control datetimepicker-input datetimepicker_day" name="date[]" value=""/>'+
                //                     '<div class="input-group-append" data-toggle="datetimepicker">'+
                //                         '<div class="input-group-text"><i class="fa fa-calendar"></i></div>'+
                //                     '</div>'+
                //                 '</div>'+
                //             '</div>'+
                //             '<div class="form-group col-md-2">'+
                //                 '<label for="number">Hora início</label>'+
                //                 '<div class="input-group date" data-target-input="nearest">'+
                //                     '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_begin" name="time_begin[]" value=""/>'+
                //                     '<div class="input-group-append" data-toggle="datetimepicker">'+
                //                         '<div class="input-group-text"><i class="fa-regular fa-clock"></i></div>'+
                //                     '</div>'+
                //                 '</div>'+
                //             '</div>'+
                //             '<div class="form-group col-md-2">'+
                //                 '<label for="number">Hora fim</label>'+
                //                 '<div class="input-group date" data-target-input="nearest">'+
                //                     '<input type="text" class="form-control datetimepicker-input datetimepicker_hour_end" name="time_end[]" value=""/>'+
                //                     '<div class="input-group-append" data-toggle="datetimepicker">'+
                //                         '<div class="input-group-text"><i class="fa-regular fa-clock"></i></div>'+
                //                     '</div>'+
                //                 '</div>'+
                //             '</div>'+
                //             '<div class="form-group col-md-2">'+
                //                 '<a class="btn btn-danger btn-sm mr-1 btn-remove" style="margin-top: 35px" href="javascript:;">'+
                //                     '<i class="fa-solid fa-remove"></i>'+
                //                     ' Remover'+
                //                 '</a>'+
                //             '</div>'+ 
                //         '</div>'
                //     );
                // });

                // var i_field = 2;
                // $('#add_new_field').click(function(){
                //     var field = $(this).parent().parent().find('#question').val();
                //     var option = $(this).parent().parent().find('#option').val();
                //     var option_text = $(this).parent().parent().find('#option:selected').text();
                //     var required = $(this).parent().parent().find('#required').is(":checked");
                //     var unique = $(this).parent().parent().find('#unique').is(":checked");

                //     if(field === ''){

                //         alert('preencha');
                //         return false;
                //     }

                //     var required_star = required ? '*':'';
                //     var field_text = '';
                //     var field_name = '';
                //     i_field = i_field+1;

                //     $('#question').val('');
                //     $('#option').prop('selectedIndex',0);
                //     $('#required').prop('checked',false);
                //     $('#unique').prop('checked',false);

                //     switch(option){
                //         case '1':
                //             field_text = '(Tipo: Texto (Até 200 caracteres))';
                //             field_name = 'text';
                //             break;
                //         case '2':
                //             field_text = '(Tipo: Seleção)';
                //             field_name = 'select';
                //             break;
                //         case '3':
                //             field_text = '(Tipo: Marcação)';
                //             field_name = 'checkbox';
                //             break;
                //         case '4':
                //             field_text = '(Tipo: Múltipla escolha)';
                //             field_name = 'multiselect';
                //             break;
                //         case '5':
                //             field_text = '(Tipo: CPF)';
                //             field_name = 'cpf';
                //             break;
                //         case '6':
                //             field_text = '(Tipo: CNPJ)';
                //             field_name = 'cnpj';
                //             break;
                //         case '7':
                //             field_text = '(Tipo: Data)';
                //             field_name = 'date';
                //             break;
                //         case '8':
                //             field_text = '(Tipo: Telefone)';
                //             field_name = 'phone';
                //             break;
                //         case '9':
                //             field_text = '(Tipo: Número inteiro)';
                //             field_name = 'integer';
                //             break;
                //         case '10':
                //             field_text = '(Tipo: Número decimal)';
                //             field_name = 'decimal';
                //             break;
                //         case '11':
                //             field_text = '(Tipo: Arquivo)';
                //             field_name = 'file';
                //             break;
                //         case '12':
                //             field_text = '(Tipo: Textarea (+ de 200 caracteres))';
                //             field_name = 'textearea';
                //             break;
                //         case '13':
                //             field_text = '(Tipo: Email)';
                //             field_name = 'new_email';
                //             break;
                //         case '14':
                //             field_text = '(Tipo: Estados (BRA))';
                //             field_name = 'states';
                //             break;
                //     }

                //     $('#card-new-field').append('<div class="form-row">' +
                //         '<div class="form-group col-10">'+
                //             '<label for="field_'+i_field+'">Campo ' + i_field + required_star + '</label>' +
                //             '<input type="text" class="form-control" name="'+field_name+'_new_field" value="'+field+' '+field_text +'" readonly>' +
                //         '</div>'+
                //         '<div class="form-group col-2">'+
                //             '<a class="btn btn-danger btn-sm mr-1 btn-remove-field" style="margin-top: 35px" href="javascript:;">'+
                //                 '<i class="fa-solid fa-remove"></i>'+
                //                 ' Remover'+
                //             '</a>'+
                //         '</div>'+
                //     '</div>');
                // });

                // $('#add_place').click(function(){
                //     $('#place_name').val('');
                //     $('#address').val('');
                //     $('#address').prop("readonly", false);
                //     $('#number').val('');
                //     $('#number').prop("readonly", false);
                //     $('#district').val('');
                //     $('#district').prop("readonly", false);
                //     $('#complement').val('');
                //     $('#complement').prop("readonly", false);
                //     $('#zip').val('');
                //     $('#zip').prop("readonly", false);

                //     $('#state').prop("disabled", false);
                //     $('#state').prop('selectedIndex',0);
                //     $('#city').prop("disabled", false);
                //     $('#city').prop('selectedIndex',0);
                //     $('#city_id_hidden').val('');

                //     // $('#state option[value="'+ui.item.uf+'"]').prop("selected", true);
                //     // $('#state').prop("readonly", true);
                //     // $('#city').prop("readonly", true);                 
                // });

                // var path = "{{ route('event_home.autocomplete_place') }}";
                // $("#place_name").autocomplete({
                //     source: function( request, response ) {
                //         $.ajax({
                //             url: path,
                //             type: 'GET',
                //             dataType: "json",
                //             data: {
                //                 search: request.term
                //             },
                //             success: function( data ) {
                //                 response(data);
                //             }
                //         });
                //     },
                //     select: function (event, ui) {
                //         $('#place_name').val(ui.item.label);
                //         $('#place_id_hidden').val(ui.item.id);
                //         $('#address').val(ui.item.address);
                //         $('#address').prop("readonly", true);
                //         $('#number').val(ui.item.number);
                //         $('#number').prop("readonly", true);
                //         $('#district').val(ui.item.district);
                //         $('#district').prop("readonly", true);
                //         $('#complement').val(ui.item.complement);
                //         $('#complement').prop("readonly", true);
                //         $('#zip').val(ui.item.zip);
                //         $('#zip').prop("readonly", true);

                //         $('#state option[value="'+ui.item.uf+'"]').prop("selected", true);
                //         $('#state').prop("disabled", true);
                //         $('#city').prop("disabled", true);

                //         var uf = $("#state").val();
                //         $("#city").html('');
                //         $.ajax({
                //             url:"{{ route('event_home.get_city') }}",
                //             type: "POST",
                //             data: {
                //                 uf: uf,
                //                 _token: '{{ csrf_token() }}' 
                //             },
                //             dataType : 'json',
                //             success: function(result){
                //                 $('#city').html('<option value="">Selecione</option>'); 
                //                 city_id = $('#city_id_hidden').val();

                //                 $.each(result.cities,function(key,value){
                //                     $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
                //                 });

                //                 $('#city option[value="'+ui.item.city_id+'"]').prop("selected", true);
                //                 $('#city_id_hidden').val(ui.item.city_id);
                //             }
                //         });

                //         return false;
                //     }
                // });

                // $('#state').on('change', function() {
                //     var uf = this.value;
                //     $("#city").html('');
                //     $.ajax({
                //         url: "{{ route('event_home.get_city') }}",
                //         type: "POST",
                //         data: {
                //             uf: uf,
                //             _token: '{{ csrf_token() }}' 
                //         },
                //         dataType : 'json',
                //         success: function(result){
                //             $('#city').html('<option value="">Selecione</option>'); 
                //             $.each(result.cities,function(key,value){
                //                 $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
                //             });
                //         }
                //     });
                // });

                // var uf = $("#state").val();
                // $("#city").html('');
                // $.ajax({
                //     url: "{{ route('event_home.get_city') }}",
                //     type: "POST",
                //     data: {
                //         uf: uf,
                //         _token: '{{ csrf_token() }}' 
                //     },
                //     dataType : 'json',
                //     success: function(result){
                //         $('#city').html('<option value="">Selecione</option>'); 
                //         city_id = $('#city_id_hidden').val();

                //         $.each(result.cities,function(key,value){
                //             $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
                //         });

                //         $('#city option[value='+city_id+']').attr('selected','selected');
                //     }
                // });

                // $('body').on('click',".btn-remove-field", function(){
                //     $(this).parent().parent().remove();
                //     i_field = i_field-1;
                // });

                // $('body').on('click',".btn-remove", function(){
                //     $(this).parent().parent().remove();
                // });

                // $('body').on('mousedown',".datetimepicker_day", function(){
                //     $(this).datetimepicker({
                //         timepicker:false,
                //         format:'d/m/Y',
                //         mask:true
                //     });
                // });

                // $('body').on('mousedown',".datetimepicker_hour_begin", function(){
                //     $(this).datetimepicker({
                //         datepicker:false,
                //         format:'H:i',
                //         mask:true,
                //         onShow:function( ct ){
                //             this.setOptions({
                //                 maxTime:$(this).val()?$(this).val():false
                //             })
                //         }
                //     });
                // });

                // $('body').on('mousedown',".datetimepicker_hour_end", function(){
                //     $(this).datetimepicker({
                //         datepicker:false,
                //         format:'H:i',
                //         mask:true,
                //         onShow:function( ct ){
                //             this.setOptions({
                //                 minTime:$(this).val()?$(this).val():false
                //             })
                //         }
                //     });
                // });
            });
        </script>
    @endpush

</x-site-layout>
