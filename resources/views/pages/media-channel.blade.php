@extends('layouts.app')

@section('content')
    <section class="directory-hero">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span>
                <a href="{{ route('media.index') }}">Media</a><span aria-hidden="true">/</span>
                <a href="{{ route('media.owners') }}">Media owners</a><span aria-hidden="true">/</span>
                <a href="{{ route('media.owner', $owner['slug']) }}">{{ $owner['name'] }}</a><span aria-hidden="true">/</span>
                <span aria-current="page">{{ $channel['name'] }}</span>
            </nav>

            <div class="directory-hero__head">
                <div>
                    <div class="directory-hero__logo">
                        @if (! empty($channel['logo']))
                            <img src="{{ \App\Support\MediaImage::url('assets/partners/'.$channel['logo']) }}" alt="{{ $channel['name'] }}">
                        @else
                            <span>{{ $channel['name'] }}</span>
                        @endif
                    </div>
                    <span class="eyebrow">{{ $channel['category'] }}</span>
                    <h1>{{ $channel['name'] }}</h1>
                    <p class="directory-hero__lede">{{ $description }}</p>
                    <div class="directory-hero__stats">
                        <span><x-dm-icon name="building" :size="16"/><a href="{{ route('media.owner', $owner['slug']) }}">Part of {{ $owner['name'] }} ↗</a></span>
                        <span><x-dm-icon name="document" :size="16"/><strong>{{ $rateCards->count() }}</strong> rate {{ $rateCards->count() === 1 ? 'card' : 'cards' }}</span>
                    </div>
                </div>
                <div class="directory-hero__actions">
                    <a class="button button--primary" href="{{ route('quote.builder') }}">Start a proposal <span aria-hidden="true">↗</span></a>
                    <a class="button button--secondary" href="{{ route('contact') }}">Request rates</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if ($rateCards->isNotEmpty())
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Rate cards &amp; packages</span>
                        <h2 class="section-title">Ways to appear on {{ $channel['name'] }}.</h2>
                        <p>Indicative rates, formats and source documents — all hosted in the platform.</p>
                    </div>
                    <div class="section-heading__aside">
                        <a class="text-link" href="{{ route('media.owner', $owner['slug']) }}">All {{ $owner['name'] }} rate cards <span aria-hidden="true">→</span></a>
                    </div>
                </div>
                <div class="media-grid">
                    @foreach ($rateCards as $item)
                        <x-media-card :item="$item"/>
                    @endforeach
                </div>
            @else
                <div class="empty-state" style="text-align:left">
                    <span class="eyebrow">Tailored proposal</span>
                    <h3>Rates for {{ $channel['name'] }} are available on request.</h3>
                    <p style="margin-left:0">We will confirm current slots, formats and availability for this channel. Ask our team for a tailored package.</p>
                    <div class="empty-state__actions" style="justify-content:flex-start">
                        <a class="button button--primary" href="{{ route('contact') }}">Request rates <span aria-hidden="true">↗</span></a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if ($siblings->isNotEmpty())
        <section class="section section--cream">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">More from {{ $owner['name'] }}</span>
                        <h2 class="section-title">Explore the group.</h2>
                    </div>
                    <div class="section-heading__aside">
                        <a class="text-link" href="{{ route('media.owner', $owner['slug']) }}">View all channels <span aria-hidden="true">→</span></a>
                    </div>
                </div>
                <div class="directory-card-grid">
                    @foreach ($siblings as $sibling)
                        <a class="directory-card" href="{{ route('media.channel', [$owner['slug'], $sibling['slug']]) }}">
                            <span class="directory-card__mark">
                                @if (! empty($sibling['logo']))
                                    <img src="{{ \App\Support\MediaImage::url('assets/partners/'.$sibling['logo']) }}" alt="" loading="lazy">
                                @else
                                    {{ $sibling['name'] }}
                                @endif
                            </span>
                            <span class="directory-card__name">{{ $sibling['name'] }}</span>
                            <span class="directory-card__arrow" aria-hidden="true">↗</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section section--dark">
        <div class="container">
            <div class="cta-panel__inner">
                <div>
                    <span class="eyebrow eyebrow--pale">Plan this channel</span>
                    <h2 class="section-title">Put {{ $channel['name'] }} in your next campaign.</h2>
                    <p style="max-width:560px;margin-top:0.7rem;color:rgba(255,248,247,.66)">Tell us the audience and outcome; we will shape the format, schedule and budget.</p>
                </div>
                <div class="cta-panel__actions">
                    <a class="button button--on-dark" href="{{ route('quote.builder') }}">Start a proposal <span aria-hidden="true">↗</span></a>
                    <a class="button button--outline-light" href="{{ route('contact') }}">Talk to a specialist</a>
                </div>
            </div>
        </div>
    </section>
@endsection
