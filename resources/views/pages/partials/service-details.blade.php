@if ($detail)
    <details class="services-detail">
        <summary>Explore this service <span aria-hidden="true">+</span></summary>
        <div class="services-detail__content">
            @foreach ($detail['copy'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
            <h4>What we do</h4>
            <ul>
                @foreach ($detail['offers'] as $offer)
                    <li>{{ $offer }}</li>
                @endforeach
            </ul>
            <h4>Typical deliverables</h4>
            <ul>
                @foreach ($detail['deliverables'] as $deliverable)
                    <li>{{ $deliverable }}</li>
                @endforeach
            </ul>
            <a class="text-link" href="{{ $detail['proof']['url'] }}">{{ $detail['proof']['label'] }} <span aria-hidden="true">→</span></a>
        </div>
    </details>
@else
    <a class="services-detail services-detail--link" href="{{ route('contact') }}">Discuss this service <span aria-hidden="true">↗</span></a>
@endif
