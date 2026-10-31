@extends('layouts.app')

@section('content')
    <section class="work-hero" aria-labelledby="work-title">
        <div class="work-hero__grid" aria-hidden="true"></div>
        <div class="container work-hero__inner">
            <div class="work-hero__copy">
                <span class="eyebrow pale">SELECTED WORK · {{ $stats['from'] }}—{{ $stats['to'] }}</span>
                <h1 id="work-title">Campaigns that<br><em>moved people.</em></h1>
                <p class="work-hero__lede">A working archive of broadcast, outdoor, digital and production work for government institutions and Malaysian brands — drawn from RA Media's client records.</p>
                <div class="work-hero__actions">
                    <a href="#archive" class="btn btn-white">Explore the archive <span aria-hidden="true">↓</span></a>
                    <a href="{{ route('contact') }}" class="btn btn-ghost-light">Start a campaign</a>
                </div>
                <p class="work-hero__note">Updated {{ $archiveUpdated }} · {{ $stats['clients'] }} organisations · {{ $stats['campaigns'] }} campaign records</p>
            </div>

            <div class="work-hero__stats" aria-label="Archive at a glance">
                <div class="work-stat">
                    <strong>{{ $stats['clients'] }}</strong>
                    <span>Organisations</span>
                </div>
                <div class="work-stat">
                    <strong>{{ $stats['campaigns'] }}</strong>
                    <span>Campaign records</span>
                </div>
                <div class="work-stat">
                    <strong>{{ $stats['channels'] }}</strong>
                    <span>Media channels</span>
                </div>
                <div class="work-stat">
                    <strong>{{ $stats['years'] }}</strong>
                    <span>Years of work</span>
                </div>
            </div>
        </div>
        <div class="work-hero__caption">
            <span>TV</span><span>Radio</span><span>Outdoor</span><span>Digital</span><span>Print</span><span>Events</span><span>Production</span>
        </div>
    </section>

    <section class="work-media" aria-labelledby="work-media-title">
        <div class="container">
            <div class="work-section-heading work-section-heading--light">
                <div>
                    <span class="eyebrow pale">ON SCREEN &amp; ON AIR</span>
                    <h2 id="work-media-title">Recent work,<br><span>as it aired.</span></h2>
                </div>
                <p>Full broadcast files from the project archive — two TV9 interviews, a Ramadan commercial and a TVC voiceover.</p>
            </div>

            <div class="work-media__grid">
                @foreach ($media as $item)
                    <article class="work-media-card @if ($item['type'] === 'audio') work-media-card--audio @endif">
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
                                    <div class="work-audio__wave" aria-hidden="true">
                                        @for ($i = 0; $i < 32; $i++)
                                            <span style="--i: {{ $i }}"></span>
                                        @endfor
                                    </div>
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

            <p class="work-media__note">Video covers are frames from the supplied broadcasts; the audio cover is an illustrative still. Media files are streamed from the project's data archive, so large files may take a moment to load.</p>
        </div>
    </section>

    <section class="work-clients" id="clients" aria-labelledby="work-clients-title">
        <div class="container">
            <div class="work-section-heading">
                <div>
                    <span class="eyebrow">CLIENT ROSTER</span>
                    <h2 id="work-clients-title">Institutions and brands<br><span>we've worked with.</span></h2>
                </div>
                <p>{{ $stats['government'] }} government and public organisations, {{ $stats['business'] }} business and brand clients. Select a client to see their campaign record.</p>
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
                                <div class="work-client-card__mark @if (empty($client['logo'])) is-typeset @endif @if (($client['mark_style'] ?? '') === 'is-mark--light') is-mark--light @endif">
                                    @if (!empty($client['logo']))
                                        <img src="{{ asset($client['logo']) }}" alt="" loading="lazy" decoding="async">
                                    @else
                                        <span class="work-client-card__wordmark {{ $client['mark_style'] ?? '' }}">{!! nl2br(e(str_replace('\\n', "\n", $client['mark']))) !!}</span>
                                    @endif
                                </div>
                                <div class="work-client-card__meta">
                                    <h3>{{ $client['name'] }}</h3>
                                    <span>{{ $campaignCount > 0 ? $campaignCount.' '.($campaignCount === 1 ? 'campaign' : 'campaigns') : 'Client roster' }}</span>
                                </div>
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

    <livewire:work-explorer />

    <section class="work-note">
        <div class="container work-note__inner">
            <p>{{ $sourceNote }}</p>
            <a href="{{ route('about') }}#experience">About RA Media <span aria-hidden="true">→</span></a>
        </div>
    </section>
@endsection
