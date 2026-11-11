@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="Insights & ideas"
        title="Better questions. <span class='editorial'>Smarter marketing.</span>"
        lede="Practical perspectives on media, creators, AI and measurable campaigns. We publish when there is something useful to say — not to fill a content calendar."
    />

    <section class="section">
        <div class="container">
            @if ($articles->isEmpty())
                <div class="empty-state" style="text-align:left">
                    <span class="eyebrow">Editorial in preparation</span>
                    <h3>Insights are being prepared.</h3>
                    <p style="margin-left:0">We are writing the first set of practical pieces around the questions clients actually ask. In the meantime, these are the areas we will cover.</p>
                    <div class="empty-state__actions" style="justify-content:flex-start">
                        <a class="button button--primary" href="{{ route('media.index') }}">Explore media opportunities <span aria-hidden="true">↗</span></a>
                        <a class="button button--secondary" href="{{ route('contact') }}">Speak with our team</a>
                    </div>
                </div>

                <div class="topic-grid" style="margin-top:var(--grid-gap)">
                    <article class="topic"><span class="tag tag--blush">Media strategy</span><h3>Choosing a media mix</h3><p>How to move from a channel list to a plan with a clear job for each part.</p></article>
                    <article class="topic"><span class="tag tag--blush">Outdoor</span><h3>What makes a billboard work</h3><p>Context, dwell time, distance and the discipline of one idea.</p></article>
                    <article class="topic"><span class="tag tag--blush">Creators</span><h3>Fit over follower count</h3><p>Why the right creator can outperform a much larger but mismatched audience.</p></article>
                    <article class="topic"><span class="tag tag--blush">AI &amp; work</span><h3>Practical AI adoption</h3><p>Where AI helps, where it does not, and how teams keep review in the loop.</p></article>
                    <article class="topic"><span class="tag tag--blush">Production</span><h3>Making content for the channel</h3><p>Designing the idea and the asset for the place it will actually appear.</p></article>
                    <article class="topic"><span class="tag tag--blush">Measurement</span><h3>From awareness to enquiries</h3><p>Connecting offline media, digital response and the follow-up that decides the outcome.</p></article>
                </div>
            @else
                <div class="article-grid">
                    @foreach ($articles as $article)
                        <article class="article-card">
                            <a href="{{ route('insights.show', $article->slug) }}" class="article-card__media">
                                <img src="{{ \App\Support\MediaImage::url($article->image) }}" loading="lazy" alt="{{ $article->title }}">
                            </a>
                            <div class="article-card__body">
                                <span class="eyebrow eyebrow--plain">{{ strtoupper($article->category) }}</span>
                                <h3><a href="{{ route('insights.show', $article->slug) }}">{{ $article->title }}</a></h3>
                                <p>{{ $article->excerpt }}</p>
                                <a class="text-link" href="{{ route('insights.show', $article->slug) }}">Read article <span aria-hidden="true">→</span></a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="pagination-wrap">{{ $articles->links('pagination.brand') }}</div>
            @endif
        </div>
    </section>
@endsection
