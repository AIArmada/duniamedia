@props([
    'eyebrow' => null,
    'title' => '',
    'lede' => null,
    'tone' => 'cream',
    'media' => null,
    'mediaMobile' => null,
    'image' => null,
    'imageMobile' => null,
    'imageAlt' => null,
    'breadcrumb' => [],
])

<section {{ $attributes->class(['page-hero', 'page-hero--dark' => $tone === 'dark', 'page-hero--media' => (bool) $media, 'page-hero--split' => (bool) $image && ! $media]) }}>
    @if ($media)
        <div class="page-hero__media" aria-hidden="true">
            <picture>
                @if ($mediaMobile)
                    <source media="(max-width: 760px)" srcset="{{ \App\Support\MediaImage::srcset($mediaMobile, [480, 640, 960]) }}" sizes="100vw">
                @endif
                <img src="{{ \App\Support\MediaImage::url($media) }}"
                     srcset="{{ \App\Support\MediaImage::srcset($media, [640, 960, 1280]) }}"
                     sizes="100vw" alt="" width="1672" height="941" loading="eager" decoding="async">
            </picture>
        </div>
    @endif
    <div class="container page-hero__inner">
        <div class="page-hero__copy">
            @if (! empty($breadcrumb))
                <nav class="breadcrumb" aria-label="Breadcrumb">
                    @foreach ($breadcrumb as $crumb)
                        @if (! $loop->last && ! empty($crumb['url']))
                            <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        @else
                            <span @if ($loop->last) aria-current="page" @endif>{{ $crumb['label'] }}</span>
                        @endif
                        @if (! $loop->last)<span aria-hidden="true">/</span>@endif
                    @endforeach
                </nav>
            @endif
            @if ($eyebrow)
                <span class="eyebrow @if ($tone === 'dark' || $media) eyebrow--pale @endif">{{ $eyebrow }}</span>
            @endif
            <h1>{!! $title !!}</h1>
            @if ($lede)
                <p class="page-hero__lede">{{ $lede }}</p>
            @endif
            @isset($actions)
                <div class="page-hero__actions">{{ $actions }}</div>
            @endisset
        </div>
        @if ($image && ! $media)
            <figure class="page-hero__visual">
                <picture>
                    @if ($imageMobile)
                        <source media="(max-width: 760px)" srcset="{{ \App\Support\MediaImage::srcset($imageMobile, [480, 640, 960]) }}" sizes="(max-width: 760px) 92vw, 38vw">
                    @endif
                    <img src="{{ \App\Support\MediaImage::url($image) }}"
                         srcset="{{ \App\Support\MediaImage::srcset($image, [480, 960, 1280]) }}"
                         sizes="(max-width: 1024px) 92vw, 38vw"
                         alt="{{ $imageAlt ?? '' }}" width="1536" height="1024"
                         loading="eager" decoding="async">
                </picture>
            </figure>
        @endif
        @isset($aside)
            <div class="page-hero__aside">{{ $aside }}</div>
        @endisset
    </div>
</section>
