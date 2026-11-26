@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="Search"
        title="Find a medium, an owner, a creator or a <span class='editorial'>case study</span>."
        lede="Search across media owners, channels, programmes, rate lines, creators, campaign history and editorial insights. Use the category pages when you want to browse rather than search."
    >
        <x-slot:actions>
            <a class="button button--secondary" href="{{ route('media.owners') }}">Media owners</a>
            <a class="button button--secondary" href="{{ route('creators.index') }}">Creators</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <form method="get" action="{{ route('search') }}" class="filter-horizontal" style="display:grid;grid-template-columns:1fr auto;gap:.6rem;margin-bottom:1.8rem">
                <flux:input type="search" name="q" value="{{ $query }}" placeholder="Search media, owners, creators, campaigns, insights…" aria-label="Search Dunia Media" icon="magnifying-glass" autofocus />
                <flux:button type="submit" variant="primary" icon="magnifying-glass">Search</flux:button>
            </form>

            @if ($query === '')
                <div class="topic-grid">
                    <article class="topic"><span class="tag tag--blush">Media</span><h3>Browse by medium</h3><p>Television, radio, outdoor, digital, print, creators and place-based media.</p><a class="text-link" href="{{ route('media.index') }}">Open the media explorer →</a></article>
                    <article class="topic"><span class="tag tag--blush">Owners</span><h3>Browse by owner</h3><p>National broadcasters, publishers, out-of-home networks and specialist channels.</p><a class="text-link" href="{{ route('media.owners') }}">Open the owner directory →</a></article>
                    <article class="topic"><span class="tag tag--blush">Creators</span><h3>Browse by creator</h3><p>Niche voices and communities matched by platform, interest and fit.</p><a class="text-link" href="{{ route('creators.index') }}">Open the creator network →</a></article>
                    <article class="topic"><span class="tag tag--blush">Work</span><h3>Browse the archive</h3><p>Campaigns, clients, channels and the outcomes behind the work.</p><a class="text-link" href="{{ route('work.index') }}">Open the work archive →</a></article>
                </div>
            @else
                <p class="tiny muted" style="margin-bottom:1.4rem">
                    {{ $media->count() + $creators->count() + $campaigns->count() + $articles->count() + count($directoryResults) + count($rateResults) }} results for “{{ $query }}”.
                </p>

                @if ($directoryResults !== [])
                    <div class="section-heading"><div><span class="eyebrow">Media directory</span><h2 class="section-title">Owners, channels &amp; programmes.</h2></div></div>
                    <div class="market-search-results">
                        @foreach ($directoryResults as $result)
                            <article class="market-search-result"><span class="tag">{{ $result['type'] }}</span><h3><a href="{{ $result['url'] }}">{{ $result['title'] }}</a></h3><p>{{ $result['description'] }}</p><a href="{{ $result['url'] }}">Explore →</a></article>
                        @endforeach
                    </div>
                @endif

                @if ($rateResults !== [])
                    <div class="section-heading"><div><span class="eyebrow">Rate lines</span><h2 class="section-title">Matching advertising rates.</h2></div><a class="text-link" href="{{ route('rates.index', ['q' => $query]) }}">Compare all matching rates →</a></div>
                    <div class="market-search-results">
                        @foreach ($rateResults as $rate)
                            <article class="market-search-result"><span class="tag">{{ $rate->channelName ?? $rate->channelMedium }}</span><h3><a href="{{ route('offers.show', $rate->productSlug) }}">{{ $rate->item }}</a></h3><p>{{ $rate->isPriced() ? 'Indicative RM '.number_format($rate->price, 2) : 'On request' }} · {{ $rate->unit }}</p><a href="{{ route('offers.show', $rate->productSlug) }}">View rate card →</a></article>
                        @endforeach
                    </div>
                @endif

                @if ($media->isNotEmpty())
                    <div class="section-heading">
                        <div><span class="eyebrow">Media opportunities</span><h2 class="section-title">{{ $media->count() }} {{ $media->count() === 1 ? 'match' : 'matches' }}.</h2></div>
                        <div class="section-heading__aside"><a class="text-link" href="{{ route('media.index', ['q' => $query]) }}">Search the full catalogue →</a></div>
                    </div>
                    <div class="media-grid">
                        @foreach ($media as $item)
                            <x-media-card :item="$item"/>
                        @endforeach
                    </div>
                @endif

                @if ($creators->isNotEmpty())
                    <div class="section-heading" style="margin-top:2rem">
                        <div><span class="eyebrow">Creators</span><h2 class="section-title">{{ $creators->count() }} {{ $creators->count() === 1 ? 'profile' : 'profiles' }}.</h2></div>
                    </div>
                    <div class="media-grid">
                        @foreach ($creators as $creator)
                            <article class="creator-card">
                                <div class="creator-card__image"><img src="{{ \App\Features\Catalog\Support\MediaImage::url($creator->imageUrl) }}" alt="{{ $creator->name }}" loading="lazy"></div>
                                <div class="creator-card__body">
                                    <div class="creator-card__meta"><span class="tag tag--verified">Verified</span><span class="tag">{{ $creator->socialPlatform }}</span></div>
                                    <h3>{{ $creator->name }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($creator->bio, 140) }}</p>
                                    <a href="{{ route('creators.show', $creator->slug) }}" class="text-link">View creator profile →</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                @if ($campaigns->isNotEmpty())
                    <div class="section-heading" style="margin-top:2rem">
                        <div><span class="eyebrow">Campaign history</span><h2 class="section-title">{{ $campaigns->count() }} {{ $campaigns->count() === 1 ? 'campaign' : 'campaigns' }}.</h2></div>
                        <div class="section-heading__aside"><a class="text-link" href="{{ route('work.index', ['q' => $query]) }}">Search the work archive →</a></div>
                    </div>
                    <div class="work-grid">
                        @foreach ($campaigns as $campaign)
                            <article class="work-card">
                                <header class="work-card__head">
                                    <div class="work-card__client"><span class="work-card__client-name">{{ $campaign['client_name'] }}</span></div>
                                    <span class="work-card__year">{{ $campaign['year'] }}</span>
                                </header>
                                <div class="work-card__body">
                                    <span class="work-card__service">{{ $campaign['channel'] }}</span>
                                    <h3>{{ $campaign['title'] }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($campaign['description'], 160) }}</p>
                                    <a class="text-link" href="{{ route('work.index', ['client' => $campaign['client_slug']]) }}">View client work →</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                @if ($articles->isNotEmpty())
                    <div class="section-heading" style="margin-top:2rem">
                        <div><span class="eyebrow">Insights</span><h2 class="section-title">{{ $articles->count() }} {{ $articles->count() === 1 ? 'article' : 'articles' }}.</h2></div>
                    </div>
                    <div class="article-grid">
                        @foreach ($articles as $article)
                            <article class="article-card">
                                <a href="{{ route('insights.show', $article->slug) }}" class="article-card__media"><img src="{{ \App\Features\Catalog\Support\MediaImage::url($article->imageUrl) }}" loading="lazy" alt="{{ $article->title }}"></a>
                                <div class="article-card__body">
                                    <span class="eyebrow eyebrow--plain">{{ strtoupper($article->category) }}</span>
                                    <h3><a href="{{ route('insights.show', $article->slug) }}">{{ $article->title }}</a></h3>
                                    <p>{{ $article->excerpt }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                @if ($media->isEmpty() && $creators->isEmpty() && $campaigns->isEmpty() && $articles->isEmpty() && $directoryResults === [] && $rateResults === [])
                    <div class="empty-state" style="text-align:left">
                        <h3>No results for “{{ $query }}”.</h3>
                        <p>Try a medium, a media owner, a creator niche, a client or a campaign name. You can also browse the media explorer directly.</p>
                        <div class="empty-state__actions" style="justify-content:flex-start">
                            <flux:button href="{{ route('media.index') }}" variant="primary">Browse media</flux:button>
                            <flux:button href="{{ route('media.owners') }}" variant="outline">Browse owners</flux:button>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
