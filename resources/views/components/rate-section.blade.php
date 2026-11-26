@props(['section', 'showChannel' => false, 'compact' => false, 'anchor' => null])

@php
    $money = static function ($value): string {
        if ($value === null) {
            return 'On request';
        }

        $decimals = fmod((float) $value, 1.0) === 0.0 ? 0 : 2;

        return 'RM '.number_format((float) $value, $decimals);
    };
    $matrixIds = $compact ? collect($section['tables'])->flatMap(
        fn (array $table) => collect($table['rows'])->flatMap(
            fn (array $row) => collect($row['cells'])->only($table['columns'])->pluck('id')
        )
    )->all() : [];
    $listEntries = collect($section['entries'])->reject(fn (array $entry): bool => in_array($entry['id'], $matrixIds, true))->values();
@endphp

<section class="rate-section" id="{{ $anchor ?? 'rate-'.$section['key'] }}">
    <header class="rate-section__head">
        <div>
            <h3>{{ $section['title'] }}</h3>
            @if ($section['is_media_value'])
                <p>Reference media value only — not a payable rate or purchasable placement.</p>
            @endif
            @if (! empty($section['is_group_fallback']))
                <p>Group package — confirm applicability to this channel with our team.</p>
            @endif
            @if (! empty($section['intro']))
                <p>{{ $section['intro'] }}</p>
            @endif
        </div>
        @if ($showChannel && ! empty($section['channels']))
            <div class="rate-section__channels">
                @foreach ($section['channels'] as $channel)
                    <span>{{ $channel['name'] }}</span>
                @endforeach
            </div>
        @endif
    </header>

    @foreach ($section['tables'] as $table)
        <div class="rate-matrix-scroll">
            <table class="rate-matrix">
                <thead>
                    <tr>
                        <th scope="col">Rate</th>
                        @foreach ($table['columns'] as $column)
                            <th scope="col">{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table['rows'] as $row)
                        <tr>
                            <th scope="row">{{ $row['label'] }}</th>
                            @foreach ($table['columns'] as $column)
                                @php($cell = $row['cells'][$column] ?? null)
                                <td>
                                    @if ($cell && ($cell['price'] !== null || $compact))
                                        <span class="rate-matrix__price">{{ $money($cell['price']) }}</span>
                                        @if (! empty($cell['unit']))
                                            <small>{{ $cell['unit'] }}</small>
                                        @endif
                                        @if (! empty($cell['price_is_inclusive']))
                                            <small>Inclusive of SST</small>
                                        @elseif ($compact && $cell['price_incl_sst'] !== null && $cell['price_incl_sst'] !== $cell['price'])
                                            <small>{{ $money($cell['price_incl_sst']) }} incl. SST</small>
                                        @endif
                                        @if ($compact && ! empty($cell['notes']))
                                            <details class="channel-rate-note"><summary>Rate notes</summary><small>{{ $cell['notes'] }}</small></details>
                                        @endif
                                        @if (! $section['is_media_value'] && $cell['price'] > 0 && ($cell['selectable'] ?? true))
                                            <form method="POST" action="{{ route('plans.rate.add', $cell['id']) }}" class="rate-matrix__add">
                                                @csrf
                                                <button type="submit" aria-label="Add {{ $cell['item'] }} to plan">+ Plan</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="rate-matrix__empty">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    @if ($listEntries->isNotEmpty())
        <div class="rate-list">
            @foreach ($listEntries as $entry)
                <div class="rate-line">
                    <div class="rate-line__main">
                        <div class="rate-line__title">
                            <strong>{{ $entry['item'] }}</strong>
                            @if (! empty($entry['is_package']))
                                <span class="rate-line__badge">Package</span>
                            @endif
                            @if (! ($entry['selectable'] ?? true))
                                <span class="rate-line__badge rate-line__badge--info">Component</span>
                            @endif
                        </div>
                        <span class="rate-line__meta">
                            {{ $entry['unit'] ?: 'Per placement' }}
                            @if ($showChannel && ! empty($section['channels']) && count($section['channels']) === 1)
                                · {{ $section['channels'][0]['name'] }}
                            @endif
                        </span>
                        @if (! empty($entry['notes']))
                            <small class="rate-line__notes">{{ $entry['notes'] }}</small>
                        @endif
                    </div>
                    <div class="rate-line__price">
                        <strong>{{ $money($entry['price']) }}</strong>
                        @if (! empty($entry['price_is_inclusive']))
                            <small>Inclusive of SST</small>
                        @elseif ($entry['price'] !== null && ! empty($entry['price_incl_sst']) && $entry['price_incl_sst'] !== $entry['price'])
                            <small>{{ $money($entry['price_incl_sst']) }} incl. SST</small>
                        @endif
                        @if ($entry['price'] === null)
                            <small>Confirmed on request</small>
                        @endif
                        @if (! $section['is_media_value'] && $entry['price'] !== null && $entry['price'] > 0 && ($entry['selectable'] ?? true))
                            <form method="POST" action="{{ route('plans.rate.add', $entry['id']) }}" class="rate-line__action">
                                @csrf
                                <button type="submit" class="rate-add">Add to plan</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
