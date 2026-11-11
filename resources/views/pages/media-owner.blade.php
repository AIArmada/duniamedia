@extends('layouts.app')
@section('content')
<section class="page-hero compact media-directory-hero">
    <div class="container">
        <nav class="media-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('media.index') }}">Media</a><span>/</span>
            <span>{{ $owner['name'] }}</span>
        </nav>

        <div class="media-owner-hero">
            <div class="media-owner-hero__logo">
                @if(!empty($owner['logo']))
                    <img src="{{ \App\Support\MediaImage::url('assets/partners/'.$owner['logo']) }}" alt="{{ $owner['name'] }}">
                @else
                    <span>{{ $owner['mark'] ?? $owner['name'] }}</span>
                @endif
            </div>
            <div class="media-owner-hero__copy">
                <span class="eyebrow pale">Media owner</span>
                <h1>{{ $owner['name'] }}</h1>
                <p>{{ $owner['description'] }}</p>
                <div class="media-owner-hero__stats">
                    <span><strong>{{ count($owner['outlets']) }}</strong> outlets</span>
                    <span><strong>{{ count($owner['categories']) }}</strong> categories</span>
                    <span><strong>{{ $rateCards->count() }}</strong> rate cards</span>
                </div>
            </div>
        </div>
    </div>
</section>

@if($rateCards->isNotEmpty())
<section class="section" id="rate-cards">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">RATE CARDS & PACKAGES</span>
                <h2>Current opportunities.</h2>
                <p>Hosted inside the app, with indicative rates and source documents.</p>
            </div>
            <a class="text-link" href="{{ route('media.index') }}">Browse all media →</a>
        </div>
        <div class="cards-3">
            @foreach($rateCards as $item)
                <x-media-card :item="$item" />
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section section-soft">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">THE ROSTER</span>
                <h2>Outlets under {{ $owner['name'] }}.</h2>
                <p>Each outlet has its own page with audience context and related rate cards.</p>
            </div>
        </div>

        <div class="media-directory-categories">
            @foreach($owner['categories'] as $category)
                <section class="media-directory-category">
                    <div class="media-directory-category__head">
                        <h3>{{ $category['label'] }}</h3>
                        <span>{{ count($category['brands']) }} outlets</span>
                    </div>
                    <div class="media-outlet-grid">
                        @foreach(collect($owner['outlets'])->where('category', $category['label']) as $outlet)
                            <a class="media-outlet-tile" href="{{ route('media.outlet', [$owner['slug'], $outlet['slug']]) }}">
                                <span class="media-outlet-tile__mark @if(empty($outlet['logo'])) media-outlet-tile__mark--wordmark @endif">
                                    @if(!empty($outlet['logo']))
                                        <img src="{{ \App\Support\MediaImage::url('assets/partners/'.$outlet['logo']) }}" alt="" loading="lazy">
                                    @else
                                        {{ $outlet['name'] }}
                                    @endif
                                </span>
                                <span class="media-outlet-tile__name">{{ $outlet['name'] }}</span>
                                <span class="media-outlet-tile__arrow" aria-hidden="true">↗</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</section>

<section class="rc-cta">
    <div class="container rc-cta__inner">
        <div>
            <span class="eyebrow pale">Plan across the group</span>
            <h2>Build a cross-channel proposal.</h2>
            <p>Combine TV, radio, digital and out-of-home from {{ $owner['name'] }} in one brief.</p>
        </div>
        <div class="rc-cta__actions">
            <a class="btn btn-white" href="{{ route('quote.builder') }}">Start a Proposal <span aria-hidden="true">↗</span></a>
            <a class="btn btn-ghost-light" href="{{ route('contact') }}">Talk to a Specialist</a>
        </div>
    </div>
</section>
@endsection
