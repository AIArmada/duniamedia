@extends('layouts.app')

@section('content')
    @php
        $specs = is_array($media->specifications) ? $media->specifications : (is_string($media->specifications) ? (json_decode($media->specifications, true) ?: []) : []);
        $documents = $specs['documents'] ?? [];
        $videos = collect($documents)->filter(fn ($document) => ($document['kind'] ?? '') === 'video');
        $hasFixedPrice = $media->pricing_mode !== 'on_request' && $media->price_from;
    @endphp

    <x-page-hero
        eyebrow="Media opportunity · {{ $media->type }}"
        title="{{ e($media->title) }}"
        :lede="$media->summary"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Media', 'url' => route('media.index')],
            ['label' => $media->title],
        ]"
    >
        <x-slot:aside>
            <div class="tag-row">
                <span class="tag tag--blush">{{ $media->type }}</span>
                @if ($media->verified_at)
                    <span class="tag tag--verified">Verified {{ $media->verified_at->format('M Y') }}</span>
                @else
                    <span class="tag">Awaiting verification</span>
                @endif
            </div>
            <p class="tiny muted">{{ $media->location ?? 'Nationwide' }} · {{ $media->supplier_name ?? $media->channel ?? 'Media owner on request' }}</p>
        </x-slot:aside>
    </x-page-hero>

    <section class="detail">
        <div class="container detail__layout">
            <article class="detail__main">
                <figure class="detail__visual">
                    <img src="{{ \App\Support\MediaImage::url($media->image) }}" alt="{{ $media->title }}" width="1200" height="675" decoding="async">
                </figure>

                <h2>What this opportunity is</h2>
                <p>{{ $media->description ?: $media->summary }}</p>

                <h3>Key facts</h3>
                <div class="fact-grid">
                    <div class="fact"><span>Channel</span><strong>{{ $media->type }}</strong></div>
                    <div class="fact"><span>Owner / supplier</span><strong>{{ $media->channel ?? $media->supplier_name ?? 'Confirmed on enquiry' }}</strong></div>
                    <div class="fact"><span>Location</span><strong>{{ $media->location ?? 'To be discussed' }}</strong></div>
                    <div class="fact"><span>Audience</span><strong>{{ $media->audience ?? 'Defined during briefing' }}</strong></div>
                    <div class="fact"><span>Availability</span><strong>{{ str_replace('_', ' ', ucfirst($media->availability_status)) }}</strong></div>
                    <div class="fact"><span>Pricing basis</span><strong>{{ $hasFixedPrice ? 'Indicative rate card' : 'On request' }}</strong></div>
                </div>

                @if (! empty($specs['formats']) || ! empty($specs['price_tiers']))
                    <h3>Formats and rate-card detail</h3>
                    <div class="fact-grid">
                        <div class="fact">
                            <span>Typical formats</span>
                            <strong>{{ is_array($specs['formats'] ?? null) ? implode(' · ', $specs['formats']) : ($specs['formats'] ?? '—') }}</strong>
                        </div>
                        <div class="fact">
                            <span>Rate tiers</span>
                            <strong>{{ is_array($specs['price_tiers'] ?? null) ? implode(' · ', $specs['price_tiers']) : ($specs['price_tiers'] ?? 'On request') }}</strong>
                        </div>
                    </div>
                    <p class="tiny muted">Indicative rate-card figures before SST and applicable discounts. Final formats, slots, artwork lead times and approvals are verified by our team before booking.</p>
                @endif

                @if (! empty($specs['validity']) || ! empty($specs['source_file']) || ! empty($specs['sales_contact']))
                    <h3>Source and validity</h3>
                    <ul class="detail-meta-list">
                        @if (! empty($specs['validity']))
                            <li><span>Rate validity</span><strong>{{ $specs['validity'] }}</strong></li>
                        @endif
                        @if (! empty($specs['source_file']))
                            <li><span>Source document</span><strong>{{ $specs['source_file'] }}</strong></li>
                        @endif
                        @if (! empty($specs['sales_contact']))
                            <li><span>Sales contact</span><strong>{{ $specs['sales_contact'] }}</strong></li>
                        @endif
                    </ul>
                @endif

                @if (! empty($documents))
                    <h3 id="rate-card">Rate card and source documents</h3>
                    <div class="ratecard-docs">
                        @foreach ($documents as $i => $document)
                            <a class="ratecard-doc" href="{{ route('media.ratecard', [$media->slug, $i]) }}" target="_blank" rel="noopener">
                                <strong>{{ $document['name'] ?? 'Rate card' }}</strong>
                                <span class="tiny muted">{{ strtoupper($document['ext'] ?? 'FILE') }} · {{ number_format(($document['size'] ?? 0) / 1024 / 1024, 1) }} MB · open full screen ↗</span>
                            </a>
                        @endforeach
                    </div>

                    @php($primary = $documents[0])
                    @if (($primary['ext'] ?? '') === 'pdf')
                        <div class="ratecard-frame">
                            <iframe src="{{ route('media.ratecard', [$media->slug, 0]) }}#view=FitH" title="Rate card for {{ $media->title }}" loading="lazy"></iframe>
                        </div>
                    @elseif (in_array($primary['ext'] ?? '', ['jpg', 'jpeg', 'png']))
                        <div class="ratecard-frame ratecard-image">
                            <img src="{{ route('media.ratecard', [$media->slug, 0]) }}" alt="Rate card for {{ $media->title }}" loading="lazy">
                        </div>
                    @else
                        <div class="ratecard-fallback">
                            <p>This workbook opens in a spreadsheet app. <a href="{{ route('media.ratecard', [$media->slug, 0]) }}">Open the hosted file ↗</a></p>
                            <p class="tiny muted">Key rates are summarised above; the original workbook stays on our server.</p>
                        </div>
                    @endif
                @endif

                @if ($videos->isNotEmpty())
                    <h3>See the placement in motion</h3>
                    <div class="ratecard-videos">
                        @foreach ($documents as $i => $document)
                            @if (($document['kind'] ?? '') === 'video')
                                <figure class="ratecard-video">
                                    <video controls playsinline preload="metadata" poster="{{ asset($document['poster'] ?? '') }}">
                                        <source src="{{ route('media.ratecard', [$media->slug, $i]) }}" type="video/mp4">
                                        Your browser does not support video.
                                    </video>
                                    <figcaption>{{ $document['name'] ?? 'Walkthrough' }} <span class="tiny muted">· {{ number_format(($document['size'] ?? 0) / 1024 / 1024, 1) }} MB</span></figcaption>
                                </figure>
                            @endif
                        @endforeach
                    </div>
                @endif
            </article>

            <aside class="detail-aside">
                <span class="eyebrow">Plan this opportunity</span>
                <h2>Build a campaign around it.</h2>
                <p>We verify the supplier, format, rate, slots and commercial conditions before any booking.</p>
                <div class="detail-price">
                    @if ($hasFixedPrice)
                        Indicative from RM{{ number_format($media->price_from, 2) }}
                    @else
                        Pricing on request
                    @endif
                    <small>Indicative guide only · excludes taxes and discounts</small>
                </div>
                <a class="button button--primary button--block" href="{{ route('quote.builder', ['media' => $media->id]) }}">Add to proposal <span aria-hidden="true">↗</span></a>
                <a class="button button--secondary button--block" href="{{ route('contact') }}">Ask a media specialist</a>
                <ul class="detail-meta-list">
                    <li><span>Availability</span><strong>{{ str_replace('_', ' ', ucfirst($media->availability_status)) }}</strong></li>
                    <li><span>Location</span><strong>{{ $media->location ?? 'Nationwide' }}</strong></li>
                    <li><span>Last checked</span><strong>{{ $media->verified_at?->format('d M Y') ?? 'Not yet verified' }}</strong></li>
                </ul>
                <p class="detail-note">No payment or reservation takes place online. Rates and availability are confirmed by the media owner before any agreement.</p>
            </aside>
        </div>
    </section>

    @if (! empty($specs['rate_card_view']))
        @include($specs['rate_card_view'], ['media' => $media, 'specs' => $specs])
    @endif

    @if ($similar->isNotEmpty())
        <section class="section section--cream" aria-labelledby="similar-title">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Similar opportunities</span>
                        <h2 id="similar-title" class="section-title">More in {{ $media->type }}.</h2>
                    </div>
                    <div class="section-heading__aside">
                        <a class="text-link" href="{{ route('media.index', ['type' => $media->type]) }}">View the category <span aria-hidden="true">→</span></a>
                    </div>
                </div>
                <div class="media-grid">
                    @foreach ($similar as $item)
                        <x-media-card :item="$item"/>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
