@props(['stats' => []])

@if (! empty($stats))
    <div class="stat-strip">
        @foreach ($stats as $stat)
            <div class="stat-strip__item">
                <strong>{{ $stat['value'] ?? '—' }}</strong>
                <span>{{ $stat['label'] ?? '' }}</span>
            </div>
        @endforeach
    </div>
@endif
