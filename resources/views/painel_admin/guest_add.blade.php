<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
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
                        <h4>Adicionar novo usuário convidado</h4>
                        <form method="POST" action="{{route('event_home.guest_store', $event->hash)}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="email">Email do convidado*</label>
                                    <input type="email" class="form-control col-5" id="email" name="email" placeholder="Email" value="{{old('email')}}" required>
                                    <small id="emailHelp" class="form-text text-muted">Antes de realizar a adição, certifique-se de que o usuário já possui cadastro no site.</small>
                                </div>
                                <div class="form-group">
                                    <label for="email">Papel*</label>
                                    <select class="form-control col-5" id="role" name="role">
                                        <option value="">Selecione</option>
                                        <option value="admin">Admin</option>
                                        <option value="convidado">Convidado</option>
                                        <option value="monitor">Monitor</option>
                                        <option value="vendedor">Vendedor</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <div class="custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="status"
                                               id="status" value="1">
                                        <label class="custom-control-label" for="status">Ativo</label>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="{{ route('event_home.guests', $event->hash) }}" class="btn btn-primary">Anterior</a>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
      @endpush

      @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      @endpush

      @push('footer')
      
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
        $(document).ready(function() {

            $('[data-toggle="tooltip"]').tooltip({
                placement : 'right'
            });

            $('#type').change(function(){
                id_type = $(this).val();
                if(id_type == 1){
                    $('#value_div').hide();
                }else{
                    $('#value_div').show();
                }
            });

            // $('#reservationtime_input').daterangepicker({
            //     timePicker: true,
            //     timePickerIncrement: 30,
            //     locale: {
            //         format: 'MM/DD/YYYY hh:mm A'
            //     }
            // });
    
            $('#reservationtime_begin').datetimepicker({ 
                icons: { time: 'far fa-clock' },
                format: 'DD/MM/YYYY hh:mm A' 
            });

            $('#reservationtime_end').datetimepicker({ 
                icons: { time: 'far fa-clock' },
                format: 'DD/MM/YYYY hh:mm A' 
            });

            $("#reservationtime_begin").on("change.datetimepicker", function (e) {
                $('#reservationtime_end').datetimepicker('minDate', e.date);
            });
            $("#reservationtime_end").on("change.datetimepicker", function (e) {
                $('#reservationtime_begin').datetimepicker('maxDate', e.date);
            });
        });
    
    </script>
      
    @endpush

</x-site-layout>