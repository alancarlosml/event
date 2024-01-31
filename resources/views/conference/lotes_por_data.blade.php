<!-- lotesPartial.blade.php -->
<thead>
    <th>Lote</th>
    <th>
        @if ($event->max_event_dates() >= \Carbon\Carbon::now())
            Valor
        @endif
    </th>
    <th class="text-center">
        @if ($event->max_event_dates() >= \Carbon\Carbon::now())
            Quantidade
        @endif
    </th>
</thead>
<tbody>
    @foreach ($lotes as $lote)
        <tr class="border-bottom" lote_hash="{{ $lote->hash }}">
            <td>
                <div class="d-flex align-items-center">
                    <div class="ps-3 d-flex flex-column">
                        <p class="fw-bold text-uppercase"> <b>{{ $lote->name }} </b></p>
                        @if ($lote->description)
                            <p class="fw-bold"> {{ $lote->description }} </p>
                        @endif
                        <em>
                            @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                                Disponível até
                            @else
                                Finalizado em
                            @endif
                            {{ \Carbon\Carbon::parse($lote->datetime_end)->format('d/m/y \à\s h:i') }}
                        </em>
                    </div>
                </div>
            </td>
            <td>
                @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                    <div class="d-flex">
                        <p class="pe-3">
                            <span class="red">@money($lote->value)</span><br />
                            @if ($lote->type == 0)
                                <small>+ taxa de @money($lote->value * 0.1)</small>
                            @endif
                        </p>
                    </div>
                @endif
            </td>
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                        <span class="pe-3 ml-2">
                            <input class="inp-number" name="inp-number" type="number"
                                   style="min-width: 1.5rem"
                                   value="{{ old('inp-number', 0) }}" min="0"
                                   max="{{ $lote->limit_max }}" />
                            {{-- <input class="ps-2" type="number" value="{{$lote->limit_min}}" min="{{$lote->limit_min}}" max="{{$lote->limit_max}}"> --}}
                        </span>
                    @else
                        Encerrado
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
</tbody>