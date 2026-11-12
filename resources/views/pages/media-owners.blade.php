@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="Media directory"
        title="The owners and <span class='editorial'>channels</span> behind the inventory."
        lede="A media owner is the organisation that controls a channel or property. Browse the groups represented by Dunia Media, then open an owner or outlet to see its current opportunities."
    >
        <x-slot:actions>
            <a class="button button--secondary" href="{{ route('media.index') }}">Browse all media</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <form method="get" action="{{ route('media.owners') }}" class="filter-horizontal" style="display:grid;grid-template-columns:1fr auto;gap:.6rem;margin-bottom:1.6rem">
                <label class="field">
                    <span class="sr-only">Search media owners</span>
                    <input type="search" name="q" value="{{ $query }}" placeholder="Search owner, channel or outlet…">
                </label>
                <button class="button button--primary" type="submit">Search directory</button>
            </form>

            @if ($query !== '')
                <p class="tiny muted" style="margin-bottom:1rem">{{ $owners->count() }} {{ $owners->count() === 1 ? 'owner' : 'owners' }} matching “{{ $query }}”. <a href="{{ route('media.owners') }}">Clear search</a></p>
            @endif

            @if ($owners->isEmpty())
                <div class="empty-state" style="text-align:left">
                    <h3>No media owners match that search.</h3>
                    <p>Try a shorter name, a channel brand, or browse the full directory.</p>
                    <a class="button button--primary" href="{{ route('media.owners') }}">View all owners</a>
                </div>
            @else
                <div class="outlet-grid">
                    @foreach ($owners as $owner)
                        <a class="outlet-tile" href="{{ route('media.owner', $owner['slug']) }}" style="align-items:flex-start">
                            <span class="outlet-tile__mark">
                                @if (! empty($owner['logo']))
                                    <img src="{{ \App\Support\MediaImage::url('assets/partners/'.$owner['logo']) }}" alt="" loading="lazy">
                                @else
                                    {{ $owner['mark'] ?? $owner['name'] }}
                                @endif
                            </span>
                            <span class="outlet-tile__name">{{ $owner['name'] }}</span>
                            <span class="tiny muted" style="display:block;margin-top:.3rem">{{ \Illuminate\Support\Str::limit($owner['description'], 120) }}</span>
                            <span class="tiny muted" style="display:block;margin-top:.5rem">{{ count($owner['outlets']) }} outlets · {{ count($owner['categories']) }} categories</span>
                            <span class="outlet-tile__arrow" aria-hidden="true">↗</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <div class="cta-panel__inner">
                <div>
                    <span class="eyebrow">Not sure which owner fits?</span>
                    <h2 class="section-title">Start with the audience and the outcome.</h2>
                    <p style="max-width:560px;margin-top:.7rem;color:rgba(255,248,247,.66)">The media advisor turns your objective, budget and timing into an illustrative direction before you shortlist owners.</p>
                </div>
                <div class="cta-panel__actions">
                    <a class="button button--on-dark" href="{{ route('advisor') }}">Open the advisor <span aria-hidden="true">↗</span></a>
                    <a class="button button--outline-light" href="{{ route('contact') }}">Talk to a specialist</a>
                </div>
            </div>
        </div>
    </section>
@endsection
