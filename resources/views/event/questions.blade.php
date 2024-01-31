<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Eventos</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Questionário - {{ $event->name }}</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
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
                            <form method="POST" action="{{ route('event.questions.create', $id) }}">
                                @csrf
                                <div class="card-body table-responsive p-0">
                                    <div class="card-body">
                                        <div class="card p-2 pr-4 mb-2">
                                            <label for="question">Novo campo</label>
                                            <div class="form-row" style="margin:0">
                                                <div class="form-group col-md-5">
                                                    <input type="text" class="form-control" id="question" name="question" placeholder="Nome do campo" value="">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-sm-12">
                                                            <select id="option" class="form-control" name="option" required>
                                                                @foreach ($options as $option)
                                                                    <option value="{{$option->id}}">{{$option->option}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-sm-12">
                                                            <label for="required" style="margin-top:2px; padding: 5px 10px; border: solid 1px #ccc; border-radius: 10px;">
                                                                <input type="checkbox" name="required" id="required" value="1"> <b>Obrigatório</b>
                                                            </label>
                                                            <label for="unique" style="margin-left: 5px; margin-top:2px; padding: 5px 10px; border: solid 1px #ccc; border-radius: 10px;">
                                                                <input type="checkbox" name="unique" id="unique" value="1"> <b>Único</b>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-sm-12" id="div_new_options" style="display: none">
                                                            <label for="">Preencha as opções separadas por vírgula</label>
                                                            <input type="text" class="form-control" name="new_options" id="new_options">
                                                        </div>
                                                        <div class="form-group col-md-12 col-sm-12" id="div_new_number" style="display: none">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label for="">Mínimo</label>
                                                                    <input type="number" class="form-control val_min_option" name="val_min" min="0">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="">Máximo</label>
                                                                    <input type="number" class="form-control val_max_option" name="val_max" min="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-1 col-sm-3" style="margin-top: 1px;">
                                                    <button type="button" class="btn btn-success" id="add_new_field">Adicionar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="card-new-field" style="margin:0">
                                            @if(count($questions) > 0)
                                                @foreach ($questions as $id => $question)
                                                    @php
                                                        $var_options = $question->question . "; (Tipo: " . $question->option->option . ")";
                                                        if(count($question->value()) > 0){
                                                            $value_array = array();
                                                            foreach($question->value() as $value_array_item){
                                                                array_push($value_array, $value_array_item->value);
                                                            }
                                                            $var_options = $var_options . "; [Opções: " . implode(",", $value_array) . "]";
                                                        }
                                                        
                                                        if($question->required == 1){
                                                            $var_options = $var_options . '; Obrigatório';
                                                        }     
                                                        if($question->unique == 1){
                                                            $var_options = $var_options . '; Único';
                                                        }    
                                                        
                                                    @endphp
                                                    @if($id<2)
                                                        <div class="form-group">
                                                            <label for="new_field">Campo {{$id+1}}@if($question->required == 1)* @endif</label>
                                                            <input type="text" class="form-control new_field" name="new_field[]" value="{{$var_options}}" readonly>
                                                            <input type="hidden" name="new_field_id[]" value="{{$question->id}}"/>
                                                        </div>
                                                    @else
                                                        <div class="row">
                                                            <div class="form-group col-9">
                                                                <label for="new_field">Campo {{$id+1}}@if($question->required == 1)* @endif</label>
                                                                <input type="text" class="form-control new_field" name="new_field[]" value="{{$var_options}}" readonly>
                                                                <input type="hidden" name="new_field_id[]" value="{{$question->id}}"/>
                                                            </div>
                                                            <div class="form-group col-3">
                                                                <a class="btn btn-danger btn-sm mr-1 btn-remove-field" style="margin-top: 35px;" href="javascript:;">
                                                                    <i class="fa-solid fa-remove"></i> Remover
                                                                </a>
                                                                <a class="btn btn-secondary btn-sm mr-1 up" href="javascript:;" style="margin-top: 35px;" >
                                                                    <i class="fas fa-arrow-up"></i>
                                                                </a>
                                                                <a class="btn btn-secondary btn-sm mr-1 down" href="javascript:;" style="margin-top: 35px;" >
                                                                    <i class="fas fa-arrow-down"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="form-group">
                                                    <label for="name_new_field">Campo 1*</label>
                                                    <input type="text" class="form-control new_field" name="new_field[]" id="name_new_field" value="Nome; (Tipo: Texto (Até 200 caracteres)); Obrigatório" readonly>
                                                    <input type="hidden" name="new_field_id[]" value=""/>
                                                </div>
                                                {{-- <div class="form-group">
                                                    <label for="name_new_field">Campo 2*</label>
                                                    <input type="text" class="form-control new_field" name="new_field[]" id="cpf_new_field" value="CPF; (Tipo: CPF); Obrigatório; Único" readonly>
                                                </div> --}}
                                                <div class="form-group">
                                                    <label for="email_new_field">Campo 2*</label>
                                                    <input type="text" class="form-control new_field" name="new_field[]" id="email_new_field" value="E-mail; (Tipo: E-mail); Obrigatório; Único" readonly>
                                                    <input type="hidden" name="new_field_id[]" value=""/>
                                                </div>
                                            @endif
                                        </div>
                                    {{--<table class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th style="width: 10%">Ordem</th>
                                                <th>Pergunta</th>
                                                <th>Opções</th>
                                                <th>Obrigatório</th>
                                                <th>Único</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($questions as $question)
                                                <tr>
                                                    <td>{{ $question->id }}</td>
                                                    <td style="width: 10%"><input type="number" class="order_question"
                                                            id="{{ $question->id }}" value="{{ $question->order }}"
                                                            style="width: 30%" min="1"></td>
                                                    <td>{{ $question->question }}</td>
                                                    <td>@money($question->option)</td>
                                                    <td>@money($question->required)</td>
                                                    <td>@money($question->unique)</td>
                                                    <td>
                                                        @if ($question->status == 0)
                                                            Não ativo
                                                        @else
                                                            Ativo
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a class="btn btn-info btn-sm mr-1"
                                                                href="{{ route('question.edit', $question->id) }}">
                                                                <i class="fas fa-pencil-alt">
                                                                </i>
                                                                Editar
                                                            </a>
                                                            <form action="{{ route('question.destroy', $question->id) }}" method="POST">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <a class="btn btn-danger btn-sm mr-1" href="javascript:;" onclick="removeData({{ $question->id }})">
                                                                    <i class="fas fa-trash"></i> Remover
                                                                </a>
                                                                <button class="d-none" id="btn-remove-hidden-{{ $question->id }}">Remover</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <div class="modal fade modalMsgRemove"
                                                        id="modalMsgRemove-{{ $question->id }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="modalMsgRemoveLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalMsgRemoveLabel">
                                                                        Remoção de pergunta</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Deseja realmente remover esse pergunta?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        id="btn-remove-ok-{{ $question->id }}"
                                                                        onclick="removeSucc({{ $question->id }})">Sim</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Não</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>--}}
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('event.index')}}" class="btn btn-success float-left">Voltar</a>
                                    <button type="submit" class="btn btn-primary float-right">Salvar</button>
                                </div>
                                {{--<form method="POST" action="{{ route('question.save_questions', $id) }}">
                                    @csrf
                                    @foreach ($questions as $question)
                                        <input type="hidden" name="order_question[]" id="question_{{ $question->id }}"
                                            value="{{ $question->id }}_{{ $question->order }}">
                                    @endforeach
                                    <div class="card-footer">
                                        <a href="{{ route('event.index')}}" class="btn btn-success float-left">Voltar</a>
                                        <button type="submit" class="btn btn-primary float-right">Salvar</button>
                                    </div>
                                </form>--}}
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @push('footer')
        <script>
            function removeData(id) {
                $('#modalMsgRemove-' + id).modal('show');
            }

            function removeSucc(id) {
                $('#btn-remove-hidden-' + id).click();
            }
            $(document).ready(function() {
                $('.order_question').change(function() {
                    id = $(this).attr('id');
                    value = $(this).val();
                    console.log(value);
                    $('#question_' + id).val(id + '_' + value);
                });
            });

            $('#option').change(function(){

            var id_option_select = $(this).val();

            if(id_option_select == 2 || id_option_select == 3 || id_option_select == 4 || id_option_select == 14){
                $('#div_new_options').show();
                if(id_option_select == 14){
                    $('#new_options').val('AC, AL, AP, AM, BA, CE, DF, ES, GO, MA, MT, MS, MG, PA, PB, PR, PE, PI, RJ, RN, RS, RO, RR, SC, SP, SE, TO');
                }
            } else {
                $('#div_new_options').hide();
                if(id_option_select == 14){
                    $('#new_options').val('');
                }
            }

            if(id_option_select == 9 || id_option_select == 10){
                $('#div_new_number').show();
            } else {
                $('#div_new_number').hide();
            }
            });

            var i_field = parseInt($('input.new_field').length);
            $('#add_new_field').click(function(){
            var field = $(this).parent().parent().find('#question').val();
            var option = $(this).parent().parent().find('#option').val();
            var option_text = $(this).parent().parent().find('#option:selected').text();
            var required = $(this).parent().parent().find('#required').is(":checked");
            var unique = $(this).parent().parent().find('#unique').is(":checked");

            if(field === ''){

                alert('Por favor, preencha o nome do campo!');
                return false;
            }

            var required_star = required ? '*':'';
            var field_text = '';
            var field_name = '';
            var field_required = '';
            var field_unique = '';
            var field_options = '';

            if(required){
                field_required = '; Obrigatório';
            }

            if(unique){
                field_unique = '; Único';
            }

            i_field = i_field+1;

            $('#question').val('');
            $('#option').prop('selectedIndex',0);
            $('#required').prop('checked',false);
            $('#unique').prop('checked',false);

            switch(option){
                case '1':
                    field_text = '(Tipo: Texto (Até 200 caracteres))';
                    field_name = 'text';
                    break;
                case '2':
                    field_text = '(Tipo: Seleção)';
                    field_name = 'select';
                    field_options = '; [Opções: ' + $('#new_options').val() + ']';
                    break;
                case '3':
                    field_text = '(Tipo: Marcação)';
                    field_name = 'checkbox';
                    field_options = '; [Opções: ' + $('#new_options').val() + ']';
                    break;
                case '4':
                    field_text = '(Tipo: Múltipla escolha)';
                    field_name = 'multiselect';
                    field_options = '; [Opções: ' + $('#new_options').val() + ']';
                    break;
                case '5':
                    field_text = '(Tipo: CPF)';
                    field_name = 'cpf';
                    break;
                case '6':
                    field_text = '(Tipo: CNPJ)';
                    field_name = 'cnpj';
                    break;
                case '7':
                    field_text = '(Tipo: Data)';
                    field_name = 'date';
                    break;
                case '8':
                    field_text = '(Tipo: Telefone)';
                    field_name = 'phone';
                    break;
                case '9':
                    field_text = '(Tipo: Número inteiro)';
                    field_name = 'integer';
                    field_options = '; [Opções: ' + $('.val_min_option').val() + '|' + + $('.val_max_option').val() +']';
                    break;
                case '10':
                    field_text = '(Tipo: Número decimal)';
                    field_name = 'decimal';
                    field_options = '; [Opções: ' + $('.val_min_option').val() + '|' + + $('.val_max_option').val() +']';
                    break;
                case '11':
                    field_text = '(Tipo: Arquivo)';
                    field_name = 'file';
                    break;
                case '12':
                    field_text = '(Tipo: Textarea (+ de 200 caracteres))';
                    field_name = 'textearea';
                    break;
                case '13':
                    field_text = '(Tipo: E-mail)';
                    field_name = 'new_email';
                    break;
                case '14':
                    field_text = '(Tipo: Estados (BRA))';
                    field_name = 'states';
                    break;
            }

            $('#card-new-field').append('<div class="form-row">' +
                '<div class="form-group col-9">'+
                    '<label for="field_'+i_field+'">Campo ' + i_field + required_star + '</label>' +
                    '<input type="text" class="form-control new_field" name="new_field[]" value="'+field+'; ' + field_text + '' + field_options + '' + field_required + '' + field_unique +'" readonly>' +
                    '<input type="hidden" name="new_field_id[]" value="">' +
                '</div>'+
                '<div class="form-group col-3">'+
                    '<a class="btn btn-danger btn-sm mr-1 btn-remove-field" style="margin-top: 35px; margin-left: 17px;" href="javascript:;">'+
                        '<i class="fa-solid fa-remove"></i>'+
                        ' Remover'+
                    '</a>'+
                    '<a class="btn btn-secondary btn-sm mr-1 up" href="javascript:;" style="margin-top: 35px;" >'+
                        '<i class="fas fa-arrow-up"></i>'+
                    '</a>'+
                    '<a class="btn btn-secondary btn-sm mr-1 down" href="javascript:;" style="margin-top: 35px;" >'+
                        '<i class="fas fa-arrow-down"></i>'+
                    '</a>'+
                '</div>'+
            '</div>');

            $("#card-new-field .up:first").hide();
            $("#card-new-field .down:last").hide();
            $("#card-new-field .down:not(:last)").show();

            $('#new_options').val('');
            $('.val_min_option').val('');
            $('.val_max_option').val('');
            });

            $("#card-new-field .up:first").hide();
            $("#card-new-field .down:last").hide();

            $(".up,.down").click(function () {

            var $element = this;
            var row = $($element).parents("div:first").parents("div:first");

            if($(this).is('.up')){
                row.insertBefore(row.prev());
            }
            else{
                row.insertAfter(row.next());
            }

            $("#card-new-field .up:first").hide();
            $("#card-new-field .down:last").hide();
            $("#card-new-field .up:not(:first)").show();
            $("#card-new-field .down:not(:last)").show();
            });
        </script>
    @endpush
</x-app-layout>
