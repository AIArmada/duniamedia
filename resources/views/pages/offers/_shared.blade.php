@php
    $specs = is_array($media->specifications) ? $media->specifications : [];
    $hasFixedPrice = $media->pricingMode !== 'on_request' && $media->priceFrom;
    $storyBlocks = $blocks ?? (empty($media->description) ? [] : [[
        'type' => 'rich_text',
        'payload' => ['title' => 'What this opportunity is', 'body' => $media->description],
    ]]);
    $heroPath = $media->displayImage();
@endphp

<x-page-hero
    eyebrow="Media opportunity · {{ $media->medium }}"
    title="{{ $media->name }}"
    :lede="$media->summary"
    :breadcrumb="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Media', 'url' => route('media.index')],
        ['label' => $media->name],
    ]"
>
    <x-slot:aside>
        <div class="tag-row">
            <span class="tag tag--blush">{{ $media->medium }}</span>
            @if ($media->verifiedAt)
                <span class="tag tag--verified">Verified {{ \Illuminate\Support\Carbon::parse($media->verifiedAt)->format('M Y') }}</span>
            @else
                <span class="tag">Awaiting verification</span>
            @endif
        </div>
        <p class="tiny muted">{{ $media->locationLabel ?? 'Nationwide' }} · {{ $media->sellerName ?? 'Media owner on request' }}</p>
    </x-slot:aside>
</x-page-hero>

