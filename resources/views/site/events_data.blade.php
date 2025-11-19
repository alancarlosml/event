<div class="events-grid">
    @if (count($events) > 0)
        @foreach ($events as $event)
            @php
                $eventDate = \Carbon\Carbon::parse($event->min_date);
                $isUpcoming = $eventDate >= now();
                $isToday = $eventDate->isToday();
                $isTomorrow = $eventDate->isTomorrow();
            @endphp
            <div class="event-card">
                <div class="event-card-image">
                    <a href="/{{ $event->event_slug }}" target="_blank">
                        <img src="{{ URL::asset('storage/' . $event->event_banner) }}" alt="{{ $event->event_name }}" loading="lazy">
                    </a>
                    @if($isToday)
                        <div class="event-card-badge" style="background: #28a745; color: white;">
                            <i class="fas fa-calendar-day"></i> Hoje
                        </div>
                    @elseif($isTomorrow)
                        <div class="event-card-badge" style="background: #ffc107; color: #333;">
                            <i class="fas fa-calendar-alt"></i> Amanhã
                        </div>
                    @elseif($isUpcoming)
                        <div class="event-card-badge">
                            <i class="fas fa-calendar-check"></i> Em breve
                        </div>
                    @endif
                </div>
                <div class="event-card-content">
                    <div class="event-card-category">
                        {{ $event->category_description }}
                    </div>
                    <h3 class="event-card-title">
                        <a href="/{{ $event->event_slug }}" target="_blank">{{ $event->event_name }}</a>
                    </h3>
                    <div class="event-card-info">
                        <div class="event-card-info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $event->place_name }}, {{ $event->city_name }}-{{ $event->state_uf }}</span>
                        </div>
                        <div class="event-card-info-item">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $eventDate->format('d/m/Y') }}</span>
                        </div>
                        <div class="event-card-info-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ \Carbon\Carbon::parse($event->min_time)->format('H:i') }}h</span>
                        </div>
                    </div>
                    <div class="event-card-footer">
                        <div class="event-card-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $eventDate->format('d/m') }}</span>
                        </div>
                        <a href="/{{ $event->event_slug }}" class="event-card-cta" target="_blank">
                            Ver evento <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="events-empty">
            <div class="events-empty-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3 class="events-empty-title">Nenhum evento encontrado</h3>
            <p class="events-empty-text">Tente ajustar os filtros de busca para encontrar mais eventos.</p>
        </div>
    @endif
</div>