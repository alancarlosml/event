<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Criar um novo evento</h2>
    
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
                        <li id="confirm"><strong>Publicar</strong></li>
                    </ul>
                    {{-- {{dd($hash_event)}} --}}
                    <div class="card-body">
                        <h4>Listagem dos cupons</h4>
                        <div class="form-group text-right">
                            <a href="{{route('event_home.create_coupon', $hash_event)}}" class="btn btn-success">Cadastrar novo cupom</a>
                        </div>
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Tipo desconto</th>
                                <th>Valor desconto</th>
                                <th>Limite de compras</th>
                                <th>Limite de inscrições</th>
                                <th>Status</th>
                                <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coupons as $coupon)
                                    <tr>
                                        <td>{{$coupon->id}}</td>
                                        <td>{{$coupon->code}}</td>
                                        <td>@if($coupon->discount_type == 0) Porcentagem @elseif($coupon->discount_type == 1) Fixo @endif</td>
                                        <td>@if($coupon->discount_type == 0) {{$coupon->discount_value*100}}% @elseif($coupon->discount_type == 1) @money($coupon->discount_value) @endif</td>
                                        <td>{{$coupon->limit_buy}}</td>
                                        <td>{{$coupon->limit_tickets}}</td>
                                        <td>@if($coupon->status == 1) <span class="badge badge-success">Ativo</span> @else <span class="badge badge-danger">Não ativo</span> @endif</td>
                                        <td>
                                            <div class="d-flex">
                                                <a class="btn btn-info btn-sm mr-1" href="{{route('event_home.coupon_edit', $coupon->hash)}}">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>
                                                    Editar
                                                </a>
                                                <form action="{{ route('event.destroy_coupon', $coupon->hash) }}" method="POST">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <a class="btn btn-danger btn-sm mr-1"  href="javascript:;" onclick="removeData('{{$coupon->hash}}')">
                                                        <i class="fas fa-trash">
                                                        </i>
                                                        Remover
                                                    </a>
                                                    <button class="d-none" id="btn-remove-hidden-{{$coupon->hash}}">Remover</button>
                                                </form>
                                            </div>
                                        </td>
                                        <div class="modal fade modalMsgRemove" id="modalMsgRemove-{{$coupon->hash}}" tabindex="-1" role="dialog" aria-labelledby="modalMsgRemoveLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalMsgRemoveLabel">Remoção de cupom</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Deseja realmente remover esse cupom?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" id="btn-remove-ok-{{$coupon->hash}}" onclick="removeSucc('{{$coupon->hash}}')">Sim</button>
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
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('event_home.create.step.two') }}" class="btn btn-primary">Anterior</a>
                        <a href="{{ route('event_home.create.step.four') }}" class="btn btn-primary">Próximo</a>
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

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

        function removeData(hash){
            $('#modalMsgRemove-' + hash).modal('show');
        }

        function removeSucc(hash){
            $('#btn-remove-hidden-' + hash).click();
        }

        $(document).ready(function() {

        });
    
    </script>
      
    @endpush

</x-site-layout>