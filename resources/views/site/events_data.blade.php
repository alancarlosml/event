<div class="row gy-4"> <!-- gy-4 para espaçamento responsivo -->
    @if (count($events) > 0)
        @foreach ($events as $event)
            <div class="col-md-4">
                <div class="single-blog-item card border-0"> <!-- Classes para moderno card -->
                    <div class="blog-thumnail">
                        <a href="/{{ $event->event_slug }}" target="_blank">
                            <img src="{{ URL::asset('storage/' . $event->event_banner) }}" alt="{{ $event->event_name }}" class="img-fluid" loading="lazy">
                        </a>
                    </div>
                    <div class="blog-content card-body">
                        <h6><a href="/{{ $event->event_slug }}" target="_blank">{{ $event->category_description }}</a></h6>
                        <h4><a href="/{{ $event->event_slug }}" target="_blank">{{ $event->event_name }}</a></h4><br />
                        <h4 id="place_name"><a href="/{{ $event->event_slug }}" target="_blank">{{ $event->place_name }}, {{ $event->city_name }}-{{ $event->state_uf }}</a></h4>
                    </div>
                    <span class="blog-date card-footer bg-transparent border-0">{{ \Carbon\Carbon::parse($event->min_date)->format('d/m/y') }}, {{ \Carbon\Carbon::parse($event->min_time)->format('H:i') }}h</span>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-md-12">
            <div class="alert alert-warning">
                <strong>Ops!</strong> Nenhum evento encontrado.
            </div>
        </div>
    @endif
</div>