@extends('layouts.app')

@section('content')
    <div class="creator-profile-page">
        <section class="creator-profile-hero" aria-labelledby="creator-title">
            <div class="container creator-profile-hero__inner">
                <nav class="creator-profile-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span>
                    <a href="{{ route('creators.index') }}">Creators</a><span aria-hidden="true">/</span>
                    <span aria-current="page">{{ $name }}</span>
                </nav>
                <div class="creator-profile-hero__copy">
                    <span class="creator-profile-kicker">{{ $creator->is_featured ? 'Featured creator' : 'Creator profile' }}</span>
                    <h1 id="creator-title">{{ $name }}</h1>
                    @if ($handle !== '')<p class="creator-profile-handle">{{ '@'.$handle }}</p>@endif
                    @if ($label = $editorial['label'] ?? $creator->primary_niche)
                        <div class="creator-profile-category"><x-dm-icon :name="$editorial['icon'] ?? 'spark'" :size="22"/>{{ $label }}</div>
                    @endif
                    @if ($creator->bio)<p class="creator-profile-bio">{{ $creator->bio }}</p>@endif
                    <div class="creator-profile-actions">
                        @if ($socialUrl)
                            <a class="button button--primary" href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer"><x-dm-icon :name="strtolower((string) $creator->social_platform) === 'tiktok' ? 'tiktok' : 'play'" :size="20"/> View {{ $creator->social_platform }} <span aria-hidden="true">↗</span></a>
                        @endif
                        <a class="button button--secondary" href="{{ $enquiryUrl }}">Discuss a collaboration <span aria-hidden="true">↗</span></a>
                    </div>
                </div>
                <div class="creator-profile-hero__portrait">
                    @if ($portrait)
                        <x-creator-portrait :photo="$portrait" :alt="'Portrait of '.$name" sizes="(max-width: 720px) 100vw, 52vw" priority/>
                    @else
                        <div class="creator-profile-placeholder" aria-hidden="true"><x-dm-icon name="creators" :size="80"/></div>
                    @endif
                </div>
            </div>
        </section>

        <section class="container creator-profile-media" aria-label="Social profile and portraits">
            <div class="creator-profile-social">
                <span class="creator-profile-kicker">Social profile</span>
                @if ($creator->social_platform)<h2>@if (strtolower($creator->social_platform) === 'tiktok')<x-dm-icon name="tiktok" :size="38"/>@endif {{ $creator->social_platform }}</h2>@else<h2>Creator network</h2>@endif
                @if ($socialUrl)
                    <a class="creator-profile-social__handle" href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer">{{ '@'.$handle }} <span aria-hidden="true">↗</span></a>
                @elseif ($handle !== '')
                    <p class="creator-profile-social__handle">{{ '@'.$handle }}</p>
                @endif
                @if ($creator->bio)<p>{{ $creator->bio }}</p>@endif
                @if ($metrics !== [])
                    <dl class="creator-profile-metrics">
                        @foreach ($metrics as $metric => $value)
                            <div><dt>{{ $metric }}</dt><dd>{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                    <p class="creator-profile-metric-date">Metrics as of {{ $metricsDate->format('j M Y') }}</p>
                @else
                    <p class="creator-profile-insights"><x-dm-icon name="users" :size="28"/><span>Enquire about current audience insights.</span></p>
                @endif
                @if ($creator->location_label)<p class="creator-profile-location"><x-dm-icon name="pin" :size="18"/>{{ $creator->location_label }}</p>@endif
            </div>
            @foreach ($gallery as $photo)
                <figure class="creator-profile-photo">
                    <x-creator-portrait :photo="$photo" :alt="'Portrait of '.$name.' — '.($loop->iteration + 1)" sizes="(max-width: 720px) 50vw, 33vw"/>
                </figure>
            @endforeach
        </section>

        @if (! empty($editorial['topics']) || $creator->primary_niche)
            <section class="container creator-profile-focus" aria-labelledby="creator-focus-title">
                <div class="creator-profile-focus__intro">
                    <span class="creator-profile-kicker">What they create</span>
                    <h2 id="creator-focus-title">Content focus</h2>
                    @if (! empty($editorial['focus_intro']))<p>{{ $editorial['focus_intro'] }}</p>@endif
                </div>
                <div class="creator-profile-focus__topics">
                    @forelse ($editorial['topics'] ?? [] as $topic)
                        <article>
                            <span class="creator-profile-topic-icon"><x-dm-icon :name="$topic['icon']" :size="30"/></span>
                            <h3>{{ $topic['title'] }}</h3>
                            <p>{{ $topic['description'] }}</p>
                        </article>
                    @empty
                        <article><span class="creator-profile-topic-icon"><x-dm-icon name="spark" :size="30"/></span><h3>{{ $creator->primary_niche }}</h3>@if ($creator->bio)<p>{{ $creator->bio }}</p>@endif</article>
                    @endforelse
                </div>
            </section>
        @endif

        <section class="container creator-profile-collaboration" aria-labelledby="creator-collaboration-title">
            <div class="creator-profile-collaboration__panel">
                <div>
                    <span class="creator-profile-kicker">Work together</span>
                    <h2 id="creator-collaboration-title">Enquiries &amp; collaborations</h2>
                    <p>Interested in working with {{ $name }} for your campaign, content or education initiative? Tell us about your goals and we can discuss the next steps.</p>
                    <a class="button button--primary" href="{{ $enquiryUrl }}">Discuss a collaboration <span aria-hidden="true">↗</span></a>
                </div>
                @if (! empty($editorial['collaboration_art']))
                    <div class="creator-profile-collaboration__photo" aria-hidden="true">
                        <img src="{{ asset($editorial['collaboration_art']) }}" alt="" width="421" height="202" loading="lazy" decoding="async">
                    </div>
                @else
                    <div class="creator-profile-collaboration__art" aria-hidden="true">
                        <x-dm-icon name="users" :size="100"/>
                    </div>
                @endif
            </div>
            <a class="creator-profile-back" href="{{ route('creators.index') }}"><span aria-hidden="true">←</span> Explore all creators</a>
        </section>
    </div>
@endsection