<section class="detail">
    <div class="container detail__layout">
        <article class="detail__main">
            <figure class="detail__visual">
                <img src="{{ \App\Features\Catalog\Support\MediaImage::url($heroPath) }}" srcset="{{ \App\Features\Catalog\Support\MediaImage::srcset($heroPath, [640, 960, 1280]) }}" sizes="(max-width: 1024px) 92vw, 64vw" alt="{{ $media->name }}" width="1536" height="1024" decoding="async">
            </figure>

            <x-content-blocks :blocks="$storyBlocks"/>

            <h3>Key facts</h3>
            <div class="fact-grid">
                <div class="fact"><div class="fact__head"><x-dm-icon name="tv" :size="16"/><span>Medium</span></div><strong>{{ $media->medium }}</strong></div>
                <div class="fact"><div class="fact__head"><x-dm-icon name="layers" :size="16"/><span>Channel</span></div><strong>{{ $media->channelLabel ?? 'Confirmed on enquiry' }}</strong></div>
                <div class="fact"><div class="fact__head"><x-dm-icon name="building" :size="16"/><span>Media owner / seller</span></div><strong>{{ $media->sellerName ?? 'Confirmed on enquiry' }}</strong></div>
                <div class="fact"><div class="fact__head"><x-dm-icon name="pin" :size="16"/><span>Location</span></div><strong>{{ $media->locationLabel ?? 'To be discussed' }}</strong></div>
                <div class="fact"><div class="fact__head"><x-dm-icon name="users" :size="16"/><span>Audience</span></div><strong>{{ $media->audienceLabel ?? 'Defined during briefing' }}</strong></div>
                <div class="fact"><div class="fact__head"><x-dm-icon name="calendar" :size="16"/><span>Availability</span></div><strong>{{ str_replace('_', ' ', ucfirst($media->availabilityStatus)) }}</strong></div>
                <div class="fact"><div class="fact__head"><x-dm-icon name="chart" :size="16"/><span>Pricing basis</span></div><strong>{{ $hasFixedPrice ? 'Indicative rate card' : 'On request' }}</strong></div>
            </div>

            @if (! empty($channels))
                <h3>Channels covered</h3>
                <div class="deck-channel-grid">
                    @foreach ($channels as $channel)
                        <a class="deck-channel" href="{{ route('media.channel.show', $channel['slug']) }}">
                            <span class="deck-channel__medium">{{ $channel['medium'] ?? 'Channel' }}</span>
                            <strong>{{ $channel['name'] }}</strong>
                            <span class="deck-channel__arrow" aria-hidden="true">↗</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if (! empty($sections))
                <h3>Rates and packages</h3>
                <div class="rate-sections">
                    @foreach ($sections as $section)
                        <x-rate-section :section="$section" :show-channel="true"/>
                    @endforeach
                </div>
            @else
                <h3>Rates and packages</h3>
                <div class="empty-state" style="text-align:left">
                    <span class="eyebrow">Tailored quotation</span>
                    <h3>{{ $media->name }} is quoted on request.</h3>
                    <p style="margin-left:0">No fixed rate card is published for this offer. Share your brief and our team will confirm formats, availability and commercial terms.</p>
                    <div class="empty-state__actions" style="justify-content:flex-start">
                        <a class="button button--primary" href="{{ route('contact') }}">Request a tailored rate <span aria-hidden="true">↗</span></a>
                    </div>
                </div>
            @endif

            @php
                $officialAssets = collect($assets)->reject(fn ($asset): bool => (bool) ($asset['is_illustrative'] ?? false));
                $illustrationAssets = collect($assets)->filter(fn ($asset): bool => (bool) ($asset['is_illustrative'] ?? false));
            @endphp

            @if ($officialAssets->isNotEmpty())
                <h3>Official imagery</h3>
                <p class="gallery-note">Official or source-licensed imagery. Credits and source links are shown with each image.</p>
                <div class="story-gallery">
                    @foreach ($officialAssets as $asset)
                        <figure>
                            <img src="{{ \App\Features\Catalog\Support\MediaImage::url($asset['path']) }}" alt="{{ $asset['alt'] }}" loading="lazy" decoding="async">
                            <figcaption>
                                @if (! empty($asset['caption'])){{ $asset['caption'] }}@endif
                                @if (! empty($asset['credit']))<small>{{ $asset['credit'] }}</small>@endif
                                @if (! empty($asset['source_url']))
                                    <a class="story-gallery__source" href="{{ $asset['source_url'] }}" target="_blank" rel="noopener noreferrer">{{ $asset['source_label'] ?? 'Source' }} ↗</a>
                                @endif
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            @endif

            @if ($illustrationAssets->isNotEmpty())
                <h3>Illustrative concepts</h3>
                <p class="gallery-note">Generated illustrations for campaign mood and format explanation — not actual coverage, venues or people.</p>
                <div class="story-gallery">
                    @foreach ($illustrationAssets as $asset)
                        <figure>
                            <img src="{{ \App\Features\Catalog\Support\MediaImage::url($asset['path']) }}" alt="{{ $asset['alt'] }}" loading="lazy" decoding="async">
                            <figcaption>
                                <span class="story-gallery__badge">Illustration</span>
                                @if (! empty($asset['caption'])){{ $asset['caption'] }}@endif
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            @endif

            @if (! empty($specs['rate_card_view']))
                @include($specs['rate_card_view'], ['media' => $media, 'specs' => $specs])
            @endif
        </article>

        <aside class="detail-aside">
            <span class="eyebrow">Plan this opportunity</span>
            <h2>Build a campaign around it.</h2>
            <p>We verify the media owner, format, rate, slots and commercial conditions before any booking.</p>
            <div class="detail-price">
                @if ($hasFixedPrice)
                    Indicative from RM{{ number_format($media->priceFrom, 2) }}
                @else
                    Pricing on request
                @endif
                <small>Indicative guide only · excludes taxes and discounts</small>
            </div>
            <flux:button href="{{ route('quote.builder', ['media' => $media->id]) }}" variant="primary" class="w-full" icon:trailing="arrow-up-right">Add to proposal</flux:button>
            <flux:button href="{{ route('contact') }}" variant="outline" class="w-full">Ask a media specialist</flux:button>
            <ul class="trust-list">
                <li><x-dm-icon name="check" :size="15"/> Rates verified before quoting</li>
                <li><x-dm-icon name="check" :size="15"/> No reservation online</li>
            </ul>
            <ul class="detail-meta-list">
                <li><span>Availability</span><strong>{{ str_replace('_', ' ', ucfirst($media->availabilityStatus)) }}</strong></li>
                <li><span>Location</span><strong>{{ $media->locationLabel ?? 'Nationwide' }}</strong></li>
                @if (! empty($specs['validity']))
                    <li><span>Rate validity</span><strong>{{ $specs['validity'] }}</strong></li>
                @endif
                <li><span>Last checked</span><strong>{{ $media->verifiedAt ? \Illuminate\Support\Carbon::parse($media->verifiedAt)->format('d M Y') : 'Not yet verified' }}</strong></li>
            </ul>
            <p class="detail-note">No payment or reservation takes place online. Rates and availability are confirmed by the media owner before any agreement.</p>
        </aside>
    </div>
</section>

@if ($similar->isNotEmpty())
    <section class="section section--cream" aria-labelledby="similar-title">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Similar opportunities</span>
                    <h2 id="similar-title" class="section-title">More in {{ $media->medium }}.</h2>
                </div>
                <div class="section-heading__aside">
                    <a class="text-link" href="{{ route('media.index', ['medium' => $media->medium]) }}">View the medium <span aria-hidden="true">→</span></a>
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
