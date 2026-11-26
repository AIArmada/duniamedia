@php
    $art = $offer->displayImage();
    $srcset = \App\Features\Catalog\Support\MediaImage::srcset($art, [480, 640, 960]);
    $medium = $feature['medium'] ?? $offer->medium ?? 'Media';
    $icon = $feature['icon'] ?? match ($medium) {
        'TV & Broadcast' => 'tv',
        'Radio', 'Podcast' => 'radio',
        'Outdoor' => 'billboard',
        'Digital' => 'digital',
        'Print' => 'print',
        default => 'layers',
    };
@endphp

<article class="owner-editorial-offer">
    <a class="owner-editorial-offer__visual" href="{{ route('offers.show', $offer->slug) }}" tabindex="-1" aria-hidden="true">
        <img src="{{ \App\Features\Catalog\Support\MediaImage::url($art) }}" @if ($srcset) srcset="{{ $srcset }}" sizes="(max-width: 560px) 92vw, (max-width: 1000px) 46vw, 25vw" @endif alt="" loading="lazy" decoding="async">
    </a>
    <div class="owner-editorial-offer__body">
        <span class="owner-editorial-offer__medium"><x-dm-icon :name="$icon" :size="17"/>{{ $medium }}</span>
        <h3><a href="{{ route('offers.show', $offer->slug) }}">{{ $feature['title'] ?? $offer->name }}</a></h3>
        @if ($description = $feature['description'] ?? $offer->summary)<p>{{ $description }}</p>@endif
        <a class="owner-editorial-offer__link" href="{{ route('offers.show', $offer->slug) }}" aria-label="View rate card for {{ $feature['title'] ?? $offer->name }}">View rate card <span aria-hidden="true">↗</span></a>
    </div>
</article>
