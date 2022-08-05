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
    
        <section class="inner-page" id="event-list">
            <div class="container">
                <div class="row">
                    @foreach($events as $event) 
                    
                    <div class="col-md-4">
                        <div class="single-blog-item">
                            <div class="blog-thumnail">
                                <a href="/eventos/{{$event->slug}}" target="_blank">
                                    <img src="{{ URL::asset('storage/'.$event->banner) }}" alt="{{ $event->name}}" class="img-fluid">
                                </a>
                            </div>
                            <div class="blog-content">
                                <h6><a href="/eventos/{{$event->slug}}" target="_blank">{{ $event->category->description}}</a></h6>
                                <h4><a href="/eventos/{{$event->slug}}" target="_blank">{{ $event->name}}</a></h4><br/>
                                <h4 id="place_name"><a href="/eventos/{{$event->slug}}" target="_blank">{{ $event->place->name}}, {{ $event->place->get_city()->name}}-{{ $event->place->get_city()->uf}}</a></h4>
                            </div>
                            <span class="blog-date">{{\Carbon\Carbon::parse($event->min_date)->format('d/m/y')}}, {{\Carbon\Carbon::parse($event->min_time)->format('h:i')}}h</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    
      </main><!-- End #main -->

      @push('head')

      <style>
            #event-list img {
                width: 100%;
                height: 250px;
                object-fit: cover;
            }

            #event-list .single-blog-item {
                border: 1px solid #dfdede;
                box-shadow: 2px 5px 10px #dfdede;
                margin: 15px auto;
                padding: 5px;
                position: relative;
                border-radius: 10px;
            }

            #event-list .blog-content {
                padding: 15px;
            }

            #event-list .blog-content h4 {
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 10px;
                text-transform: uppercase;
            }

            #event-list .blog-content h6 a{
                color:#013289;
                font-weight: 600;
                font-size: 16px;
            }

            #event-list .blog-content h4 a{
                color:#4154f1;
                font-size: 20px;
            }
            
            #event-list .blog-content h4#place_name a{
                color:#333;
                font-size: 16px;
            }
            
            #event-list .blog-date {
                position: absolute;
                background: #4154f1;
                top: 35px;
                /* left: 5px; */
                color: #fff;
                border-radius: 0 25px 25px 0;
                padding: 5px 15px;
                font-weight: 500;
            }

      </style>
      @endpush

</x-site-layout>