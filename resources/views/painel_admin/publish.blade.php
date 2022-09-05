<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Gerenciar evento</h2>
    
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
                    <ul id="progressbar">
                        <li class="active" id="account"><strong>Informações</strong></li>
                        <li class="active" id="personal"><strong>Inscrições</strong></li>
                        <li class="active" id="payment"><strong>Cupons</strong></li>
                        <li class="active" id="confirm"><strong>Fim</strong></li>
                    </ul>
                    <form method="POST" action="{{route('event_home.publish', $hash_event)}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="hash_event" value="{{$hash_event}}">
                        <div class="card-body">
                            <h4>Aparência do site do evento</h4>
                            <label for="thems">Tema*</label><br/> 
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-default text-center">
                                    <input type="radio" name="color_option" id="color_option_a1" autocomplete="off" value="green" @if($event->theme == 'green') checked @endif>
                                    <i class="fas fa-circle fa-2x text-green"></i>
                                </label>
                                <label class="btn btn-default text-center">
                                    <input type="radio" name="color_option" id="color_option_a2" autocomplete="off" value="blue" @if($event->theme == 'blue') checked @endif>
                                    <i class="fas fa-circle fa-2x text-blue"></i>
                                </label>
                                <label class="btn btn-default text-center">
                                    <input type="radio" name="color_option" id="color_option_a3" autocomplete="off" value="purple" @if($event->theme == 'purple') checked @endif>
                                    <i class="fas fa-circle fa-2x text-purple"></i>
                                </label>
                                <label class="btn btn-default text-center">
                                    <input type="radio" name="color_option" id="color_option_a4" autocomplete="off" value="red" @if($event->theme == 'red') checked @endif>
                                    <i class="fas fa-circle fa-2x text-red"></i>
                                </label>
                                <label class="btn btn-default text-center">
                                    <input type="radio" name="color_option" id="color_option_a5" autocomplete="off" value="orange" @if($event->theme == 'orange') checked @endif>
                                    <i class="fas fa-circle fa-2x text-orange"></i>
                                </label>
                            </div>
                            <input type="hidden" name="theme" id="theme"  value="{{ $event->theme ?? '' }}">
                            <br/>
                            <br/>
                            <div class="form-group">
                                <label>Banner do evento*</label><br/>
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="banner_option" id="inlineRadio1" value="1" @if($event->banner_option == 1) checked @endif>
                                        <label class="form-check-label" for="inlineRadio1">Sem banner (apenas cor)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="banner_option" id="inlineRadio2" value="2" @if($event->banner_option == 2) checked @endif>
                                        <label class="form-check-label" for="inlineRadio2">Banner</label>
                                    </div>
                                </div>
                                <div id="banner_container">
                                    @if(isset($event->banner) == false)
                                        <input class="form-control" type="file" id="banner" name="banner">
                                    @else
                                    <div class="form-group">
                                        <img src="{{ asset('storage/'.$event->banner) }}" alt="Banner evento" class="img-fluid img-thumbnail" style="width: 400px">
                                        <a href="{{route('event_home.delete_file_event', $event->id)}}" class="btn btn-danger ml-1">Excluir</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <h4>Organização do evento</h4>
                            <input type="hidden" name="owner_id" value="{{$owner_id}}">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="owner_name">Organizador do evento*</label>
                                    <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Organizador" value="{{ $event->owner->name ?? '' }}" required>
                                    <small id="owner_nameHelp" class="form-text text-muted">Busque pelo local do evento, caso não o encontre, clique no botão a seguir para cadastrar um novo local.</small>
                                </div>
                                {{-- <div class="form-group col-md-3">
                                    <label for="place_name">Não encontrou o organizador?</label><br>
                                    <a class="btn btn-warning btn-sm mr-1" style="margin-top: 3px" href="javascript:;" id="add_owner">
                                        <i class="fa-solid fa-plus"></i>
                                        Adicionar
                                    </a>
                                </div>  --}}
                            </div>
                            {{-- <div class="form-group">
                                <label for="owner_email">Organizador*</label>
                                <input type="text" class="form-control col-lg-6 col-sm-12" id="owner_email" name="owner_email" placeholder="Organizador">
                            </div> --}}
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <textarea type="password" class="form-control" id="description" name="description" rows="6">{{ $event->owner->description ?? '' }}</textarea>
                            </div>
                            <div id="banner_organizador">
                                <label for="banner">Banner do organizador</label>
                                @if(isset($event->owner->icon) == false)
                                    <input class="form-control" type="file" id="icon" name="icon">
                                @else
                                <div class="form-group">
                                    <img src="{{ asset('storage/'.$event->owner->icon) }}" alt="Banner evento" class="img-fluid img-thumbnail" style="width: 200px">
                                    <a href="{{route('event_home.delete_file_icon', $event->owner->id)}}" class="btn btn-danger ml-1">Excluir</a>
                                </div>
                                @endif
                            </div>
                            <hr>
                            <h4>Publicar o evento</h4>
                            <div class="form-group">
                                <div class="custom-switch">
                                    <input type="checkbox" @if($event->status == 1) checked="checked" @endif class="custom-control-input" name="status" id="status" value="1">
                                    <label class="custom-control-label" for="status">Sim</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <a href="{{ route('event_home.create.step.three') }}" class="btn btn-primary">Anterior</a>
                            <button type="submit" class="btn btn-primary btn-lg float-right" style="margin-top: -5px"><i class="fa-solid fa-circle-check"></i> Salvar evento</button>
                        </div>
                    </form>
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

        $(document).ready(function() {

            // var_theme = $('#theme').val();

            // $("input[type='radio'][name='color_option']").each(function() {
            //     if($(this).val() == var_theme){
            //         console.log($(this).parent().attr('class'));
            //         $(this).parent().addClass('active');
            //     }
            // });

            // if($("input[type='radio'][name='color_option']").val() == var_theme){
            //     console.log($(this).parent().attr('class'));
            //     // $(this).parent('label').addClass('active');
            // }

            $("input[type='radio'][name='banner_option']").change(function(){

                val_banner_option = $(this).val();
                
                if(val_banner_option == 1) {
                    $('#banner_container').hide();
                } else {
                    $('#banner_container').show();
                }
            });

            $("input[type='radio'][name='color_option']").change(function(){

                val_color_option = $(this).val();
                
                $('#theme').val(val_color_option);
            });

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