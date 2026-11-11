@extends('layouts.app')

@section('content')
    @php($heroCover = collect($media)->firstWhere('key', 'jwp-psa-kl')['poster'] ?? null)

    <section class="work-hero" aria-labelledby="work-title">
        @if ($heroCover)
            <div class="work-hero__media" aria-hidden="true">
                <img src="{{ asset($heroCover) }}" alt="" width="1600" height="900" fetchpriority="high" decoding="async">
            </div>
        @endif
        <div class="container work-hero__inner">
            <div>
                <span class="eyebrow eyebrow--pale">Selected work · {{ $stats['from'] }}—{{ $stats['to'] }}</span>
                <h1 id="work-title">Campaigns that<br><em class="editorial">moved people.</em></h1>
                <p class="work-hero__lede">A working archive of broadcast, outdoor, digital and production work for government institutions and Malaysian brands — drawn from RA Media's client records.</p>
                <div class="work-hero__actions">
                    <a href="#archive" class="button button--on-dark">Explore the archive <span aria-hidden="true">↓</span></a>
                    <a href="{{ route('contact') }}" class="button button--outline-light">Start a campaign</a>
                </div>
                <p class="work-hero__note">Updated {{ $archiveUpdated }} · {{ $stats['clients'] }} organisations · {{ $stats['campaigns'] }} campaign records</p>
            </div>
            <div class="work-stats" aria-label="Archive at a glance">
                <div class="work-stat"><strong>{{ $stats['clients'] }}</strong><span>Organisations</span></div>
                <div class="work-stat"><strong>{{ $stats['campaigns'] }}</strong><span>Campaign records</span></div>
                <div class="work-stat"><strong>{{ $stats['channels'] }}</strong><span>Media channels</span></div>
                <div class="work-stat"><strong>{{ $stats['years'] }}</strong><span>Years of work</span></div>
            </div>
        </div>
    </section>

    <section class="section section--dark" id="work-media" aria-labelledby="work-media-title">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow eyebrow--pale">On screen &amp; on air</span>
                    <h2 id="work-media-title" class="section-title">Recent work, as it aired.</h2>
                    <p style="color:rgba(255,248,247,.66)">Full broadcast files from the project archive — television interviews, public service films, a Ramadan commercial and a TVC voiceover.</p>
                </div>
                <div class="section-heading__aside">
                    <a class="text-link text-link--dark" href="#archive">Jump to the archive <span aria-hidden="true">↓</span></a>
                </div>
            </div>

            <div class="work-media__grid">
                @foreach (array_slice($media, 0, 6) as $item)
                    <article id="work-media-{{ $item['key'] }}" class="work-media-card" wire:key="work-media-{{ $item['key'] }}">
                        @if ($item['type'] === 'video')
                            <div class="work-media-card__frame" data-video-cover>
                                <video preload="metadata" playsinline poster="{{ asset($item['poster']) }}">
                                    <source src="{{ route('work.media', $item['key']) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <button type="button" class="work-media-card__play" data-video-play aria-label="Play {{ $item['title'] }}">
                                    <span class="work-media-card__play-icon" aria-hidden="true"></span>
                                    <span>Play film</span>
                                </button>
                                <span class="work-media-card__badge">Video · {{ $item['duration'] }}</span>
                            </div>
                        @else
                            <div class="work-media-card__frame work-media-card__frame--audio">
                                <img src="{{ asset($item['poster']) }}" alt="" loading="lazy" decoding="async">
                                <div class="work-audio">
                                    <span class="work-audio__label">Audio · {{ $item['duration'] }}</span>
                                    <audio controls preload="metadata" src="{{ route('work.media', $item['key']) }}"></audio>
                                </div>
                            </div>
                        @endif
                        <div class="work-media-card__body">
                            <span class="work-media-card__date">{{ $item['date'] }}</span>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['description'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            <p class="tiny" style="margin-top:1.2rem;color:rgba(255,248,247,.5)">Video covers are frames from the supplied broadcasts; the audio cover is an illustrative still. Files are hosted privately and streamed on demand.</p>
        </div>
    </section>

    <section class="section section--cream" id="clients" aria-labelledby="work-clients-title">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Client roster</span>
                    <h2 id="work-clients-title" class="section-title">Institutions and brands we've worked with.</h2>
                    <p>{{ $stats['government'] }} government and public organisations, {{ $stats['business'] }} business and brand clients. Select a client to see their campaign record.</p>
                </div>
            </div>

            @foreach (['government' => 'Government & public institutions', 'business' => 'Business & brands'] as $sector => $label)
                <section class="work-client-group" aria-label="{{ $label }}">
                    <header class="work-client-group__header">
                        <span>{{ $label }}</span>
                        <span>{{ count($clientsBySector[$sector] ?? []) }} organisations</span>
                    </header>
                    <div class="work-client-grid">
                        @foreach ($clientsBySector[$sector] ?? [] as $client)
                            @php($campaignCount = $campaignCounts[$client['slug']] ?? 0)
                            @if ($campaignCount > 0)
                                <a class="work-client-card" href="{{ route('work.index', ['client' => $client['slug']]) }}#archive" aria-label="View campaigns for {{ $client['name'] }}">
                            @else
                                <article class="work-client-card is-static">
                            @endif
                                <span class="work-client-card__mark @if (empty($client['logo'])) is-typeset @endif">
                                    @if (! empty($client['logo']))
                                        <img src="{{ asset($client['logo']) }}" alt="" loading="lazy" decoding="async">
                                    @else
                                        <span>{{ $client['mark'] ?? $client['short_name'] }}</span>
                                    @endif
                                </span>
                                <span class="work-client-card__meta">
                                    <h3>{{ $client['name'] }}</h3>
                                    <span>{{ $campaignCount > 0 ? $campaignCount.' '.($campaignCount === 1 ? 'campaign' : 'campaigns') : 'Client roster' }}</span>
                                </span>
                            @if ($campaignCount > 0)
                                </a>
                            @else
                                </article>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </section>

    <livewire:work-explorer/>

    <section class="work-note">
        <div class="container work-note__inner">
            <p>{{ $sourceNote }}</p>
            <a href="{{ route('about') }}#experience">About RA Media <span aria-hidden="true">→</span></a>
        </div>
    </section>
@endsection
