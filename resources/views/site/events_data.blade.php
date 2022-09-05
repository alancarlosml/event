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
    <div class="container d-flex justify-content-center">
        {{ $events->links("pagination::bootstrap-4") }}
    </div>
</div>