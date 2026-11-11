@props([
    'eyebrow' => null,
    'title' => '',
    'lede' => null,
    'tone' => 'cream',
    'media' => null,
    'breadcrumb' => [],
])

<section {{ $attributes->class(['page-hero', 'page-hero--dark' => $tone === 'dark', 'page-hero--media' => (bool) $media]) }}>
    @if ($media)
        <div class="page-hero__media" aria-hidden="true"><img src="{{ $media }}" alt=""></div>
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
        @isset($aside)
            <div class="page-hero__aside">{{ $aside }}</div>
        @endisset
    </div>
</section>
