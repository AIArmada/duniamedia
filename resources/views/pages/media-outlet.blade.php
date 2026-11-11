@extends('layouts.app')
@section('content')
<section class="page-hero compact media-directory-hero">
    <div class="container">
        <nav class="media-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('media.index') }}">Media</a><span>/</span>
            <a href="{{ route('media.owner', $owner['slug']) }}">{{ $owner['name'] }}</a><span>/</span>
            <span>{{ $outlet['name'] }}</span>
        </nav>

        <div class="media-owner-hero media-owner-hero--outlet">
            <div class="media-owner-hero__logo">
                @if(!empty($outlet['logo']))
                    <img src="{{ \App\Support\MediaImage::url('assets/partners/'.$outlet['logo']) }}" alt="{{ $outlet['name'] }}">
                @else
                    <span>{{ $outlet['name'] }}</span>
                @endif
            </div>
            <div class="media-owner-hero__copy">
                <span class="eyebrow pale">{{ $outlet['category'] }}</span>
                <h1>{{ $outlet['name'] }}</h1>
                <p>{{ $description }}</p>
                <div class="media-owner-hero__stats">
                    <a href="{{ route('media.owner', $owner['slug']) }}">Part of {{ $owner['name'] }} ↗</a>
                    <span>{{ $rateCards->count() }} rate {{ $rateCards->count() === 1 ? 'card' : 'cards' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        @if($rateCards->isNotEmpty())
            <div class="section-heading">
                <div>
                    <span class="eyebrow">RATE CARDS & PACKAGES</span>
                    <h2>Ways to appear on {{ $outlet['name'] }}.</h2>
                    <p>Indicative rates, formats and source documents — all hosted in the app.</p>
                </div>
                <a class="text-link" href="{{ route('media.owner', $owner['slug']) }}">All {{ $owner['name'] }} rate cards →</a>
            </div>
            <div class="cards-3">
                @foreach($rateCards as $item)
                    <x-media-card :item="$item" />
                @endforeach
            </div>
        @else
            <div class="media-outlet-empty">
                <span class="eyebrow">TAILORED PROPOSAL</span>
                <h2>Rates for {{ $outlet['name'] }} are available on request.</h2>
                <p>We will confirm current slots, formats and availability for this outlet. Ask our team for a tailored package.</p>
                <a class="btn btn-primary" href="{{ route('contact') }}">Request Rates <span aria-hidden="true">↗</span></a>
            </div>
        @endif
    </div>
</section>

@if($siblings->isNotEmpty())
<section class="section section-soft">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">MORE FROM {{ strtoupper($owner['name']) }}</span>
                <h2>Explore the group.</h2>
            </div>
            <a class="text-link" href="{{ route('media.owner', $owner['slug']) }}">View all outlets →</a>
        </div>
        <div class="media-outlet-grid">
            @foreach($siblings as $sibling)
                <a class="media-outlet-tile" href="{{ route('media.outlet', [$owner['slug'], $sibling['slug']]) }}">
                    <span class="media-outlet-tile__mark @if(empty($sibling['logo'])) media-outlet-tile__mark--wordmark @endif">
                        @if(!empty($sibling['logo']))
                            <img src="{{ \App\Support\MediaImage::url('assets/partners/'.$sibling['logo']) }}" alt="" loading="lazy">
                        @else
                            {{ $sibling['name'] }}
                        @endif
                    </span>
                    <span class="media-outlet-tile__name">{{ $sibling['name'] }}</span>
                    <span class="media-outlet-tile__arrow" aria-hidden="true">↗</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="rc-cta">
    <div class="container rc-cta__inner">
        <div>
            <span class="eyebrow pale">Plan this outlet</span>
            <h2>Put {{ $outlet['name'] }} in your next campaign.</h2>
            <p>Tell us the audience and outcome; we will shape the format, schedule and budget.</p>
        </div>
        <div class="rc-cta__actions">
            <a class="btn btn-white" href="{{ route('quote.builder') }}">Start a Proposal <span aria-hidden="true">↗</span></a>
            <a class="btn btn-ghost-light" href="{{ route('contact') }}">Talk to a Specialist</a>
        </div>
    </div>
</section>
@endsection
