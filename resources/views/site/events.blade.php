<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <ol>
              <li><a href="index.html">Home</a></li>
              <li>Eventos</li>
            </ol>
            <h2>Todos os eventos</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page search" id="event-list">
            <div class="container">
                <div class="row">
                    <div class="info-box bg-light">
                        <div class="container-fluid info-box-content">
                            <h6 class="text-left display-5">Busque mais eventos</h6>
                            <form action="enhanced-results.html">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="form-group col-lg-3 col-sm-12">
                                                <select name="category" id="category" class="custom-select sources" placeholder="Categoria">
                                                    @foreach ($categories as $category)
                                                        <option value="{{$category->id}}">{{$category->description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12">
                                                <select name="area_id" id="area_id" class="custom-select sources" placeholder="Área">
                                                    <option>Selecione uma categoria</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12">
                                                <select name="state" id="state" class="custom-select sources" placeholder="Estado">
                                                    @foreach ($states as $state)
                                                        <option value="{{$state->uf}}">{{$state->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12">
                                                <select name="period" id="period" class="custom-select sources" placeholder="Período">
                                                    <option>Selecione</option>
                                                    <option value="any"> Qualquer </option>
                                                    <option value="today"> Hoje </option>
                                                    <option value="tomorrow"> Amanhã </option>
                                                    <option value="week"> Esta semana </option>
                                                    <option value="month"> Este mês </option>
                                                    @php
                                                        $begin = date("m");
                                                        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                                                        date_default_timezone_set('America/Sao_Paulo');
                                                        for($i=intval($begin)+1; $i<=12; $i++){ 
                                                            $month = ucfirst(strftime('%B', mktime(0, 0, 0, $i, 10))); 
                                                            $month_eng = strtolower(date('F', mktime(0, 0, 0, $i, 10))); 
                                                            echo "<option value='$month_eng'>$month</option>";
                                                        }
                                                    @endphp         
                                                    <option value="year"> Este ano </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="search" class="form-control" id="event_name_search" placeholder="Nome do evento"/>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="col-2 text-right">
                                                <input class="btn btn-primary" id="event_button_search" type="submit" value="Buscar" style="width: 100%;">
                                            </div> 
                                        </div>  
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($events as $event) 
                    
                    <div class="col-md-4">
                        <div class="single-blog-item">
                            <div class="blog-thumnail">
                                <a href="/{{$event->slug}}" target="_blank">
                                    <img src="{{ URL::asset('storage/'.$event->banner) }}" alt="{{ $event->name}}" class="img-fluid">
                                </a>
                            </div>
                            <div class="blog-content">
                                <h6><a href="/{{$event->slug}}" target="_blank">{{ $event->area->category->description}}</a></h6>
                                <h4><a href="/{{$event->slug}}" target="_blank">{{ $event->name}}</a></h4><br/>
                                <h4 id="place_name"><a href="/{{$event->slug}}" target="_blank">{{ $event->place->name}}, {{ $event->place->get_city()->name}}-{{ $event->place->get_city()->uf}}</a></h4>
                            </div>
                            <span class="blog-date">{{\Carbon\Carbon::parse($event->min_date)->format('d/m/y')}}, {{\Carbon\Carbon::parse($event->min_time)->format('H:i')}}h</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('footer')

        <script>
        $(document).ready(function() {

            $(".custom-select").each(function() {
                var classes = $(this).attr("class"),
                    id      = $(this).attr("id"),
                    name    = $(this).attr("name");

                var template =  '<div class="' + classes + '">';
                    template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
                    template += '<div class="custom-options">';
                    $(this).find("option").each(function() {
                    template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
                    });
                template += '</div></div>';
                
                $(this).wrap('<div class="custom-select-wrapper"></div>');
                $(this).hide();
                $(this).after(template);
            });
            $(".custom-option:first-of-type").hover(function() {
                $(this).parents(".custom-options").addClass("option-hover");
            }, function() {
                $(this).parents(".custom-options").removeClass("option-hover");
            });
            $(".custom-select-trigger").on("click", function() {
                $('html').one('click',function() {
                $(".custom-select").removeClass("opened");
                });
                $(this).parents(".custom-select").toggleClass("opened");
                event.stopPropagation();
            });
            $(".custom-option").on("click", function() {
                $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
                $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
                $(this).addClass("selection");
                $(this).parents(".custom-select").removeClass("opened");
                $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
            });

            $('#category').next().find('.custom-options').click(function () {
                category_id = $(this).children('.selection').attr('data-value');
                $("#category").find('option').attr("selected",false) ;
                $('#category option[value="'+category_id+'"]').attr('selected','selected');
                $("#area_id").html('');
                $.ajax({
                    url:"{{url('/get-areas')}}",
                    type: "POST",
                    data: {
                        category_id: category_id,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        // $('#area_id').html('<option value="">Selecione</option>'); 
                        $.each(result.areas,function(key,value){
                            $("#area_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });

                        var template =  '';
                        $('#area_id').find("option").each(function() {
                            template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
                        });

                        $('#area_id').next().children('.custom-options').html(template);
                    }
                });
            }); 

            $('#category').on('change', function() {
                var category_id = this.value;
                $("#area_id").html('');
                $.ajax({
                    url:"{{url('admin/categories/get-areas-by-category')}}",
                    type: "POST",
                    data: {
                        category_id: category_id,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        $('#area_id').html('<option value="">Selecione</option>'); 
                        $.each(result.areas,function(key,value){
                            $("#area_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
            });

            $('#area_id').next().find('.custom-options').click(function () {
                area_id = $(this).children('.selection').attr('data-value');
                $("#area_id").find('option').attr("selected",false) ;
                $('#area_id option[value="'+area_id+'"]').attr('selected','selected');
                $('#area_id').next().find('.custom-options').parents(".custom-select-wrapper").find("select").val($(this).data("value"));
                $('#area_id').next().find('.custom-options').parents(".custom-select-wrapper").find("select").find(".custom-option").removeClass("selection");
            });

            var el_custom_option = $('#area_id').next().children('.custom-select-trigger').next();
            $(el_custom_option).on('click', 'span', function () {
                $(el_custom_option).children('span').removeClass("selection");
                $(this).addClass("selection");
                $(this).parents(".custom-select").removeClass("opened");
                $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
            });
        });
    
    </script>
      
    @endpush

</x-site-layout>