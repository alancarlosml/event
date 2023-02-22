<x-site-layout>
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
          <div class="container">
    
            <h2 class="mt-4"> Todos os eventos</h2>
    
          </div>
        </section><!-- End Breadcrumbs -->
    
        <section class="inner-page search" id="event-list">
            <div class="container">
                <header class="section-header">
                    <p>Busque mais eventos</p>
                </header>
                <div class="row">
                    {{-- <h6 class="text-left display-5">Busque mais eventos</h6> --}}
                    <div class="info-box bg-light">
                        <div class="container-fluid info-box-content">
                            <form action="enhanced-results.html">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Categoria</b></label>
                                                <select name="category" id="category" class="custom-select sources" placeholder="Categoria">
                                                    <option value="0">Selecione uma opção</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{$category->id}}">{{$category->description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Área</b></label>
                                                <select name="area_id" id="area_id" class="custom-select sources" placeholder="Área">
                                                    <option value="0">Selecione uma categoria</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Local</b></label>
                                                <select name="state" id="state" class="custom-select sources" placeholder="Estado">
                                                    <option value="0">Selecione uma opção</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{$state->uf}}">{{$state->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-sm-12 text-left">
                                                <label><b>Período</b></label>
                                                <select name="period" id="period" class="custom-select sources" placeholder="Período">
                                                    <option value="0">Selecione uma opção</option>
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
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="search" class="form-control" id="event_name_search" placeholder="Nome do evento"/>
                                                    </div>
                                                </div> 
                                            </div>
                                            {{-- <div class="col-2 text-right">
                                                <input class="btn btn-primary" id="event_button_search" type="submit" value="Buscar" style="width: 100%;">
                                            </div>  --}}
                                        </div>  
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="event_data">
                    @include('site.events_data')
                </div>
                        <div class="container d-flex justify-content-center">
                            {{ $events->links("pagination::bootstrap-4") }}
                        </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
      @endpush

      @push('head')
        {{-- <style>
            .bootstrap-select.open > .dropdown-menu{
            /*display: block;*/
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            -webkit-transform: scale(1) translateY(0);
            -ms-transform: scale(1) translateY(0);
            transform: scale(1) translateY(0);
        }
        .bootstrap-select > .dropdown-menu{
            /*display: none;*/
            opacity: 0;
            max-height: 330px !important;
            visibility: hidden;
            overflow: hidden;
            -webkit-transform-origin: 50% 0;
            -ms-transform-origin: 50% 0;
            transform-origin: 50% 0;
            -webkit-transform: scale(0.85) translateY(-5px);
            -ms-transform: scale(0.85) translateY(-5px);
            transform: scale(0.85) translateY(-5px);
            -webkit-transition: all 0.2s cubic-bezier(0.5, 0, 0, 1.25), opacity 0.15s ease-out;
            transition: all 0.2s cubic-bezier(0.5, 0, 0, 1.25), opacity 0.15s ease-out;
        }

        .box{
            width: 400px;
            height: 120px;
            position: absolute;
            margin: auto;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
        }
       
        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn){
            width: 100%;
        }
        .bootstrap-select>.dropdown-toggle{
            background: #EDF2F5;
            border: 0;
            width: 100%;
            display: block;
            padding: 16px 15px;
            color: #000000;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.030em;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Roboto', sans-serif;
        }
        .bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover{
            color: #000;
        }
        .dropdown-toggle::after {
            content: "";
            position: absolute;
            border-top: .5em solid;
            border-right: .5em solid transparent;
            border-bottom: 0;
            border-left: .5em solid transparent;
            right: 10px;
            top: 48%;
        }
        .dropdown-menu{
            box-shadow: 0px 0px 15px rgba(60, 76, 150, 0.1);
            -webkit-box-shadow: 0px 0px 15px rgba(60, 76, 150, 0.1);
            -moz-box-shadow: 0px 0px 15px rgba(60, 76, 150, 0.1);
            position: absolute;
            left: 0;
            background: #fff;
            min-width: 100% !important;
            width: 100% !important;
        }
        .bootstrap-select .dropdown-menu a{
            display: block;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.030em;
        }
        </style>   --}}
      @endpush

      @push('footer')

        <script>

            // function ($) {
            // 'use strict';

            // // DROPDOWN CLASS DEFINITION
            // // =========================

            // var backdrop = '.dropdown-backdrop'
            // var toggle   = '[data-toggle="dropdown"]'
            // var Dropdown = function (element) {
            //     $(element).on('click.bs.dropdown', this.toggle)
            // }

            // Dropdown.VERSION = '3.4.1'

            // function getParent($this) {
            //     var selector = $this.attr('data-target')

            //     if (!selector) {
            //     selector = $this.attr('href')
            //     selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
            //     }

            //     var $parent = selector !== '#' ? $(document).find(selector) : null

            //     return $parent && $parent.length ? $parent : $this.parent()
            // }

            // function clearMenus(e) {
            //     if (e && e.which === 3) return
            //     $(backdrop).remove()
            //     $(toggle).each(function () {
            //     var $this         = $(this)
            //     var $parent       = getParent($this)
            //     var relatedTarget = { relatedTarget: this }

            //     if (!$parent.hasClass('open')) return

            //     if (e && e.type == 'click' && /input|textarea/i.test(e.target.tagName) && $.contains($parent[0], e.target)) return

            //     $parent.trigger(e = $.Event('hide.bs.dropdown', relatedTarget))

            //     if (e.isDefaultPrevented()) return

            //     $this.attr('aria-expanded', 'false')
            //     $parent.removeClass('open').trigger($.Event('hidden.bs.dropdown', relatedTarget))
            //     })
            // }

            // Dropdown.prototype.toggle = function (e) {
            //     var $this = $(this)

            //     if ($this.is('.disabled, :disabled')) return

            //     var $parent  = getParent($this)
            //     var isActive = $parent.hasClass('open')

            //     clearMenus()

            //     if (!isActive) {
            //     if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
            //         // if mobile we use a backdrop because click events don't delegate
            //         $(document.createElement('div'))
            //         .addClass('dropdown-backdrop')
            //         .insertAfter($(this))
            //         .on('click', clearMenus)
            //     }

            //     var relatedTarget = { relatedTarget: this }
            //     $parent.trigger(e = $.Event('show.bs.dropdown', relatedTarget))

            //     if (e.isDefaultPrevented()) return

            //     $this
            //         .trigger('focus')
            //         .attr('aria-expanded', 'true')

            //     $parent
            //         .toggleClass('open')
            //         .trigger($.Event('shown.bs.dropdown', relatedTarget))
            //     }

            //     return false
            // }

            // Dropdown.prototype.keydown = function (e) {
            //     if (!/(38|40|27|32)/.test(e.which) || /input|textarea/i.test(e.target.tagName)) return

            //     var $this = $(this)

            //     e.preventDefault()
            //     e.stopPropagation()

            //     if ($this.is('.disabled, :disabled')) return

            //     var $parent  = getParent($this)
            //     var isActive = $parent.hasClass('open')

            //     if (!isActive && e.which != 27 || isActive && e.which == 27) {
            //     if (e.which == 27) $parent.find(toggle).trigger('focus')
            //     return $this.trigger('click')
            //     }

            //     var desc = ' li:not(.disabled):visible a'
            //     var $items = $parent.find('.dropdown-menu' + desc)

            //     if (!$items.length) return

            //     var index = $items.index(e.target)

            //     if (e.which == 38 && index > 0)                 index--         // up
            //     if (e.which == 40 && index < $items.length - 1) index++         // down
            //     if (!~index)                                    index = 0

            //     $items.eq(index).trigger('focus')
            // }


            // // DROPDOWN PLUGIN DEFINITION
            // // ==========================

            // function Plugin(option) {
            //     return this.each(function () {
            //     var $this = $(this)
            //     var data  = $this.data('bs.dropdown')

            //     if (!data) $this.data('bs.dropdown', (data = new Dropdown(this)))
            //     if (typeof option == 'string') data[option].call($this)
            //     })
            // }

            // var old = $.fn.dropdown

            // $.fn.dropdown             = Plugin
            // $.fn.dropdown.Constructor = Dropdown


            // // DROPDOWN NO CONFLICT
            // // ====================

            // $.fn.dropdown.noConflict = function () {
            //     $.fn.dropdown = old
            //     return this
            // }


            // // APPLY TO STANDARD DROPDOWN ELEMENTS
            // // ===================================

            // $(document)
            //     .on('click.bs.dropdown.data-api', clearMenus)
            //     .on('click.bs.dropdown.data-api', '.dropdown form', function (e) { e.stopPropagation() })
            //     .on('click.bs.dropdown.data-api', toggle, Dropdown.prototype.toggle)
            //     .on('keydown.bs.dropdown.data-api', toggle, Dropdown.prototype.keydown)
            //     .on('keydown.bs.dropdown.data-api', '.dropdown-menu', Dropdown.prototype.keydown)

            // }(jQuery);


        function getMoreEvents(page) {

            var event_val = $('#event_name_search').val();
            var category_val = $("#category option:selected").val();
            var area_val = $("#area_id option:selected").val();
            var state_val = $("#state option:selected").val();
            var period_val = $("#period option:selected").val();

            $.ajax({
                type: "GET",
                data: {
                    'event_val':event_val,
                    'category_val': category_val,
                    'area_val': area_val,
                    'state_val': state_val,
                    'period_val': period_val
                },
                url: "{{ route('event_home.get-more-events') }}" + "?page=" + page,
                    beforeSend: function() {
                        $('#event_data').html('<div class="d-flex justify-content-center" style="padding: 100px 0"><div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div></div>');
                    },
                    success:function(data) {
                        $('#event_data').html(data.events_list);
                    }
            });
        }

        $(document).ready(function() {

            // $(document).on('click', '.pagination a', function(event) {
            //     event.preventDefault();
            //     var page = $(this).attr('href').split('page=')[1];
            //     console.log(page);
            //     getMoreEvents(page);
            // });
            getMoreEvents(1);
            $('#event_name_search').on('keyup', function() {
                $value = $(this).val();
                getMoreEvents(1);
            });
            $('#category').on('change', function(e) {
                getMoreEvents(1);
            });
            $('#area_id').on('change', function (e) {
                getMoreEvents();
            });
            $('#state').on('change', function (e) {
                getMoreEvents();
            });
            $('#period').on('change', function (e) {
                getMoreEvents();
            });

            // $('.selectpicker').selectpicker();

            // $(".custom-select").each(function() {
            //     var classes = $(this).attr("class"),
            //         id      = $(this).attr("id"),
            //         name    = $(this).attr("name");

            //     var template =  '<div class="' + classes + '">';
            //         template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
            //         template += '<div class="custom-options">';
            //         $(this).find("option").each(function() {
            //             template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
            //         });
            //     template += '</div></div>';

            //     $(this).wrap('<div class="custom-select-wrapper"></div>');
            //     $(this).hide();
            //     $(this).after(template);
            // });
            // $(".custom-option:first-of-type").hover(function() {
            //     $(this).parents(".custom-options").addClass("option-hover");
            // }, function() {
            //     $(this).parents(".custom-options").removeClass("option-hover");
            // });
            // $(".custom-select-trigger").on("click", function() {
            //     $('html').one('click',function() {
            //         $(".custom-select").removeClass("opened");
            //     });
            //     $(this).parents(".custom-select").toggleClass("opened");
            //     event.stopPropagation();
            // });
            // $(".custom-option").on("click", function() {
            //     $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
            //     $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
            //     $(this).addClass("selection");
            //     $(this).parents(".custom-select").removeClass("opened");
            //     $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
            // });

            $('#category').next().find('.custom-options').click(function () {
                category_id = $(this).children('.selection').attr('data-value');
                $("#category").find('option').attr("selected",false) ;
                $('#category option[value="'+category_id+'"]').attr('selected','selected');
                $("#area_id").html('');
                $.ajax({
                    url:"{{route('event_home.get_areas_by_category')}}",
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
                    url:"{{route('event_home.get_areas_by_category')}}",
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