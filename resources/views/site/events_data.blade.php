<div class="row">
    @foreach($events as $event) 
    <div class="col-md-4">
        <div class="single-blog-item">
            <div class="blog-thumnail">
                <a href="/{{$event->event_slug}}" target="_blank">
                    <img src="{{ URL::asset('storage/'.$event->event_banner) }}" alt="{{ $event->event_name}}" class="img-fluid">
                </a>
            </div>
            <div class="blog-content">
                <h6><a href="/{{$event->event_slug}}" target="_blank">{{ $event->category_description}}</a></h6> 
                <h4><a href="/{{$event->event_slug}}" target="_blank">{{ $event->event_name}}</a></h4><br/>
                <h4 id="place_name"><a href="/{{$event->event_slug}}" target="_blank">{{ $event->place_name}}, {{ $event->city_name}}-{{ $event->state_uf}}</a></h4>
            </div>
            <span class="blog-date">{{\Carbon\Carbon::parse($event->min_date)->format('d/m/y')}}, {{\Carbon\Carbon::parse($event->min_time)->format('H:i')}}h</span>
        </div>
    </div>
    @endforeach
</div>