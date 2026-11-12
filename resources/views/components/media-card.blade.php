@props(['item'])
@php
    $displayImage = $item->displayImage();
    $imageSrcset = \App\Support\MediaImage::srcset($displayImage, [360, 480, 720, 960]);
    $hasFixedPrice = $item->price_from && $item->pricing_mode !== 'on_request';
    $typeIcons = [
        'TV & Broadcast' => 'tv',
        'Radio' => 'radio',
        'Outdoor' => 'billboard',
        'Digital' => 'digital',
        'Print' => 'print',
        'Indoor' => 'spaces',
        'Creators' => 'creators',
        'Podcast' => 'radio',
    ];
@endphp
<article class="media-card">
    <a class="media-card__media" href="{{ route('media.show', $item->slug) }}" tabindex="-1" aria-hidden="true">
        <img src="{{ \App\Support\MediaImage::url($displayImage) }}"
             @if ($imageSrcset) srcset="{{ $imageSrcset }}" sizes="(max-width: 760px) 92vw, (max-width: 1024px) 46vw, 30vw" @endif
             alt="" loading="lazy" decoding="async">
        @if ($item->is_featured)
            <span class="media-card__badge">Featured</span>
        @endif
    </a>
    <div class="media-card__body">
        <div class="media-card__meta">
            <span class="tag"><x-dm-icon :name="$typeIcons[$item->offering_type] ?? 'spark'" :size="13"/>{{ $item->offering_type }}</span>
            <span class="media-card__owner">{{ $item->channel_label ?? $item->seller?->display_name ?? 'Media owner on request' }} · {{ $item->location_label ?? 'Nationwide' }}</span>
        </div>
        <h3><a href="{{ route('media.show', $item->slug) }}">{{ $item->name }}</a></h3>
        <p class="media-card__summary">{{ $item->summary }}</p>
        <div class="media-card__footer">
            <span class="media-card__price">
                @if ($hasFixedPrice)
                    Indicative from RM {{ number_format($item->price_from) }}
                @else
                    Request quotation
                @endif
                <small>{{ $hasFixedPrice ? 'Rate card guide · subject to confirmation' : 'Rates and availability on request' }}</small>
            </span>
            <a href="{{ route('media.show', $item->slug) }}" class="media-card__arrow" aria-label="View {{ $item->name }} details">↗</a>
        </div>
    </div>
</article>
