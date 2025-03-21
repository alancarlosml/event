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
                                        <td>@if($usuario->status == 1) <span class="badge badge-success">Ativo</span> @else <span class="badge badge-danger">Não ativo</span> @endif</td>
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

      @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
      @endpush

      {{-- @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link href="../../../assets_admin/jquery.datetimepicker.min.css " rel="stylesheet">
          
      @endpush --}}

      @push('footer')
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="../../../assets_admin/jquery.datetimepicker.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

        <script>

        function removeData(id){
            $('#modalMsgRemove-' + id).modal('show');
        }

        function removeSucc(id){
            $('#btn-remove-hidden-' + id).click();
        }

        // $(document).ready(function() {

        //     $('.order_lote').change(function(){
        //         id = $(this).attr('id');
        //         value = $(this).val();
        //         console.log(value);
        //         $('#lote_' + id).val(id + '_' + value);
        //     });

        //     $(".up,.down").click(function () {
               
        //        var $element = this;
        //        var row = $($element).parents("tr:first");

               
        //        if($(this).is('.up')){
        //             hash_this = $(this).parents('tr').find('.lote_hash').text();
        //             hash_prev = row.prev().find('.lote_hash').text();
        //             if(hash_prev != ''){
        //                 console.log(hash_prev);
        //                     val_this = $('#lote_' + hash_this).val();
        //                     val_prev = $('#lote_' + hash_prev).val();
        //                     id_this = parseInt(val_this.split('_')[1]) - 1;
        //                     id_prev = parseInt(val_prev.split('_')[1]) + 1;
        //                     $('#lote_' + hash_this).val(hash_this + '_' + id_this);
        //                     $('#lote_' + hash_prev).val(hash_prev + '_' + id_prev);
        //                     console.log(id_this);
        //                     console.log(id_prev);
        //                     row.insertBefore(row.prev());
        //             }
        //        }
        //        else{
        //             hash_this = $(this).parents('tr').find('.lote_hash').text();
        //             hash_next = row.next().find('.lote_hash').text();
        //             if(hash_next != ''){
        //                 console.log(hash_next);
        //                     val_this = $('#lote_' + hash_this).val();
        //                     val_next = $('#lote_' + hash_next).val();
        //                     id_this = parseInt(val_this.split('_')[1]) + 1;
        //                     id_next = parseInt(val_next.split('_')[1]) - 1;
        //                     $('#lote_' + hash_this).val(hash_this + '_' + id_this);
        //                     $('#lote_' + hash_next).val(hash_next + '_' + id_next);
        //                     console.log(id_this);
        //                     console.log(id_next);
        //                     row.insertAfter(row.next());
        //             }
        //        }
        //   });

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
            //         url:"{{route('event_home.get_areas_by_category')}}",
            //         type: "POST",
            //         data: {
            //             category_id: category_id,
            //             _token: '{{csrf_token()}}' 
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
            //         $('#event_address').toggle();                    
            //     });

            // var path = "{{route('event_home.autocomplete_place')}}";
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
            //         $('#address').val(ui.item.address);
            //         $('#number').val(ui.item.number);
            //         $('#district').val(ui.item.district);
            //         $('#complement').val(ui.item.complement);
            //         $('#zip').val(ui.item.zip);

            //         $('#state option[value="'+ui.item.uf+'"]').prop("selected", true);
                    
            //         var uf = $("#state").val();
            //         $("#city").html('');
            //         $.ajax({
            //             url:"{{url('admin/places/get-cities-by-state')}}",
            //             type: "POST",
            //             data: {
            //                 uf: uf,
            //                 _token: '{{csrf_token()}}' 
            //             },
            //             dataType : 'json',
            //             success: function(result){
            //                 $('#city').html('<option value="">Selecione</option>'); 
            //                 city_id = $('#city_id_hidden').val();

            //                 $.each(result.cities,function(key,value){
            //                     $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
            //                 });

            //                 $('#city option[value="'+ui.item.city_id+'"]').prop("selected", true);
            //             }
            //         });

            //         return false;
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
        // });
    
    </script>
      
    @endpush

</x-site-layout>