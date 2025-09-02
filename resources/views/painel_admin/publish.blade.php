<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">Meus eventos</a></li>
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
                    <ul id="progressbar">
                        <li class="active" id="account"><strong>Informações</strong></li>
                        <li class="active" id="personal"><strong>Inscrições</strong></li>
                        <li class="active" id="payment"><strong>Cupons</strong></li>
                        <li class="active" id="confirm"><strong>Publicar</strong></li>
                    </ul>
                    <form method="POST" action="{{route('event_home.publish', $hash_event)}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="hash_event" value="{{$hash_event}}">
                        <div class="card-body">
                            <h4>Aparência do site do evento</h4>
                            <label for="thems">Tema*</label><br/> 
                            <div class="btn-group btn-group-toggle" data-bs-toggle="buttons">
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
                                    @if($event->banner == "")
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
                            <div class="form-group mb-3">
                                <label for="owner_name">Organizador do evento*</label>
                                <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Organizador" value="{{ $event->owner->name ?? '' }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">Descrição</label>
                                <textarea type="password" class="form-control" id="description" name="description" rows="6">{{ $event->owner->description ?? '' }}</textarea>
                            </div>
                            <div id="banner_organizador mb-3">
                                <label for="banner">Banner do organizador*</label>
                                @if($event->owner === null)
                                    <!-- If no owner exists, always show the file input for uploading an icon -->
                                    <input class="form-control" type="file" id="icon" name="icon" required>
                                @else
                                    <!-- If owner exists, check if icon is set -->
                                    @if($event->owner->icon && $event->owner->icon !== '')
                                        <div class="form-group">
                                            <img src="{{ asset('storage/' . $event->owner->icon) }}" alt="Banner evento" class="img-fluid img-thumbnail" style="width: 200px">
                                            <a href="{{ route('event_home.delete_file_icon', $event->owner->id) }}" class="btn btn-danger ml-1">Excluir</a>
                                        </div>
                                    @else
                                        <!-- If owner exists but has no icon, show the file input -->
                                        <input class="form-control" type="file" id="icon" name="icon" required>
                                    @endif
                                @endif
                            </div>
                            <hr>
                            <h4>Publicar o evento</h4>
                            <div class="form-group py-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" @if($event->status == 1) checked="checked" @endif class="form-check-input" name="status" id="status" value="1">
                                    <label class="form-check-label" for="status">Sim</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <a href="{{ route('event_home.create.step.three') }}" class="btn btn-secondary">Anterior</a>
                            <button type="submit" class="btn btn-primary btn-lg float-end" style="margin-top: -5px"><i class="fa-solid fa-circle-check"></i> Salvar evento</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('footer')

        <script>

            $(document).ready(function() {

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
        </script>
      
    @endpush

</x-site-layout>