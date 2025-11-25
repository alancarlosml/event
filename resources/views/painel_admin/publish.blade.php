<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
                <li><a href="/">Home</a></li>
                <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                <li>Publicar</li>
            </ol>
            <h2>Gerenciar evento</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="mb-3 px-3">
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
                
                <div class="wizard-container">
                    <div class="wizard-progress">
                        <div class="wizard-progress-bar" style="width: 100%"></div>
                    </div>
                    
                    <div class="wizard-steps">
                        <div class="step completed">
                            <div class="step-number">1</div>
                            <div class="step-label">Informações</div>
                        </div>
                        <div class="step completed">
                            <div class="step-number">2</div>
                            <div class="step-label">Inscrições</div>
                        </div>
                        <div class="step completed">
                            <div class="step-number">3</div>
                            <div class="step-label">Cupons</div>
                        </div>
                        <div class="step active">
                            <div class="step-number">4</div>
                            <div class="step-label">Publicar</div>
                        </div>
                    </div>
                    
                    <div class="wizard-content">
                        <form method="POST" action="{{route('event_home.publish', $hash_event)}}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="hash_event" value="{{$hash_event}}">
                            
                            <div class="mb-5">
                                <h4 class="mb-3 border-bottom pb-2">Aparência do site do evento</h4>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tema <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <label class="theme-selector">
                                            <input type="radio" name="color_option" value="green" @if($event->theme == 'green') checked @endif class="d-none">
                                            <div class="theme-circle bg-success" title="Verde"></div>
                                        </label>
                                        <label class="theme-selector">
                                            <input type="radio" name="color_option" value="blue" @if($event->theme == 'blue') checked @endif class="d-none">
                                            <div class="theme-circle bg-primary" title="Azul"></div>
                                        </label>
                                        <label class="theme-selector">
                                            <input type="radio" name="color_option" value="purple" @if($event->theme == 'purple') checked @endif class="d-none">
                                            <div class="theme-circle" style="background-color: #6f42c1;" title="Roxo"></div>
                                        </label>
                                        <label class="theme-selector">
                                            <input type="radio" name="color_option" value="red" @if($event->theme == 'red') checked @endif class="d-none">
                                            <div class="theme-circle bg-danger" title="Vermelho"></div>
                                        </label>
                                        <label class="theme-selector">
                                            <input type="radio" name="color_option" value="orange" @if($event->theme == 'orange') checked @endif class="d-none">
                                            <div class="theme-circle" style="background-color: #fd7e14;" title="Laranja"></div>
                                        </label>
                                    </div>
                                    <input type="hidden" name="theme" id="theme" value="{{ $event->theme ?? '' }}">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Banner do evento <span class="text-danger">*</span></label>
                                    <div class="mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="banner_option" id="inlineRadio1" value="1" @if($event->banner_option == 1) checked @endif>
                                            <label class="form-check-label" for="inlineRadio1">Sem banner (apenas cor)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="banner_option" id="inlineRadio2" value="2" @if($event->banner_option == 2) checked @endif>
                                            <label class="form-check-label" for="inlineRadio2">Banner personalizado</label>
                                        </div>
                                    </div>
                                    
                                    <div id="banner_container" class="mt-3 p-3 border rounded bg-light" style="{{ $event->banner_option == 1 ? 'display: none;' : '' }}">
                                        @if($event->banner == "")
                                            <input class="form-control" type="file" id="banner" name="banner">
                                            <div class="form-text">Recomendado: 1200x400px, max 2MB.</div>
                                        @else
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ asset('storage/'.$event->banner) }}" alt="Banner evento" class="img-thumbnail" style="max-height: 150px; max-width: 100%;">
                                                <a href="{{route('event_home.delete_file_event', $event->id)}}" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash me-1"></i> Excluir
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-5">
                                <h4 class="mb-3 border-bottom pb-2">Organização do evento</h4>
                                <input type="hidden" name="owner_id" value="{{$owner_id}}">
                                
                                <div class="mb-3">
                                    <label for="owner_name" class="form-label fw-bold">Nome do Organizador <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Nome da empresa ou pessoa" value="{{ $event->owner->name ?? '' }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Descrição do Organizador</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Breve descrição sobre quem está organizando o evento">{{ $event->owner->description ?? '' }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="icon" class="form-label fw-bold">Logo/Foto do Organizador <span class="text-danger">*</span></label>
                                    <div class="p-3 border rounded bg-light">
                                        @if($event->owner === null || !$event->owner->icon)
                                            <input class="form-control" type="file" id="icon" name="icon" required>
                                            <div class="form-text">Recomendado: 200x200px (quadrado), max 1MB.</div>
                                        @else
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ asset('storage/' . $event->owner->icon) }}" alt="Logo organizador" class="img-thumbnail rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                                <a href="{{ route('event_home.delete_file_icon', $event->owner->id) }}" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash me-1"></i> Excluir
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="mb-3 border-bottom pb-2">Publicação</h4>
                                <div class="form-check form-switch p-3 border rounded bg-light">
                                    <input class="form-check-input" type="checkbox" @if($event->status == 1) checked @endif name="status" id="status" value="1" style="width: 3em; height: 1.5em; margin-right: 1em;">
                                    <label class="form-check-label fw-bold pt-1" for="status">Publicar evento agora?</label>
                                    <div class="form-text ms-1">Se marcado, o evento ficará visível para o público imediatamente.</div>
                                </div>
                            </div>
                            
                            <div class="wizard-actions">
                                <a href="{{ route('event_home.create.step.three') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Anterior
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Salvar e Finalizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
        <style>
            .theme-selector {
                cursor: pointer;
                position: relative;
            }
            .theme-circle {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: 2px solid #ddd;
                transition: all 0.2s;
            }
            .theme-selector input:checked + .theme-circle {
                border: 3px solid #333;
                transform: scale(1.1);
                box-shadow: 0 0 10px rgba(0,0,0,0.2);
            }
            .text-purple { color: #6f42c1; }
            .text-orange { color: #fd7e14; }
        </style>
      @endpush

      @push('footer')
        <script>
            $(document).ready(function() {
                $("input[type='radio'][name='banner_option']").change(function(){
                    val_banner_option = $(this).val();
                    if(val_banner_option == 1) {
                        $('#banner_container').slideUp();
                    } else {
                        $('#banner_container').slideDown();
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