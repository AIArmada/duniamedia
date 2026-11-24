@extends('layouts.app')

@section('content')
    <div class="owner-directory">
        <section class="owner-directory-hero" aria-labelledby="owner-directory-title">
            <div class="owner-directory-hero__copy">
                <span class="eyebrow">Media owners</span>
                <h1 id="owner-directory-title">Meet the networks behind <em>the media.</em></h1>
                <p>Discover Malaysia’s media owners, channel brands and advertising opportunities across television, radio, publishing, outdoor, digital and more.</p>
            </div>
            <div class="owner-directory-hero__art" aria-hidden="true">
                @foreach (collect($categories)->except('all') as $entry)
                    <span class="owner-directory-hero__icon"><x-dm-icon :name="$entry['icon']" :size="32"/></span>
                @endforeach
            </div>
        </section>

        <section aria-label="Find media owners" class="owner-directory-discovery">
            <form method="get" action="{{ route('media.owners') }}" class="owner-directory-search" role="search">
                <input type="hidden" name="category" value="{{ $category }}">
                <flux:input type="search" name="q" value="{{ $query }}" placeholder="Search media owners, networks or channels…" aria-label="Search media owners" icon="magnifying-glass" />
                <flux:button type="submit" variant="primary">Search</flux:button>
            </form>
            <nav class="owner-directory-categories" aria-label="Filter by media category">
                @foreach ($categories as $key => $entry)
                    <a href="{{ route('media.owners', array_filter(['q' => $query, 'category' => $key === 'all' ? null : $key])) }}" @if($category === $key) aria-current="true" @endif>
                        <x-dm-icon :name="$entry['icon']" :size="20"/>{{ $entry['label'] }}
                    </a>
                @endforeach
            </nav>
        </section>

        <section aria-labelledby="owner-directory-results">
            <div class="owner-directory-results">
                <h2 id="owner-directory-results">{{ $owners->count() }} {{ $owners->count() === 1 ? 'media network' : 'media networks' }} @if ($query !== '') matching “{{ $query }}” @endif</h2>
                @if ($query !== '' || $category !== 'all')
                    <a href="{{ route('media.owners') }}">Clear filters ×</a>
                @endif
            </div>
            <div class="owner-directory-grid">
                @forelse ($owners as $owner)
                    @php
                        $channels = collect($owner['channels']);
                        $preview = $channels->groupBy('category')->map(fn ($group) => $group->first())->values();
                        $preview = $preview->concat($channels)->unique('slug')->take(5);
                        $logo = ! empty($owner['logo']) ? 'assets/partners/'.$owner['logo'] : null;
                        $accent = preg_match('/^#[0-9a-f]{6}$/i', $owner['accent'] ?? '') ? $owner['accent'] : '#a4004b';
                    @endphp
                    <article class="owner-network-card" style="--owner-accent: {{ $accent }}">
                        <a class="owner-network-card__brand" href="{{ route('media.owner', $owner['slug']) }}" aria-label="Explore {{ $owner['name'] }}">
                            @if ($logo && is_file(public_path($logo)))
                                <img src="{{ \App\Features\Catalog\Support\MediaImage::url($logo) }}" alt="{{ $owner['name'] }} logo" loading="lazy" decoding="async">
                            @else
                                <span>{{ $owner['mark'] ?? $owner['name'] }}</span>
                            @endif
                        </a>
                        <div class="owner-network-card__body">
                            <h3><a href="{{ route('media.owner', $owner['slug']) }}">{{ $owner['name'] }}</a></h3>
                            <p>{{ $owner['description'] }}</p>
                            <div class="owner-network-card__count">{{ $channels->count() }} {{ $channels->count() === 1 ? 'channel' : 'channels' }} · {{ count($owner['categories']) }} {{ count($owner['categories']) === 1 ? 'group' : 'groups' }}</div>
                            <div class="owner-network-card__channels" aria-label="{{ $owner['name'] }} channel preview">
                                @foreach ($preview as $channel)
                                    @php($channelLogo = ! empty($channel['logo']) ? 'assets/partners/'.$channel['logo'] : null)
                                    <a href="{{ route('media.channel', ['owner' => $owner['slug'], 'channel' => $channel['slug']]) }}" aria-label="Explore {{ $channel['name'] }}" title="{{ $channel['name'] }}">
                                        @if ($channelLogo && is_file(public_path($channelLogo)))
                                            <img src="{{ \App\Features\Catalog\Support\MediaImage::url($channelLogo) }}" alt="{{ $channel['name'] }}" loading="lazy" decoding="async">
                                        @else
                                            <span>{{ $channel['name'] }}</span>
                                        @endif
                                    </a>
                                @endforeach
                                @if ($channels->count() > $preview->count())
                                    <a class="owner-network-card__more" href="{{ route('media.owner', $owner['slug']) }}" aria-label="Explore {{ $channels->count() - $preview->count() }} more channels">+{{ $channels->count() - $preview->count() }}</a>
                                @endif
                            </div>
                        </div>
                        <div class="owner-network-card__actions">
                            <a href="{{ route('media.owner', $owner['slug']) }}">Explore network <span aria-hidden="true">→</span></a>
                            @if (app(\App\Features\Identity\Api\ResolvesParties::class)->idForSlug($owner['slug']) !== null)
                                <a href="{{ route('rates.index', ['owner' => $owner['slug']]) }}"><x-dm-icon name="layers" :size="17"/> Rate cards</a>
                            @else
                                <a href="{{ route('media.owner', $owner['slug']) }}"><x-dm-icon name="layers" :size="17"/> Opportunities</a>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="owner-directory-empty">
                        <x-dm-icon name="search" :size="36"/>
                        <h3>No media owners match your search.</h3>
                        <p>Try another channel name or select a different media category.</p>
                        <a class="button button--primary" href="{{ route('media.owners') }}">View all media owners</a>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="owner-directory-cta" aria-labelledby="owner-directory-cta-title">
            <div class="owner-directory-cta__icon"><x-dm-icon name="chart" :size="42"/></div>
            <div>
                <span>Need a complete campaign plan?</span>
                <h2 id="owner-directory-cta-title">Media Strategy Studio</h2>
            </div>
            <p>Get personalised media recommendations, compare strategies and build a campaign that fits your budget and audience.</p>
            <a class="button button--on-dark" href="{{ route('advisor') }}">Open Media Strategy Studio <span aria-hidden="true">→</span></a>
        </section>
    </div>
@endsection
